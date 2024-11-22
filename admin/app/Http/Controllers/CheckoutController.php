<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function applyCoupon(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'coupon_code' => 'required|string|max:255',
        ]);

        $coupon = Coupon::where('code', $request->coupon_code)->first();

        if (!$coupon) {
            return back()->with('error', 'Invalid coupon code.');
        }

        if ($coupon->expiry_date && now()->gt($coupon->expiry_date)) {
            return back()->with('error', 'This coupon has expired.');
        }

        if ($coupon->max_uses <= 0) {
            return back()->with('error', 'This coupon has been fully redeemed.');
        }

        // Apply coupon logic here (e.g., adjust the order total)
        // For example, you might want to store the discount in the session or apply it directly to the cart
        // $discount = calculateDiscount($coupon, $cartTotal);

        return back()->with('success', 'Coupon applied successfully.');
    }
}
