<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coupon\StoreRequest;
use App\Models\Coupon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CouponController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        $coupon = Coupon::paramCreate($request->validated());

        if (!$coupon) {
            return new JsonResponse(['success' => false, 'message' => 'Failed to create coupons']);
        }

        return new JsonResponse(['success' => true, 'message' => 'Coupon created successfully', 'code' => $coupon->code]);
    }

    public function show(Request $request, Coupon $coupon): JsonResponse
    {
        if ($coupon->useCoupon($request->query('email'))) {
            return new JsonResponse(['success' => true, 'message' => 'Coupon used successfully!']);
        }

        return new JsonResponse(['success' => false, 'message' => 'Coupon cannot be used!']);
    }


}
