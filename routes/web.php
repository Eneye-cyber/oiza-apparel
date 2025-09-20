<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;
// routes/web.php
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Models\Products\Product;
use Illuminate\Http\Request;

Route::get('/products/{slug}/quick-view', function ($slug, Request $request) {
    $product = Product::where('slug', $slug)
        ->with(['variants.attributes'])
        ->firstOrFail();

    return response()->json($product);
});


Route::prefix('cart')->group(function () {
  Route::get('/', [CartController::class, 'index'])->name('cart.index');
  Route::post('add/{product}', [CartController::class, 'add'])->name('cart.add');
  Route::post('update/{item}', [CartController::class, 'update'])->name('cart.update');
  Route::delete('remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
});




Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/shop', [PageController::class, 'shop'])->name('shop');

Route::get('/shop', [PageController::class, 'shop'])->name('shop');
Route::get('shop/{slug}', [PageController::class, 'show'])
    ->name('shop.category')
    ->where('slug', '[a-zA-Z0-9\-/]+');

Route::get('product/{product:slug}', [PageController::class, 'product'])->name('shop.product');
    
// Contact Route
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Contact Route
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

// Cart Route
// Route::get('/cart', [PageController::class, 'index'])->name('cart');

// Static Pages (Privacy, Terms, Sitemap)
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/sitemap', [PageController::class, 'sitemap'])->name('sitemap');

Route::prefix('v1')->group(function () {
  Route::get('/countries/{code?}', [ApiController::class, 'countries'])->name('country.index');
  Route::get('/shipping/{type}/{id}', [ApiController::class, 'shipping'])->name('shipping.index');
});