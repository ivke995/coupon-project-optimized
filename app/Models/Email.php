<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property string $email
 * @property int $coupons_used
 * @property string|null $first_coupon_used_at
 * @property string|null $last_coupon_used_at
 *
 * @mixin Builder
 */
class Email extends Model
{
    use HasFactory;

    protected $fillable = ['email'];

    public static function paramCreate(string $email): Email
    {
        if ((new Email())->where('email', $email)->exists()) {
            return (new Email())->where('email', $email)->first();
        }

        return (new Email())->create(['email' => $email]);
    }

    public function coupons(): BelongsToMany
    {
        return $this->belongsToMany(Coupon::class);
    }

    public function getFirstCouponUsedAttribute(): ?Coupon
    {
        return $this->belongsToMany(Coupon::class)
            ->withPivot(['created_at'])
            ->orderBy('coupon_email.created_at')
            ->first();
    }

    public function getLastCouponUsedAttribute(): ?Coupon
    {
        return $this->belongsToMany(Coupon::class)
            ->withPivot(['created_at'])
            ->orderBy('coupon_email.created_at', 'desc')
            ->first();
    }

    public function getCouponsUsedAttribute(): int
    {
        return $this->coupons()->count();
    }

    public function getFirstCouponUsedAtAttribute(): ?string
    {
        return $this->first_coupon_used ? $this->first_coupon_used->pivot->created_at : null;
    }

    public function getLastCouponUsedAtAttribute(): ?string
    {
        return $this->last_coupon_used ? $this->last_coupon_used->pivot->created_at : null;
    }
}
