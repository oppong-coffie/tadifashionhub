<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\customerController;
use App\Http\Controllers\DesignerController;


Route::get('/welcome', function () {
    return view('auth.login');
});

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login'])->name('login.post');

// Route::get('/customer/dashboard', [AuthController::class, 'customer_dashboard'])->name('customer.dashboard');
// Route::get('/designer/dashboa', [DesignerController::class, 'designer_dashboard'])->name('designer.dashboard');
Route::get('/designer/dashboard', [DesignerController::class, 'designerDashboard'])->name('designer.dashboard');
Route::get('/customer/dashboard', [CustomerController::class, 'customerDashboard'])->name('customer.dashboard');

Route::get('/products/{user_id}', [customerController::class, 'getallproducts'])->name('getproducts');

// Add a new Product
Route::post('/sendproduct', [DesignerController::class, 'sendproduct'])->name('sendproduct');

// get all products for the Designer
Route::get('/products/{user_id}', [DesignerController::class, 'getProducts'])->name('getproducts');

// Served products
Route::post('/served-products/{productid}', [DesignerController::class, 'servedProducts'])->name('servedproduct');

// Reject products
Route::post('/reject-products/{productid}', [DesignerController::class, 'rejectProducts'])->name('rejectproduct');

//Delete a product
Route::delete('/deleteproduct/{id}', [DesignerController::class, 'deleteProducts'])->name('deleteproduct');

// Update a Product
Route::put('/updateproduct', [DesignerController::class, 'updateProduct'])->name('updateproduct');

// Add to cart
Route::post('/addtocart', [customerController::class, 'addToCart'])->name('addtocart');

// detail
Route::get('/detail/{designer_id}/{product_image}/{product_price}/{product_name}', [customerController::class, 'detailToCart']
)->name('detail');

// Route::get('/cart-data', [customerController::class, 'getCartData'])->name('getCartData');
Route::delete('/remove-from-cart/{id}', [customerController::class, 'removeFromCart'])->name('removeFromCart');
Route::post('/checkout', [customerController::class, 'initiatePayment'])->name('checkout');
Route::get('/payment/callback', [customerController::class, 'paymentCallback'])->name('payment.callback');

Route::get('/forgot-password', [AuthController::class, 'showRequestForm'])->name('password.request');
Route::post('/request-otp', [AuthController::class, 'requestOTP'])->name('password.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOTP'])->name('verify-otp');
Route::post('/reset', [AuthController::class, 'reset'])->name('reset');
Route::get('/reset-form/{user_id}', [AuthController::class, 'showResetForm'])->name('reset.form');
Route::get('/take-otp/{user_id}', [AuthController::class, 'showOtpForm'])->name('take.otp');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');