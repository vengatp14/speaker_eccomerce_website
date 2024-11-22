<?php
// app/Http/Controllers/API/OrderController.php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of all orders
     */
    public function index()
    {
        $orders = Order::with(['items.product', 'address', 'customer'])->get();
        return response()->json($orders);
    }

    /**
     * Get all orders and their items for a specific customer
     *
     * @param int $customerId
     * @return \Illuminate\Http\JsonResponse
     */
  public function getCustomerOrders($customerId)
{
    try {
        $orders = Order::where('customer_id', $customerId)->with(['items.product', 'address'])->get();

        return response()->json([
            'orders' => $orders,
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to retrieve orders',
        ], 500);
    }
}


    /**
     * Store a newly created order in storage.
     */
 public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'customer_id' => 'required|exists:users,id',
        'address' => 'required|array',
        'address.street' => 'required|string',
        'address.city' => 'required|string',
        'address.state' => 'required|string',
        'address.country' => 'required|string',
        'address.postal_code' => 'required|string',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
        'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
        'total_price' => 'required|numeric|min:0',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    DB::beginTransaction();
    try {
        $calculated_total = 0;
        $has_null_prices = false;
        $debug_info = [
            'items' => [],
            'null_price_products' => []
        ];

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);

            if (!$product) {
                DB::rollBack();
                return response()->json(['error' => 'Product not found', 'product_id' => $item['product_id']], 404);
            }

            if ($product->price === null) {
                $has_null_prices = true;
                $debug_info['null_price_products'][] = $item['product_id'];
                continue;
            }

            $item_total = $product->price * $item['quantity'];
            $calculated_total += $item_total;

            $debug_info['items'][] = [
                'product_id' => $item['product_id'],
                'product_price' => $product->price,
                'quantity' => $item['quantity'],
                'item_total' => $item_total
            ];
        }

        if ($has_null_prices) {
            Log::warning('Order includes products with null prices', $debug_info);
        }

        if (!$has_null_prices && abs($calculated_total - $request->total_price) > 0.01) {
            DB::rollBack();
            return response()->json([
                'error' => 'Total price mismatch',
                'calculated_total' => $calculated_total,
                'provided_total' => $request->total_price,
                'debug_info' => $debug_info
            ], 422);
        }

        $address = Address::firstOrCreate([
            'customer_id' => $request->customer_id,
            'street' => $request->address['street'],
            'city' => $request->address['city'],
            'state' => $request->address['state'],
            'country' => $request->address['country'],
            'postal_code' => $request->address['postal_code'],
        ]);

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'address_id' => $address->id,
            'payment_method' => $request->payment_method,
            'total_price' => $has_null_prices ? $request->total_price : $calculated_total,
            'payment_status' => 'pending',
        ]);

        foreach ($request->items as $item) {
            $product = Product::findOrFail($item['product_id']);
            $price = $product->price ?? 0;
            $subtotal = $price * $item['quantity'];
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $price,
                'subtotal' => $subtotal,
            ]);
        }

        DB::commit();
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load('items.product', 'address', 'customer'),
            'debug_info' => $debug_info
        ], 201);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Order creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'message' => 'Order creation failed',
            'error' => $e->getMessage()
        ], 500);
    }
}

    /**
     * Display the specified order.
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'address', 'customer'])->findOrFail($id);
        return response()->json($order);
    }

    /**
     * Update the specified order payment status.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,completed,failed',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($id);
        $order->update(['payment_status' => $request->payment_status]);

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order->load('items.product', 'address', 'customer')
        ]);
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);

        DB::beginTransaction();
        try {
            $order->items()->delete();
            $order->delete();
            DB::commit();
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order deletion failed', 'error' => $e->getMessage()], 500);
        }
    }
}
