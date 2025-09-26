<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Models\Brand;
use App\Models\RareBrand;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tipus/{slug}', function($slug) {
    $brand = Brand::where('slug', $slug)->first();
    if ($brand) {
        return view('brands.type', compact('brand'));
    }

    $rareBrand = RareBrand::where('slug', $slug)->first();
    if ($rareBrand) {
        return view('rarebrands.type', compact('rareBrand'));
    }

    abort(404);
})->name('tipus');


Route::get('/termekcsoport/{category:slug}', function (Category $category) {
    return "Ez a(z) {$category->name} termékcsoport oldala.";
})->name('termekcsoport');
