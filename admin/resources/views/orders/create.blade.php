@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Create New Order</h1>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        {{-- Customer Selection --}}
        <div class="form-group mb-4">
            <label for="customer_id" class="form-label">Customer</label>
            <select name="customer_id" id="customer_id" class="form-control">
                @foreach($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                @endforeach
            </select>
            @error('customer_id')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        {{-- Address Section --}}
        <h3 class="mb-4">Shipping Address</h3>
        <div class="row">
            <div class="col-md-6">
                <label for="street" class="form-label">Street</label>
                <input type="text" name="address[street]" class="form-control" value="{{ old('address.street') }}">
                @error('address.street')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="city" class="form-label">City</label>
                <input type="text" name="address[city]" class="form-control" value="{{ old('address.city') }}">
                @error('address.city')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="state" class="form-label">State</label>
                <input type="text" name="address[state]" class="form-control" value="{{ old('address.state') }}">
                @error('address.state')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" name="address[postal_code]" class="form-control" value="{{ old('address.postal_code') }}">
                @error('address.postal_code')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        {{-- Product Items --}}
        <h3 class="mb-4">Order Items</h3>
        <div id="product-items">
            @foreach($products as $product)
                <div class="form-group">
                    <label>{{ $product->name }} (${{ number_format($product->price / 100, 2) }})</label>
                    <input type="number" name="items[{{ $product->id }}][quantity]" class="form-control" placeholder="Quantity" min="1">
                    <input type="hidden" name="items[{{ $product->id }}][product_id]" value="{{ $product->id }}">
                </div>
            @endforeach
        </div>

        {{-- Payment Method --}}
        <div class="form-group mb-4">
            <label for="payment_method" class="form-label">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control">
                <option value="credit_card">Credit Card</option>
                <option value="paypal">PayPal</option>
                <option value="razorpay">Razorpay</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Create Order</button>
    </form>
</div>
@endsection
