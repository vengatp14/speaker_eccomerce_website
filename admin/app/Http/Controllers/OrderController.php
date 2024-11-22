<?php
namespace App\Http\Controllers;

use App\Models\{Order, OrderItem, Address, Product, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Validator};

class OrderController extends Controller
{
    // In your OrderController
public function index(Request $request)
{
    $query = Order::with('customer');

    // Apply filters
    if ($request->filled('status')) {
        $query->where('order_status', $request->status);
    }

    if ($request->filled('payment_status')) {
        $query->where('payment_status', $request->payment_status);
    }

    if ($request->filled('date_from')) {
        $query->whereDate('created_at', '>=', $request->date_from);
    }

    if ($request->filled('date_to')) {
        $query->whereDate('created_at', '<=', $request->date_to);
    }

    $orders = $query->latest()->paginate(10);

    return view('orders.index', compact('orders'));
}
    public function create()
    {
        $customers = User::all();
        $products = Product::all();

        return view('orders.create', compact('customers', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'address' => 'required|array',
            'name' => 'required|string',
            'address.street' => 'required|string',
            'address.city' => 'required|string',
            'address.state' => 'required|string',
            'address.country' => 'required|string',
            'address.postal_code' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|in:credit_card,paypal,razorpay',
        ]);

        try {
            DB::beginTransaction();

            $address = Address::firstOrCreate(
                array_merge(['customer_id' => $request->customer_id], $request->address)
            );

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'address_id' => $address->id,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'processing', // Add default order status
            ]);

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            $order->total_price = $this->calculateOrderTotal($order);
            $order->save();

            DB::commit();
            return redirect()->route('orders.show', $order)
                ->with('success', 'Order created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create order. ' . $e->getMessage());
        }
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'address', 'customer']);
        $order->total_price = $this->calculateOrderTotal($order);
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $order->load(['items.product', 'address', 'customer']);
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,completed,failed',
            'order_status' => 'required|in:processing,shipped,delivered,cancelled', // Add order status validation
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }

    public function destroy(Order $order)
    {
        try {
            DB::transaction(function () use ($order) {
                $order->items()->delete();
                $order->delete();
            });

            return redirect()->route('orders.index')
                ->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete order.');
        }
    }

   private function calculateOrderTotal($order)
{
    $total = 0;

    foreach ($order->items as $item) {
        $product = $item->product;

        if ($product) {
            $price = $product->legacy_price ?? $product->price ?? 0;
            $total += $price * $item->quantity;

            \Log::debug("Product ID: {$product->id}, Price: {$price}, Quantity: {$item->quantity}");
        }
    }

    \Log::debug("Calculated Total: " . $total);

    return number_format($total / 100, 2); // Assuming prices are stored in cents
}
// In your OrderController

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:processing,shipped,delivered,cancelled',
        ]);

        $order->update($validated);

        return redirect()->route('orders.show', $order)
            ->with('success', 'Order status updated successfully.');
    }
}
