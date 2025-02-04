<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stripe/validatebankaccount', [StripeController::class, 'validateBankAccount']);

Route::get('/stripe/card/payment', [StripeController::class, 'showCheckout'])->name('stripe.card.payment');
Route::post('/stripe/card/payment/process', [StripeController::class, 'processPayment'])->name('stripe.card.payment.process');


Route::get('/success', function () {
    dd('success');
})->name('success');


