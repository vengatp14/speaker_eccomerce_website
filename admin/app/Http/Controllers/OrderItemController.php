<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Http\Requests\OrderItemRequest;

class OrderItemController extends Controller
{
    public function index()
    {
        return OrderItem::all();
    }

    public function store(OrderItemRequest $request)
    {
        $validated = $request->validated();
        return OrderItem::create($validated);
    }

    public function show(OrderItem $orderItem)
    {
        return $orderItem;
    }

    public function update(OrderItemRequest $request, OrderItem $orderItem)
    {
        $validated = $request->validated();
        $orderItem->update($validated);
        return $orderItem;
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return response()->json(null, 204);
    }
}
