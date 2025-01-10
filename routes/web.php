<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StripeController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/stripe/validatebankaccount', [StripeController::class, 'validateBankAccount']);
