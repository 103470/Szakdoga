<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Model\Brand;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tipus/{brand:slug}', function (Brand $brand) {
    return "Ez a(z) {$brand->name} típusválasztó oldala.";  
})->name('tipus');

Route::get('/tipus/{rare_brand:slug}', function(RareBrand $rareBrand){
    return "Ez a(z) {$rareBrand->name} tipusválasztó oldala.";
})->name('ritkatipus');
