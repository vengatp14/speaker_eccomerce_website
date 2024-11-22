@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Order #{{ $order->id }}</h1>

    {{-- Customer Information --}}
    <h3 class="mb-4">Customer Details</h3>
    <p><strong>Name:</strong> {{ $order->customer->name }}</p>
    <p><strong>Email:</strong> {{ $order->customer->email }}</p>

    {{-- Address Information --}}
    <h3 class="mb-4">Shipping Address</h3>
    <p>{{ $order->address->street }}, {{ $order->address->city }}, {{ $order->address->state }}, {{ $order->address->country }}, {{ $order->address->postal_code }}</p>

    {{-- Order Items --}}
    <h3 class="mb-4">Order Items</h3>
    <ul>
        @foreach($order->items as $item)
            <li>{{ $item->product->name }} x {{ $item->quantity }} (Unit Price: ${{ number_format($item->product->price / 100, 2) }})</li>
        @endforeach
    </ul>

    {{-- Order Summary --}}
    <h3 class="mt-4">Order Summary</h3>
    <p><strong>Total Price:</strong> ${{ number_format($order->total_price, 2) }}</p>

    {{-- Order Status --}}
    <h3 class="mt-4">Order Status</h3>
    <p>{{ ucfirst($order->order_status) }}</p>

    {{-- Payment Status --}}
    <h3 class="mt-4">Payment Status</h3>
    <p>{{ ucfirst($order->payment_status) }}</p>

    {{-- Actions --}}
    <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary mt-4">Edit Order</a>
</div>
@endsection
