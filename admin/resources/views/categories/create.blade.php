<!-- Bootstrap CSS (if not already included in layout.app) -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<!-- Optional: FontAwesome for icons (if needed) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

<!-- Custom CSS for Animation and Styling -->
<style>
    /* Background color for the body and form */
    body {
        background-color: #6192d13e; /* Dark background for the whole page */
    }

    .form-container {
        background-color: #343a40; /* Dark background for the form */
        color: #ffffff; /* White text for contrast */
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    /* Form labels and inputs */
    .form-label {
        color: #ffffff; /* Ensure labels remain white */
    }

    .form-control {
        background-color: #495057; /* Dark background for input fields */
        color: #ffffff; /* White text inside inputs */
        border: none;
    }

    /* Fade-in animation for the form */
    .fade-in {
        opacity: 0;
        animation: fadeIn 1s ease-in forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Button styling */
    .btn-primary {
        background-color: #17a2b8; /* Matching color for the buttons */
        border-color: #17a2b8;
    }

    .btn-primary:hover {
        background-color: #138496;
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .btn-secondary:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    /* Mobile responsiveness */
    @media (max-width: 768px) {
        form {
            margin-top: 20px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
        }

        .form-label {
            font-size: 16px;
        }
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 fade-in">
            <div class="form-container">
                <h1 class="text-center">Create Category</h1>

                <!-- Form Start -->
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Name Field -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>

                    <!-- Description Field -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"></textarea>
                    </div>

                    <!-- Image Upload Field -->
                    <div class="mb-3">
                        <label for="image" class="form-label">Image</label>
                        <input type="file" class="form-control" id="image" name="image" required>
                    </div>

                    <!-- Submit and Back Buttons -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Create</button>
                        <a href="{{ route('categories.index') }}" class="btn btn-secondary btn-lg">Back</a>
                    </div>
                </form>
                <!-- Form End -->
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies (if not already included in layout.app) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
