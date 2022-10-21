<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * @property $type_id
 * @property $is_active
 * @property $code
 * @property $value
 * @property $limit
 * @property $email
 * @property $expires_at
 * @property $coupon_type
 * @property $is_single
 * @property $times_used
 * @property $status
 *
 * @mixin Builder
 */
class Coupon extends Model
{
    use HasFactory;

    protected $dates = ['expires_at'];
    protected $fillable = ['type_id', 'is_active', 'code', 'value', 'limit', 'email_id', 'expires_at'];

    public static function generateCode(int $length = 6): string
    {
        $code = Str::random($length);

        while ((new Coupon())->where('code', $code)->exists()) {
            $code = Str::random($length);
        }

        return $code;
    }

    public static function paramCreate(array $params): ?Coupon
    {
        if (!self::paramsValid($params['limit'] ?? null, $params['expires_at'] ?? null, $params['email'] ?? null)) { return null; }

        $email = $params['email'];
        unset($params['email']);

        $coupon = (new Coupon())->create($params + ['code' => self::generateCode()]);

        if ($coupon->is_single) {
            $email = Email::paramCreate($email);
            $coupon->email_id = $email->id;
            $coupon->save();
        }

        return $coupon;
    }

    public function paramUpdate(array $params): ?Coupon
    {
        if (!self::paramsValid($params['limit'] ?? null, $params['expires_at'] ?? null, $params['email'] ?? null)) { return null; }

        $email = $params['email'];
        unset($params['email']);

        $this->fill($params);
        $this->save();

        if($this->is_single) {
            $email = Email::paramCreate($email);
            $this->email_id = $email->id;
            $this->save();
        }
        return $this;
    }

    public static function isSingle(string $couponType): bool
    {
        return in_array($couponType, ['single', 'single-expires']);
    }

    public function canUse(string $email): bool
    {
        if ($this->status === 'inactive') { return false; }
        if ($this->limit && $this->limit <= $this->times_used) { return false; }
        if ($this->is_single && $this->email->email !== $email) { return false; }
        if ($this->emails()->where('email', $email)->exists()) { return false; }

        return true;
    }

    public function useCoupon(string $email): bool
    {
        if (!$this->canUse($email)) { return false; }

        $email = Email::paramCreate($email);

        $this->emails()->attach($email);

        return true;
    }

    public static function paramsValid(?int $limit, ?string $expiresAt, ?string $email): bool
    {
        if ((self::isSingle(self::getCouponType($limit, $expiresAt))) && !$email) {
            return false;
        }

        return true;
    }

    public static function getCouponType(?int $limit, ?string $expiresAt): string
    {
        if ($limit === 1) {
            if ($expiresAt) {
                return 'single-expires';
            }

            return 'single';
        }

        if ($limit > 1) {
            if ($expiresAt) {
                return 'multi-expires';
            }

            return 'multi-limit';
        }

        return 'unlimited';
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }

    public function email(): BelongsTo
    {
        return $this->belongsTo(Email::class);
    }

    public function emails(): BelongsToMany
    {
        return $this->belongsToMany(Email::class);
    }

    public function getIsSingleAttribute(): bool
    {
        return self::isSingle($this->coupon_type);
    }

    public function getCouponTypeAttribute(): string
    {
        return self::getCouponType($this->limit, $this->expires_at);
    }

    public function getStatusAttribute(): string
    {
        if ($this->is_active || ($this->expires_at && $this->expires_at < Carbon::now())) {
            if (!count($this->emails)) { return 'active'; }
            if (($this->limit === null) || ($this->limit > count($this->emails))) { return 'used'; }
        }

        return 'inactive';
    }

    public function getTimesUsedAttribute(): int
    {
        return $this->emails()->count();
    }
}
