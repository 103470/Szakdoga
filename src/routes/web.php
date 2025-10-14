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
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\Product;

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
})->name('kategoria');

Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}/{categorySlug}', function(
    $brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug
) {
    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = $brand->types()->where('slug', $typeSlug)->firstOrFail();
    $vintage = Vintage::where('type_id', $type->id)->where('slug', $vintageSlug)->firstOrFail();
    $model = BrandModel::where('slug', $modelSlug)->where('type_id', $type->id)->firstOrFail();
    $category = Category::where('slug', $categorySlug)->firstOrFail();

    $subcategories = SubCategory::where('category_id', $category->kategory_id)
                                ->where(function ($query) use ($model) {
                                    $query->where('fuel_type_id', $model->fuel_type_id)
                                        ->orWhereNull('fuel_type_id');
                                })
                                ->get();

    if ($subcategories->isNotEmpty()) {
        return view('brands.subcategories', compact('brand', 'type', 'vintage', 'model', 'category', 'subcategories'));
    }
    $subcategoryIds = SubCategory::where('category_id', $category->kategory_id)
        ->pluck('subcategory_id');

    $products = Product::whereIn('subcategory_id', $subcategoryIds)->get();

    return view('brands.products', compact('brand', 'type', 'vintage', 'model', 'category', 'products'));
})->name('alkategoria');

Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}/{categorySlug}/{subcategorySlug}', function(
    $brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug
) {
    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = $brand->types()->where('slug', $typeSlug)->firstOrFail();
    $vintage = Vintage::where('type_id', $type->id)->where('slug', $vintageSlug)->firstOrFail();
    $model = BrandModel::where('slug', $modelSlug)->where('type_id', $type->id)->firstOrFail();
    $category = Category::where('slug', $categorySlug)->firstOrFail();

    $subcategory = SubCategory::where('slug', $subcategorySlug)
        ->where('category_id', $category->kategory_id)
        ->firstOrFail();

    $productCategories = $subcategory->productCategories()->get();

    if ($productCategories->isNotEmpty()) {
        return view('brands.productcategories', compact('brand', 'type', 'vintage', 'model', 'category', 'subcategory', 'productCategories'));
    }

    $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get(); 

    return view('brands.products', compact('brand', 'type', 'vintage', 'model', 'category', 'subcategory', 'products'));
})->name('termekkategoria');

Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}/{categorySlug}/{subcategorySlug}/{productCategorySlug}', function(
    $brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug, $productCategorySlug
) {
    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = $brand->types()->where('slug', $typeSlug)->firstOrFail();
    $vintage = Vintage::where('type_id', $type->id)->where('slug', $vintageSlug)->firstOrFail();
    $model = BrandModel::where('slug', $modelSlug)->where('type_id', $type->id)->firstOrFail();
    $category = Category::where('slug', $categorySlug)->firstOrFail();

    $subcategory = SubCategory::where('slug', $subcategorySlug)
        ->where('category_id', $category->kategory_id)
        ->firstOrFail();

    $uniqueId = $model->unique_code;

    if ($productCategorySlug === 'osszes_termek') {
        $products = Product::whereHas('oemNumbers.partVehicles', function ($query) use ($uniqueId) {
                $query->where('unique_code', $uniqueId);
            })
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->get();
        $productCategory = null;
    } else {
        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->first();

        $products = $productCategory
            ? Product::whereHas('oemNumbers.partVehicles', function ($query) use ($uniqueId) {
                    $query->where('unique_code', $uniqueId);
                })
                ->where('product_category_id', $productCategory->product_category_id)
                ->get()
            : collect();
    }

    return view('brands.products', compact(
        'brand', 'type', 'vintage', 'model', 'category', 'subcategory', 'productCategory', 'products'
    ));
})->name('termekek');

Route::get('/termek/{product:slug}', function(Product $product) {
    $brandModels = $product->brandModels();
    return view('brands.productdetails', compact('product', 'brandModels'));
    $product = Product::with(['reviews.user'])->findOrFail($id);
})->name('termek.leiras');


Route::get('/termekcsoport/{category:slug}', function (Category $category) {
    $subcategories = $category->subcategories;
    if ($subcategories->isNotEmpty()) {
        return view('categories.subcategories', compact('category', 'subcategories'));
    }

    if ($category->requires_model) {
        $models = BrandModel::all(); 
        return view('categories.model', compact('category', 'models'));
    }

    $subcategoryIds = $category->subcategories()->pluck('subcategory_id');
    $products = Product::whereIn('subcategory_id', $subcategoryIds)->get();

    return view('brands.products', compact('category', 'products'));
})->name('termekcsoport');

Route::get('/termekcsoport/{category:slug}/{subcategory:slug}', function(Category $category, SubCategory $subcategory) {
    $productCategories = $subcategory->productCategories()->get();

    if ($productCategories->isNotEmpty()) {
        return view('brands.productcategories', compact('category', 'subcategory', 'productCategories'));
    }

    $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get();

    return view('brands.products', compact('category', 'subcategory', 'products'));
})->name('termekcsoport_subcategory');






