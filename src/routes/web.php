<?php

use App\Http\Controllers\SocialController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Middleware\AdminOnlyMiddleware;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AdminMarkaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

// LOGIN
Route::get('login', function () {
    return view('login');
})->name('login');
Route::post('login', LoginController::class)->name('login.attempt');

// REGISTER
Route::get('register', function () {
    return view('register');
})->name('register');
Route::post('register', [RegisterController::class, 'store'])->name('register');

Route::get('/test-middleware', function () {
    return class_exists(\App\Http\Middleware\AdminOnlyMiddleware::class) ? 'Megvan!' : 'Nincs meg!';
});




Route::get('login/{provider}', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialController::class, 'callback']);


Route::middleware(['auth', \App\Http\Middleware\AdminOnlyMiddleware::class])
    ->prefix('admin')
    ->as('admin.')
    ->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class);
        Route::resource('markak', AdminMarkaController::class);
    });



Route::middleware(['auth'])->prefix('user')->name('user.')->group(function () {
    Route::get('/', [UserDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [UserDashboardController::class, 'editProfile'])->name('profile.edit');
    Route::post('/profile', [UserDashboardController::class, 'updateProfile'])->name('profile.update');

});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')->with('success', 'Sikeresen kijelentkeztél!');
})->name('logout');

Route::get('/forgot-password', [ForgotPasswordController::class, 'create'])
    ->name('password.request');

//Email küldése a jelszó-visszaállítás linkkel
Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

//Jelszó visszaállítás űrlap a kapott token alapján
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

//Új jelszó mentése
Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::view('/aszf', 'aszf')->name('aszf');
Route::view('/adatvedelem', 'adatvedelem')->name('adatvedelem');

Route::get('/test-admin', function () {
    return 'Admin middleware működik!';
})->middleware('admin');
