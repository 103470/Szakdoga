<?php

use App\Http\Controllers\SocialController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Middleware\AdminOnlyMiddleware;
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



// SOCIAL LOGIN
Route::get('login/{provider}', [SocialController::class, 'redirect'])->name('social.redirect');
Route::get('login/{provider}/callback', [SocialController::class, 'callback']);


// ADMIN DASHBOARD
Route::middleware(['auth', AdminOnlyMiddleware::class])->prefix('admin')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::resource('users', AdminUserController::class, ['as' => 'admin']);

});



// USER DASHBOARD
Route::middleware(['auth'])->prefix('user')->group(function () {
    Route::get('/', [App\Http\Controllers\UserDashboardController::class, 'index'])
         ->name('user.dashboard');
});




Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/login')->with('success', 'Sikeresen kijelentkeztél!');
})->name('logout');


