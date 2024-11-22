<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Resources\CouponResource;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return CouponResource::collection($coupons);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|unique:coupons|max:255',
            'type' => ['required', Rule::in(['fixed', 'percent'])],
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'expiry_date' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        $coupon = Coupon::create($validated);

        return new CouponResource($coupon);
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon)
    {
        return new CouponResource($coupon);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => ['required', 'max:255', Rule::unique('coupons')->ignore($coupon)],
            'type' => ['required', Rule::in(['fixed', 'percent'])],
            'value' => 'required|numeric|min:0',
            'minimum_amount' => 'nullable|numeric|min:0',
            'max_uses' => 'required|integer|min:1',
            'expiry_date' => 'nullable|date|after:today',
            'is_active' => 'boolean'
        ]);

        $coupon->update($validated);

        return new CouponResource($coupon);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return response()->json(['message' => 'Coupon deleted successfully'], 200);
    }
}
