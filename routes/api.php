<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/payment/webhook/monnify', [CheckoutController::class, 'monnifyWebhook'])
    ->name('payment.webhook.monnify');