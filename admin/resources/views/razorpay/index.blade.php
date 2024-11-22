<!-- resources/views/razorpay/index.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Razorpay Payment</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Payment Form</h3>

                        @if(Session::has('success'))
                            <div class="alert alert-success text-center">
                                {{ Session::get('success') }}
                            </div>
                        @endif

                        @if(Session::has('error'))
                            <div class="alert alert-danger text-center">
                                {{ Session::get('error') }}
                            </div>
                        @endif

                        <form action="{{ route('payment') }}" method="POST">
                            @csrf
                            <script src="https://checkout.razorpay.com/v1/checkout.js"
                                    data-key="{{ env('RAZOR_KEY') }}"
                                    data-amount="100000" {{-- Amount in paise (e.g., 100000 paise = ₹1000) --}}
                                    data-buttontext="Pay Now"
                                    data-name="Your Company Name"
                                    data-description="Payment for Order #123"
                                    data-image="https://your-company-logo.png"
                                    data-prefill.name="Customer Name"
                                    data-prefill.email="customer@example.com"
                                    data-theme.color="#F37254">
                            </script>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>
