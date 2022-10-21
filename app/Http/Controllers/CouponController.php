<?php

namespace App\Http\Controllers;

use App\Http\Requests\Coupon\StoreRequest;
use App\Models\Coupon;
use App\Models\Email;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    public function index(Request $request)
    {
        $status = $request->query('status');

        $coupons = Coupon::all();

        if ($status) {
            $coupons = $coupons->where('status', $status);
        }

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        $coupons = Coupon::all();
        $types = Type::all();

        return view('coupons.create', compact('coupons', 'types'));
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $coupon = Coupon::paramCreate($request->validated());

        if(!$coupon) {
            abort(400);
        }

        return redirect()
            ->route('coupons.all')
            ->with('success', 'You have created your coupon successfully');
    }

    public function edit(Coupon $coupon)
    {
        $types = Type::all();

        return view('coupons.edit', compact('coupon', 'types'));
    }

    public function update(StoreRequest $request, Coupon $coupon)
    {
        $coupon = $coupon->paramUpdate($request->validated());

        if(!$coupon) { abort(403); }

        return redirect()->route('coupons.index');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index');
    }
}
