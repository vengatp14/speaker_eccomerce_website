<?php


namespace App\Http\Controllers;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(10);
        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.create');
    }

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

        Coupon::create($validated);

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        return view('coupons.show', compact('coupon'));
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.edit', compact('coupon'));
    }

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

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }
}
