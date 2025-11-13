<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RareBrandController;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\Category;
use App\Models\Brands\Type;
use App\Models\Brands\Vintage;
use App\Models\Brands\BrandModel;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\RareBrands\Type as RareType;
use App\Models\RareBrands\Vintage as RareVintage;
use App\Models\RareBrands\RareBrandModel;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductController;
use App\Services\BrandResolverService;
use App\Services\BrandControllerDispatcher;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/tipus/{slug}', function($slug, BrandResolverService $resolver) {
    $data = $resolver->resolveType($slug);
    return BrandControllerDispatcher::dispatch($data, 'type');
})->name('marka');


Route::get('/tipus/{brandSlug}/{typeSlug}', function($brandSlug, $typeSlug, BrandResolverService $resolver) {
    $data = $resolver->resolveVintage($brandSlug, $typeSlug);
    return BrandControllerDispatcher::dispatch($data, 'vintage');
})->name('tipus');


Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}', function($brandSlug, $typeSlug, $vintageSlug, BrandResolverService $resolver) {
    $data = $resolver->resolveModel($brandSlug, $typeSlug, $vintageSlug);
    return BrandControllerDispatcher::dispatch($data, 'model');
})->name('model');


Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}', function(
    $brandSlug, $typeSlug, $vintageSlug, $modelSlug, BrandResolverService $resolver
) {
    $data = $resolver->resolveCategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug);
    return BrandControllerDispatcher::dispatch($data, 'categories');
})->name('kategoria');


Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}/{categorySlug}', 
    function($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, BrandResolverService $resolver) {

        $data = $resolver->resolveSubcategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug);
        return BrandControllerDispatcher::dispatch($data, 'subcategories');
})->name('alkategoria');


Route::get('/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}/{categorySlug}/{subcategorySlug}', 
    function($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug, BrandResolverService $resolver) {

        $data = $resolver->resolveProductCategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug);
        return BrandControllerDispatcher::dispatch($data, 'productCategory');
})->name('termekkategoria');


Route::get(
    '/tipus/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}/{categorySlug}/{subcategorySlug}/{productCategorySlug}',
    function(
        $brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug, $productCategorySlug,
        BrandResolverService $resolver
    ) {
        $data = $resolver->resolveProducts($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug, $productCategorySlug);
        return BrandControllerDispatcher::dispatch($data, 'products');
    }
)->name('termekek');




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
        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $types = $brand->types;
            return view('categories.type', compact('category', 'subcategory', 'brand', 'types', 'productCategory'));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $rareTypes = $rareBrand->types; 
        return view('categories.raretypes', compact('category', 'subcategory', 'rareBrand', 'rareTypes', 'productCategory'));
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

    $brand = Brand::where('slug', $brandSlug)->first();

    if ($brand) {
        $type = $brand->types()->where('slug', $typeSlug)->firstOrFail();
        $vintages = Vintage::where('type_id', $type->id)->get();
        return view('categories.vintage', compact('category','subcategory','productCategory','brand','type','vintages'));
    }

    $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
    $type = RareType::where('rare_brand_id', $rareBrand->id)
        ->where('slug', $typeSlug)
        ->firstOrFail();

    $hasVintage = RareVintage::where('type_id', $type->id)->exists();

    if ($hasVintage) {
        $vintages = RareVintage::where('type_id', $type->id)->get();    
        return view('categories.rarevintage', compact('category','subcategory','productCategory','rareBrand','type','vintages'));
    }

    return view('categories.raremodel', compact('category','subcategory','productCategory','rareBrand','type'));

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

    $brand = Brand::where('slug', $brandSlug)->first();

    if ($brand) {
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
    }

    $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
    $type = RareType::where('slug', $typeSlug)
                    ->where('rare_brand_id', $rareBrand->id)
                    ->firstOrFail();

    $vintage = RareVintage::where('slug', $vintageSlug)
                          ->where('type_id', $type->id)
                          ->firstOrFail();

    $models = RareBrandModel::forVintage($vintage)
        ->when(
            $subcategory->fuelType && !$subcategory->fuelType->is_universal,
            function ($query) use ($subcategory) {
                $query->where('fuel_type_id', $subcategory->fuel_type_id);
            }
        )
        ->with('fuelType')
        ->get();

    $groupedModels = RareBrandModel::groupedByFuel($models);

    return view('categories.raremodel', compact(
        'category', 'subcategory', 'productCategory', 'rareBrand', 'type', 'vintage', 'models', 'groupedModels'
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

    $brand = Brand::where('slug', $brandSlug)->first();
    $rareBrand = null;

    if ($brand) {
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

    } else {
        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();

        $type = RareType::where('slug', $typeSlug)
            ->where('rare_brand_id', $rareBrand->id)
            ->firstOrFail();

        $vintage = RareVintage::where('slug', $vintageSlug)
            ->where('type_id', $type->id)
            ->firstOrFail();

        $model = RareBrandModel::forVintage($vintage)
            ->where('slug', $modelSlug)
            ->when($subcategory->fuelType && !$subcategory->fuelType->is_universal, function ($query) use ($subcategory) {
                $query->where('fuel_type_id', $subcategory->fuel_type_id);
            })
            ->with('fuelType')
            ->firstOrFail();
    }

    $products = Product::whereHas('oemNumbers.partVehicles', function ($query) use ($model, $subcategory) {
        $query->where('unique_code', $model->unique_code)
              ->whereHas('oemNumber', function ($q) use ($subcategory) {
                  $q->whereHas('product', fn($p) => $p->where('subcategory_id', $subcategory->subcategory_id));
              });
    })
    ->when($productCategory && $productCategory->id, fn($q) => $q->where('product_category_id', $productCategory->id))
    ->get();

    if ($rareBrand) {
        return view('categories.rareproducts', compact(
            'category', 'subcategory', 'productCategory', 'rareBrand', 'type', 'vintage', 'model', 'products'
        ));
    }

    return view('categories.products', compact(
        'category', 'subcategory', 'productCategory', 'brand', 'type', 'vintage', 'model', 'products'
    ));
})->name('termekcsoport_products');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/content', [CartController::class, 'getCartContent'])->name('cart.content');
Route::delete('/cart/item/{id}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::patch('/cart/item/{id}', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::get('/cart/dropdown', [CartController::class, 'dropdown'])->name('cart.dropdown');

Route::prefix('checkout')->group(function () {
    Route::get('/choice', [CheckoutController::class, 'choice'])->name('checkout.choice');
    Route::get('/details', [CheckoutController::class, 'details'])->name('checkout.details');
    Route::post('/details/submit', [CheckoutController::class, 'submitDetails'])->name('checkout.details.submit');
    Route::get('/payment', [CheckoutController::class, 'showPaymentPage'])->name('checkout.payment');
    Route::post('/finalize', [CheckoutController::class, 'finalize'])->name('checkout.finalize');
});

Route::get('/search', [ProductController::class, 'search'])->name('products.search');

























