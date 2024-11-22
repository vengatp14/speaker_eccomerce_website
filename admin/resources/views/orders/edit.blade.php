@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Edit Order #{{ $order->id }}</h1>

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

    <form action="{{ route('orders.update', $order) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group mb-4">
            <label for="order_status" class="form-label">Order Status</label>
            <select name="order_status" id="order_status" class="form-control">
                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="payment_status" class="form-label">Payment Status</label>
            <select name="payment_status" id="payment_status" class="form-control">
                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="completed" {{ $order->payment_status == 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Order</button>
    </form>
</div>
@endsection
