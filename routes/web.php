<?php

use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');

// Contact Route
Route::get('/contact', [PageController::class, 'index'])->name('contact');

// Cart Route
Route::get('/cart', [PageController::class, 'index'])->name('cart');

// Static Pages (Privacy, Terms, Sitemap)
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/sitemap', [PageController::class, 'sitemap'])->name('sitemap');