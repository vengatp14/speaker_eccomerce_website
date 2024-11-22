@extends('layout.app')

@section('title', 'Products List')

@section('content')
    <h1>Products List</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('products.create') }}" class="btn btn-primary mb-3">Create New Product</a>

    @if ($products->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th>Description</th>
                    <th>Image</th>
                    <th>Annual Prices</th>
                    <th>Lifetime Prices</th>
                    
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->productname }}</td>
                        <td>{{ Str::limit($product->description, 50) }}</td>
                        <td>
                            <img src="{{ asset('images/' . $product->image) }}" alt="Product Image" width="80">
                        </td>
                        <td>
                            Single Site: ${{ $product->price_tiers['annual']['single_site'] }} <br>
                            Up to 5 Sites: ${{ $product->price_tiers['annual']['up_to_5_sites'] }} <br>
                            Up to 20 Sites: ${{ $product->price_tiers['annual']['up_to_20_sites'] }}
                        </td>
                        <td>
                            Single Site: ${{ $product->price_tiers['lifetime']['single_site'] }} <br>
                            Up to 5 Sites: ${{ $product->price_tiers['lifetime']['up_to_5_sites'] }} <br>
                            Up to 20 Sites: ${{ $product->price_tiers['lifetime']['up_to_20_sites'] }}
                        </td>
                        <td>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination links -->
        {{ $products->links() }}
    @else
        <p>No products found.</p>
    @endif
@endsection
