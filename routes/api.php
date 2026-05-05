<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;

Route::post('/midtrans/create-transaction', [PaymentController::class, 'createTransaction']);
Route::post('/midtrans/callback', [PaymentController::class, 'callback']);
Route::get('/payment/success', [PaymentController::class, 'success']);