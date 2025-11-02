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
        $types = \App\Models\RareBrands\Type::where('rare_brand_id', $rareBrand->id)->get();
        return view('rarebrands.type', compact('rareBrand', 'types'));
    }

    abort(404);
})->name('marka');

Route::get('/tipus/{brandSlug}/{typeSlug}', function($brandSlug, $typeSlug) {
    $brand = Brand::where('slug', $brandSlug)->first();

    if ($brand) {
        $type = $brand->types()->where('slug', $typeSlug)->firstOrFail();
        $vintages = Vintage::where('type_id', $type->id)->get();

        return view('brands.vintage', compact('brand', 'type', 'vintages'));
    }

    $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
    $type = \App\Models\RareBrands\Type::where('rare_brand_id', $rareBrand->id)
        ->where('slug', $typeSlug)
        ->firstOrFail();

    $hasVintage = \App\Models\RareBrands\Vintage::where('type_id', $type->id)->exists();

    if ($hasVintage) {
        $vintages = \App\Models\RareBrands\Vintage::where('type_id', $type->id)->get();
        return view('rarebrands.vintage', compact('rareBrand', 'type', 'vintages'));
    }

    return view('rarebrands.model', compact('rareBrand', 'type'));

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
            $query->whereHas('fuelType', function ($q) use ($model) {
                $q->where('id', $model->fuel_type_id)
                  ->orWhere('is_universal', true);
            });
        })
        ->get();

    return view('brands.subcategories', compact('brand', 'type', 'vintage', 'model', 'category', 'subcategories'));
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
    return view('categories.subcategories', compact('category', 'subcategories'));
})->name('termekcsoport');

Route::get('/termekcsoport/{category:slug}/{subcategory:slug}/{productCategorySlug?}/{brandSlug?}', function (Category $category, SubCategory $subcategory, $productCategorySlug = null, $brandSlug = null) {
    $productCategory = null;

    if ($productCategorySlug) {
        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->firstOrFail();
    }

    if ($brandSlug) {
        $brand = Brand::where('slug', $brandSlug)->firstOrFail();
        $types = $brand->types;
        return view('categories.type', compact('category', 'subcategory', 'brand', 'types', 'productCategory'));
    }

    if ($productCategory) {
        if ($category->requires_model) {
            $brands = Brand::all();
            $rareBrands = RareBrand::all();
            return view('categories.brands', compact('category', 'subcategory', 'brands', 'rareBrands', 'productCategory'));
        } else {
            $products = Product::where('product_category_id', $productCategory->id)->get();
            return view('categories.products', compact('category', 'subcategory', 'productCategory', 'products'));
        }
    }

    $productCategories = $subcategory->productCategories;

    if ($productCategories->isNotEmpty()) {
        return view('categories.productCategories', compact('category', 'subcategory', 'productCategories'));
    }

    if ($category->requires_model) {
        $brands = Brand::all();
        $rareBrands = RareBrand::all();
        return view('categories.brands', compact('category', 'subcategory', 'brands', 'rareBrands'));
    }

    $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get();
    return view('categories.products', compact('category', 'subcategory', 'products'));
})->name('termekcsoport_dynamic');


Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug?}/{brandSlug}/{typeSlug}', 
function ($categorySlug, $subcategorySlug, $productCategorySlug = null, $brandSlug, $typeSlug) {

    $category = Category::where('slug', $categorySlug)->firstOrFail();

    $subcategory = SubCategory::where('slug', $subcategorySlug)
        ->where('category_id', $category->kategory_id)
        ->firstOrFail();

    $productCategory = null;
    if ($productCategorySlug) {
        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->firstOrFail();
    }

    $brand = Brand::where('slug', $brandSlug)->firstOrFail();

    $type = Type::where('slug', $typeSlug)
        ->where('brand_id', $brand->id)
        ->firstOrFail();

    $vintages = Vintage::where('type_id', $type->id)->get();

    return view('categories.vintage', compact(
        'category', 'subcategory', 'productCategory', 'brand', 'type', 'vintages'
    ));
})->name('termekcsoport_vintage');


Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug?}/{brandSlug}/{typeSlug}/{vintageSlug}', 
function ($categorySlug, $subcategorySlug, $productCategorySlug = null, $brandSlug, $typeSlug, $vintageSlug) {

    $category = Category::where('slug', $categorySlug)->firstOrFail();

    $subcategory = SubCategory::with('fuelType')
        ->where('slug', $subcategorySlug)
        ->where('category_id', $category->kategory_id)
        ->firstOrFail();

    $productCategory = null;
    if ($productCategorySlug) {
        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->firstOrFail();
    }

    $brand = Brand::where('slug', $brandSlug)->firstOrFail();
    $type = Type::where('slug', $typeSlug)
                ->where('brand_id', $brand->id)
                ->firstOrFail();

    $vintage = Vintage::where('slug', $vintageSlug)
                      ->where('type_id', $type->id)
                      ->firstOrFail();

    $models = BrandModel::forVintage($vintage)
        ->when(
            $subcategory->fuelType && !$subcategory->fuelType->is_universal,
            function ($query) use ($subcategory) {
                $query->where('fuel_type_id', $subcategory->fuel_type_id);
            }
        )
        ->with('fuelType')
        ->get();

    $groupedModels = BrandModel::groupedByFuel($models);

    return view('categories.model', compact(
        'category', 'subcategory', 'productCategory', 'brand', 'type', 'vintage', 'models', 'groupedModels'
    ));
})->name('termekcsoport_model');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug?}/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}', 
function ($categorySlug, $subcategorySlug, $productCategorySlug = null, $brandSlug, $typeSlug, $vintageSlug, $modelSlug) {

    $category = Category::where('slug', $categorySlug)->firstOrFail();

    $subcategory = SubCategory::with('fuelType')
        ->where('slug', $subcategorySlug)
        ->where('category_id', $category->kategory_id)
        ->firstOrFail();

    $productCategory = null;
    if ($productCategorySlug) {
        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->first();
    }

    $brand = Brand::where('slug', $brandSlug)->firstOrFail();

    $type = Type::where('slug', $typeSlug)
        ->where('brand_id', $brand->id)
        ->firstOrFail();

    $vintage = Vintage::where('slug', $vintageSlug)
        ->where('type_id', $type->id)
        ->firstOrFail();

    $model = BrandModel::forVintage($vintage)
        ->where('slug', $modelSlug)
        ->when($subcategory->fuelType && !$subcategory->fuelType->is_universal, function ($query) use ($subcategory) {
            $query->where('fuel_type_id', $subcategory->fuel_type_id);
        })
        ->with('fuelType')
        ->firstOrFail();

    $products = Product::whereHas('oemNumbers.partVehicles', function ($query) use ($model, $subcategory) {
        $query->where('unique_code', $model->unique_code)
              ->whereHas('oemNumber', function ($q) use ($subcategory) {
                  $q->whereHas('product', fn($p) => $p->where('subcategory_id', $subcategory->subcategory_id));
              });
    })
    ->when($productCategory && $productCategory->id, fn($q) => $q->where('product_category_id', $productCategory->id))
    ->get();

    return view('categories.products', compact(
        'category', 'subcategory', 'productCategory', 'brand', 'type', 'vintage', 'model', 'products'
    ));
})->name('termekcsoport_products');



















