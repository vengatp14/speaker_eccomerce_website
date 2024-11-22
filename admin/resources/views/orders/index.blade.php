<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
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

        {{-- Header Section --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h4">Orders</h1>
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                Create New Order
            </a>
        </div>

        {{-- Filters Section --}}
        <div class="card mb-4">
            <div class="card-body">
                <form action="{{ route('orders.index') }}" method="GET" class="row g-3">
                    {{-- Status Filter --}}
                    <div class="col-md-3">
                        <label class="form-label">Order Status</label>
                        <select name="status" class="form-select">
                            <option value="">All Statuses</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    {{-- Payment Status Filter --}}
                    <div class="col-md-3">
                        <label class="form-label">Payment Status</label>
                        <select name="payment_status" class="form-select">
                            <option value="">All Payments</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ request('payment_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>

                    {{-- Date Range Filter --}}
                    <div class="col-md-3">
                        <label class="form-label">Date Range</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control mb-2">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">
                    </div>

                    {{-- Filter Actions --}}
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-secondary me-2">Apply Filters</button>
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>#{{ $order->id ?? 'N/A' }}</td>
                            <td>
                                <strong>{{ $order->customer?->name ?? 'N/A' }}</strong><br>
                                <small>{{ $order->customer?->email ?? 'N/A' }}</small>
                            </td>
                            <td>${{ $order->total_price ?? '0.00' }}</td>
                            <td>
                                <span class="badge
                                    @if($order->order_status === 'delivered') bg-success
                                    @elseif($order->order_status === 'processing') bg-primary
                                    @elseif($order->order_status === 'shipped') bg-info
                                    @else bg-secondary @endif">
                                    {{ ucfirst($order->order_status ?? 'unknown') }}
                                </span>
                            </td>
                            <td>
                                <span class="badge
                                    @if($order->payment_status === 'completed') bg-success
                                    @elseif($order->payment_status === 'pending') bg-warning
                                    @else bg-danger @endif">
                                    {{ ucfirst($order->payment_status ?? 'unknown') }}
                                </span>
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">View</a>
                                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-warning">Edit</a>
                                    <form action="{{ route('orders.destroy', $order) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this order?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No orders found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle (with Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
