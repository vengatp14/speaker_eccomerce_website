<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;

class RazorpayController extends Controller
{
    // Return Razorpay View
    public function razorpay()
    {
        return view('razorpay.index');
    }

    // Create a new order on Razorpay
    public function createOrder(Request $request)
    {
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        $orderData = [
            'amount' => $request->amount * 100, // Convert amount to paise
            'currency' => 'USD', // You can change to 'INR' or relevant currency
            'receipt' => uniqid(), // Unique receipt ID
            'payment_capture' => 1, // Auto capture payment
        ];

        try {
            $order = $api->order->create($orderData);
            return response()->json(['orderId' => $order['id']], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Order creation failed: ' . $e->getMessage()], 500);
        }
    }

    // Handle Payment Process
    public function payment(Request $request)
    {
        $input = $request->all();
        $api = new Api(env('RAZORPAY_KEY'), env('RAZORPAY_SECRET'));

        if (!empty($input['razorpay_payment_id'])) {
            try {
                // Fetch and capture the payment
                $payment = $api->payment->fetch($input['razorpay_payment_id']);
                $api->payment->fetch($input['razorpay_payment_id'])->capture(['amount' => $payment['amount']]);

                // On successful capture, set session success message
                Session::put('success', 'Payment successful, your order will be dispatched in the next 48 hours.');
                return redirect()->back();
            } catch (\Exception $e) {
                // Capture failed, set session error message
                Session::put('error', 'Payment capture failed: ' . $e->getMessage());
                return redirect()->back();
            }
        } else {
            // Payment ID missing or invalid
            Session::put('error', 'Payment failed or invalid request.');
            return redirect()->back();
        }
    }

    // Capture Razorpay Payment via API (useful for custom implementations)
    public function capturePayment($paymentId, $amount)
    {
        try {
            $key = env('RAZORPAY_KEY');        // Razorpay key from .env
            $secret = env('RAZORPAY_SECRET');  // Razorpay secret from .env

            // Capture request to Razorpay API
            $response = Http::withBasicAuth($key, $secret)
                ->post("https://api.razorpay.com/v1/payments/{$paymentId}/capture", [
                    'amount' => $amount * 100,  // Convert amount to paise
                    'currency' => 'INR',        // Adjust currency if needed
                ]);

            if ($response->successful()) {
                return response()->json(['status' => 'success', 'message' => 'Payment captured successfully'], 200);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Payment capture failed'], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
