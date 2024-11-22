  <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: FontAwesome for icons (if needed) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Your custom CSS file -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">


    <!-- Your content will go here -->

    <!-- Bootstrap JS and dependencies (jQuery and Popper.js) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



<div class="container">
    <h2 class="mb-4">Product Details</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $product->productname }}</h5>
            <p class="card-text">{{ $product->description }}</p>
            <p class="card-text"><strong>Price Plan:</strong> {{ ucfirst($product->price) }}</p>
            <img src="{{ asset('images/'.$product->image) }}" alt="{{ $product->productname }}" class="img-fluid mb-3">
            <div>
                <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Edit</a>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to List</a>
            </div>
        </div>
    </div>
</div>



