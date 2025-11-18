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
use App\Http\Controllers\CategoryController;
use App\Services\CategoryResolverService;
use App\Services\CategoryControllerDispatcher;
use App\Services\UrlNormalizer;

Route::get('login', function () {
    return view('login');
})->name('login');
Route::post('login', LoginController::class)->name('login.attempt');

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

Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', [ResetPasswordController::class, 'create'])
    ->middleware('guest')
    ->name('password.reset');

Route::post('/reset-password', [ResetPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.update');

Route::view('/aszf', 'aszf')->name('aszf');
Route::view('/adatvedelem', 'adatvedelem')->name('adatvedelem');

Route::get('/test-admin', function () {
    return 'Admin middleware működik!';
})->middleware('admin');
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

Route::get('/termek/{product:slug}', [ProductController::class, 'show'])->name('termek.leiras');

Route::get('/termekcsoport/{category:slug}', [CategoryController::class, 'showSubcategories'])
    ->name('termekcsoport');

Route::get('/termekcsoport/{category:slug}/{subcategory:slug}', 
    [CategoryController::class, 'index']
)->name('termekcsopor_productCategory');

Route::get('/termekcsoport/{category:slug}/{subcategory:slug}/{productCategory:slug}',
    [CategoryController::class, 'productCategory']
)->name('termekcsoport_brand');

/*Route::get('/termekcsoport/{category:slug}/{subcategory:slug}/{brandSlug}', 
    [CategoryController::class, 'brand']
)->where([
    'brandSlug' => '[^/]+',
])->name('termekcsoport_dynamic');*/

Route::get('/termekcsoport/{category}/{subcategory}/{brandSlug}', function($category, $subcategory, $brandSlug) {
    dd($category, $subcategory, $brandSlug);
});

Route::get('/termekcsoport/{category:slug}/{subcategory:slug}/{productCategory:slug}/{brandSlug}', 
    [CategoryController::class, 'productCategoryBrand']
)->where([
    'brandSlug' => '[^/]+',
])->name('termekcsoport_dynamic_pc');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{brandSlug}/{typeSlug}', 
    [CategoryController::class, 'vintage']
)->where([
    'brandSlug' => '[^/]+',
    'typeSlug'  => '[^/]+',
])->name('termekcsoport_vintage');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug}/{brandSlug}/{typeSlug}', 
    [CategoryController::class, 'productCategoryVintage']
)->where([
    'brandSlug' => '[^/]+',
    'typeSlug'  => '[^/]+',
])->name('termekcsoport_vintage_pc');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{brandSlug}/{typeSlug}/{vintageSlug}', 
    [CategoryController::class, 'model']
)->where([
    'brandSlug'    => '[^/]+',
    'typeSlug'     => '[^/]+',
    'vintageSlug'  => '[^/]+',
])
->name('termekcsoport_model');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug}/{brandSlug}/{typeSlug}/{vintageSlug}', 
    [CategoryController::class, 'productCategoryModel']
)->where([
    'brandSlug'    => '[^/]+',
    'typeSlug'     => '[^/]+',
    'vintageSlug'  => '[^/]+',
])
->name('termekcsoport_model_pc');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}', 
    [CategoryController::class, 'product']
)->where([
    'brandSlug'   => '[^/]+',
    'typeSlug'    => '[^/]+',
    'vintageSlug' => '[^/]+',
    'modelSlug'   => '[^/]+',
])
->name('termekcsoport_products');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug}/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}', 
    [CategoryController::class, 'productCategoryProduct']
)->where([
    'brandSlug'   => '[^/]+',
    'typeSlug'    => '[^/]+',
    'vintageSlug' => '[^/]+',
    'modelSlug'   => '[^/]+',
])
->name('termekcsoport_products_pc');








/*Route::get('/termekcsoport/{category:slug}/{subcategory:slug}/{productCategorySlug?}/{brandSlug?}', 
    [CategoryController::class, 'handle'])
    ->name('termekcsoport_dynamic');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{brandSlug}/{typeSlug}', 
    function ($categorySlug, $subcategorySlug, $productCategorySlug = null, $brandSlug, $typeSlug) {
        $data = CategoryResolverService::getData($categorySlug, $subcategorySlug, $productCategorySlug, $brandSlug, $typeSlug);
        return CategoryControllerDispatcher::render($data);
    }
)->name('termekcsoport_vintage');


Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug?}/{brandSlug}/{typeSlug}/{vintageSlug}', 
    function ($categorySlug, $subcategorySlug, $productCategorySlug = null, $brandSlug, $typeSlug, $vintageSlug) {
        $data = CategoryResolverService::getModelData($categorySlug, $subcategorySlug, $productCategorySlug, $brandSlug, $typeSlug, $vintageSlug);
        return CategoryControllerDispatcher::renderModelPage($data);
    }
)->name('termekcsoport_model');

Route::get('/termekcsoport/{categorySlug}/{subcategorySlug}/{productCategorySlug?}/{brandSlug}/{typeSlug}/{vintageSlug}/{modelSlug}',
    [CategoryController::class, 'showProducts'])
    ->name('termekcsoport_products');*/


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
    Route::post('/create-session', [CheckoutController::class, 'createStripeCheckoutSession'])->name('checkout.create-session');
    Route::get('/checkout/pending/{order}', [CheckoutController::class, 'pending'])->name('checkout.pending');
    Route::get('/checkout/payment-status/{order}', [CheckoutController::class, 'paymentStatus']);
    Route::get('/success', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/pending', [CheckoutController::class, 'pending'])->name('stripe.pending');
    Route::get('/cancel', [CheckoutController::class, 'cancel'])->name('stripe.cancel');

});

Route::get('/search', [ProductController::class, 'search'])->name('products.search');
Route::post('/stripe/webhook', [CheckoutController::class, 'handleWebhook']);


























