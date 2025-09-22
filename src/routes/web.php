<?php

use App\Http\Controllers\SocialController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function () {
    return view('login');
})->name('login');

Route::get('register', function () {
    return view('register');
})->name('register');

Route::post('login', LoginController::class)->name('login.attempt');
Route::post('register', [RegisterController::class, 'store'])->name('register.store');
Route::get('login/{provider}', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialController::class, 'callback']);