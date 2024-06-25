<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Livewire\HomePage::class);
Route::get('/categories', \App\Livewire\CategoryPage::class);
Route::get('/products', \App\Livewire\ProductsPage::class);
Route::get('/cart', \App\Livewire\CartPage::class);
Route::get('/products/{product}', \App\Livewire\ProductDetailPage::class);

Route::get('/checkout', \App\Livewire\CheckoutPage::class);
Route::get('/my-orders', \App\Livewire\MyOrdersPage::class);
Route::get('/my-orders/{order}', \App\Livewire\MyOrderDetailPage::class);

Route::get('/login', \App\Livewire\Auth\Login::class);
Route::post('/register', \App\Livewire\Auth\Register::class);
Route::post('/forgot-password', \App\Livewire\Auth\ForgotPasswordPage::class);
Route::post('/reset-password', \App\Livewire\Auth\ResetPasswordPage::class);

Route::get('/success', \App\Livewire\SuccessPage::class);
Route::get('/cancel', \App\Livewire\CancelPage::class);

