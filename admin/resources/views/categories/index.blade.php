<!-- Bootstrap CSS (if not already included in layout.app) -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Optional: FontAwesome for icons (if needed) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Custom CSS for Background and Animations -->
<style>
    /* Dark background for body */
    body {
        background-color: #2c2f33; /* Dark gray background */
        color: #ffffff; /* White text for contrast */
    }

    h1 {
        text-align: center;
        margin-bottom: 30px;
        color: #17a2b8; /* Light blue color for the heading */
        animation: fadeInDown 1s ease-in-out; /* Animation for heading */
    }

    /* Table styling */
    .table {
        background-color: #343a40; /* Dark background for the table */
        color: #ffffff; /* White text for contrast */
        border-radius: 10px; /* Rounded corners */
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3); /* Shadow for the table */
    }

    .table th {
        background-color: #17a2b8; /* Blue header */
        color: #ffffff;
        padding: 15px;
        text-align: center;
    }

    .table td {
        text-align: center;
        padding: 15px;
    }

    /* Row hover effect */
    .table tbody tr:hover {
        background-color: #495057; /* Darker hover effect for rows */
        transition: background-color 0.3s ease;
    }

    /* Button animations */
    .btn-primary,
    .btn-info,
    .btn-warning,
    .btn-danger {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .btn-primary:hover,
    .btn-info:hover,
    .btn-warning:hover,
    .btn-danger:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    /* Table cell animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    tbody tr {
        animation: fadeInUp 0.5s ease;
    }

    /* Fade-in animation for the heading */
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<div class="container py-5">
    <h1>Categories</h1>

    <div class="text-center mb-4">
        <a href="{{ route('categories.create') }}" class="btn btn-primary btn-lg">Create New Category</a>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        @if ($category->image)
                            <img src="{{ Storage::url($category->image) }}" alt="Image" style="width: 100px;">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('categories.show', $category) }}" class="btn btn-info">View</a>
                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Bootstrap JS and dependencies (if not already included in layout.app) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
