<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CheckoutController;

Route::post('/stripe/webhook', [CheckoutController::class, 'handleWebhook']);
