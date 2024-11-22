<!-- Bootstrap CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Optional: FontAwesome for icons (if needed) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<style>
   /* Page Background */
body {
    background: linear-gradient(135deg, #1b1b1b 0%, #343a40 100%);
    color: #e0e0e0;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Card Styles */
.card {
    background-color: #2c2f33;
    color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
    animation: fadeInUp 1s ease-in-out;
    transition: transform 0.3s, box-shadow 0.3s;
}

.card-header {
    background-color: #4a5568;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.4);
}

/* Fade-in Animation */
@keyframes fadeInUp {
    0% {
        opacity: 0;
        transform: translateY(30px);
    }
    100% {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Button Styles */
.btn {
    background-color: #4caf50;
    transition: background-color 0.3s, transform 0.3s;
    color: #ffffff;
}

.btn:hover {
    background-color: #43a047;
    transform: scale(1.05);
}

/* Heading Styles */
h2, h3 {
    font-family: 'Arial', sans-serif;
    color: #ffffff;
}

/* Form Control Styling */
.form-control {
    background-color: #3a3f44;
    color: #ffffff;
    border: 1px solid #4a5568;
    border-radius: 5px;
}

/* Responsive Tweaks */
@media (max-width: 768px) {
    .card {
        margin-bottom: 20px;
    }

    h2, h3 {
        font-size: 1.5rem;
    }

    .btn-lg {
        font-size: 1.2rem;
    }

    .container {
        padding: 15px;
    }
}

</style>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <!-- Card Container -->
            <div class="card shadow-sm">
                <div class="card-header text-center">
                    <h2>Edit Product</h2>
                </div>

                <div class="card-body">
                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form -->
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Product Name -->
                        <div class="form-group">
                            <label for="productname" class="font-weight-bold">Product Name</label>
                            <input type="text" name="productname" id="productname" class="form-control" value="{{ old('productname', $product->productname) }}" required>
                        </div>

                        <!-- Description -->
                        <div class="form-group">
                            <label for="description" class="font-weight-bold">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4" required>{{ old('description', $product->description) }}</textarea>
                        </div>

                        <!-- Image -->
                        <div class="form-group">
                            <label for="image" class="font-weight-bold">Image</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                            <small class="text-muted">Leave blank if you don't want to change the image.</small>
                        </div>

                        <!-- Source File -->
                        <div class="form-group">
                            <label for="source_file" class="font-weight-bold">Source File</label>
                            <input type="file" name="source_file" id="source_file" class="form-control-file">
                            <small class="text-muted">Upload any relevant source files here.</small>
                        </div>

                        <!-- Category Selection -->
                        <div class="form-group">
                            <label for="category_id" class="font-weight-bold">Category</label>
                            <select name="category_id" id="category_id" class="form-control" required>
                                <option value="" disabled>Select a Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Annual Prices -->
                        <h3 class="mt-4 font-weight-bold text-center">Annual Prices</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="annual_single_site">Single Site</label>
                                    <input type="number" name="annual_prices[single_site]" id="annual_single_site" class="form-control" value="{{ old('annual_prices.single_site', $product->price_tiers['annual']['single_site']) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="annual_up_to_5_sites">Up to 5 Sites</label>
                                    <input type="number" name="annual_prices[up_to_5_sites]" id="annual_up_to_5_sites" class="form-control" value="{{ old('annual_prices.up_to_5_sites', $product->price_tiers['annual']['up_to_5_sites']) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="annual_up_to_20_sites">Up to 20 Sites</label>
                                    <input type="number" name="annual_prices[up_to_20_sites]" id="annual_up_to_20_sites" class="form-control" value="{{ old('annual_prices.up_to_20_sites', $product->price_tiers['annual']['up_to_20_sites']) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Lifetime Prices -->
                        <h3 class="mt-4 font-weight-bold text-center">Lifetime Prices</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lifetime_single_site">Single Site</label>
                                    <input type="number" name="lifetime_prices[single_site]" id="lifetime_single_site" class="form-control" value="{{ old('lifetime_prices.single_site', $product->price_tiers['lifetime']['single_site']) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lifetime_up_to_5_sites">Up to 5 Sites</label>
                                    <input type="number" name="lifetime_prices[up_to_5_sites]" id="lifetime_up_to_5_sites" class="form-control" value="{{ old('lifetime_prices.up_to_5_sites', $product->price_tiers['lifetime']['up_to_5_sites']) }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="lifetime_up_to_20_sites">Up to 20 Sites</label>
                                    <input type="number" name="lifetime_prices[up_to_20_sites]" id="lifetime_up_to_20_sites" class="form-control" value="{{ old('lifetime_prices.up_to_20_sites', $product->price_tiers['lifetime']['up_to_20_sites']) }}" required>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center">
                            <button style="margin:30px 0" type="submit" class="btn btn-primary btn-lg">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
