<!-- Bootstrap CSS (if not already included in layout.app) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: FontAwesome for icons (if needed) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <h1>{{ $category->name }}</h1>
    <p><strong>Description:</strong> {{ $category->description }}</p>
    @if ($category->image)
        <img src="{{ Storage::url($category->image) }}" alt="Image" style="width: 200px;">
    @endif
    <div class="mt-3">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
    </div>

 <!-- Bootstrap JS and dependencies (if not already included in layout.app) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
