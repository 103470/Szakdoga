<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\Category;
use App\Models\Brands\Type;
use App\Models\Brands\Vintage;
use App\Models\Brands\BrandModel;

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
})->name('marka');

Route::get('/tipus/{brandSlug}/{typeSlug}', function($brandSlug, $typeSlug) {
    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = $brand->types()->where('slug', $typeSlug)->firstOrFail(); 

    $vintages = Vintage::where('type_id', $type->id)->get();

    return view('brands.vintage', compact('brand', 'type', 'vintages'));
})->name('tipus');


Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}', function($brandSlug, $typeSlug, $vintageSlug) {
    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = $brand->types()->where('slug', $typeSlug)->firstOrFail(); 

    $vintage = Vintage::where('type_id', $type->id)
                      ->where('slug', $vintageSlug)
                      ->firstOrFail();

    $models = BrandModel::forVintage($vintage)->get();
    $groupedModels = BrandModel::groupedByFuel($models);

    return view('brands.model', compact('brand', 'type', 'vintage', 'models', 'groupedModels'));
})->name('model');

Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}', function($brandSlug, $typeSlug, $vintageSlug, $modelSlug) {
    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = $brand->types()->where('slug', $typeSlug)->firstOrFail(); 

    $vintage = Vintage::where('type_id', $type->id)
                      ->where('slug', $vintageSlug)
                      ->firstOrFail();

    $model = BrandModel::where('slug', $modelSlug)
                       ->where('type_id', $type->id)
                       ->firstOrFail();

    $categories = Category::where('requires_model', true)
                          ->orderBy('name')
                          ->get();

    return view('brands.categories', compact('brand', 'type', 'vintage', 'model', 'categories'));
})->name('categories');


Route::get('/termekcsoport/{category:slug}', function (Category $category) {
    return "Ez a(z) {$category->name} termékcsoport oldala.";
})->name('termekcsoport');
