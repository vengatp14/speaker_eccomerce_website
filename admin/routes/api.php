<?php
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\API\ProductSController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\RazorpayController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\API\CouponController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Route::prefix('orders')->group(function () {
//     Route::post('/', [OrderController::class, 'store']);
//     Route::get('/{id}', [OrderController::class, 'show']);
//     Route::put('/{id}', [OrderController::class, 'update']);
// });
Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/signin', [AuthController::class, 'signin']);

Route::post('/create-order', [RazorpayController::class, 'createOrder']);

Route::apiResource('products', ProductSController::class);


Route::middleware('auth:api')->group(function () {
    Route::apiResource('order-items', OrderItemController::class);
});


Route::resource('categories', CategoryController::class);
Route::get('categories/{categoryId}/products', [ProductSController::class, 'getProductsByCategory']);
Route::get('category', [CategoryController::class, 'category']);
Route::post('/signin', [AuthController::class, 'signin']);

Route::post('/create-order', [RazorpayController::class, 'createOrder']);
Route::post('/rzp_capture/{paymentId}/{amount}', [RazorpayController::class, 'capturePayment']);
Route::post('/payment', [RazorpayController::class, 'payment']);

            Route::apiResource('coupons', CouponController::class);



// Order routes
Route::get('orders', [OrderController::class, 'index']);
Route::post('orders', [OrderController::class, 'store']);
Route::get('orders/{id}', [OrderController::class, 'show']);
Route::put('orders/{id}', [OrderController::class, 'update']);
Route::delete('orders/{id}', [OrderController::class, 'destroy']);

// Customer orders route
Route::get('orders/customer/{customerId}', [OrderController::class, 'getCustomerOrders']);


Route::post('/orders', [OrderController::class, 'store']);


Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/signin', [AuthController::class, 'signin']);
