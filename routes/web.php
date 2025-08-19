<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');

Route::get('/shop/{category}/{subcategory?}/{product?}', [PageController::class, 'show'])->name('shop.show');

// Contact Route
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Cart Route
Route::get('/cart', [PageController::class, 'index'])->name('cart');

// Static Pages (Privacy, Terms, Sitemap)
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/sitemap', [PageController::class, 'sitemap'])->name('sitemap');