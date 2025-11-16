<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\CategoryResolverService;
use App\Services\CategoryControllerDispatcher;
use App\Models\Product;
use App\Services\UrlNormalizer; 
use App\Models\SubCategory;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\ProductCategory;
use App\Models\Brands\Type;
use App\Models\RareBrands\Type as RareType;
use App\Models\Brands\Vintage;
use App\Models\RareBrands\Vintage as RareVintage;
use App\Models\Brands\BrandModel;
use App\Models\RareBrands\RareBrandModel;


class CategoryController extends Controller
{
    public function showSubcategories(Category $category)
    {
        $subcategories = $category->subcategories;

        if ($subcategories->count() === 1) {
            $subcategory = $subcategories->first();

            if (!$category->requires_model) {
                $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get();

                return view('categories.products', compact(
                    'category',
                    'subcategory',
                    'products'
                ));
            }

            $productCategories = $subcategory->productCategories;

            if ($productCategories->isNotEmpty()) {
                return redirect()->route('termekcsoport_dynamic', [
                    'category' => $category->slug,
                    'subcategory' => $subcategory->slug,
                    'productCategorySlug' => $productCategories->first()->slug,
                ]);
            } else {
                return redirect()->route('termekcsoport_dynamic', [
                    'category' => $category->slug,
                    'subcategory' => $subcategory->slug,
                    'productCategorySlug' => 'osszes_termek',
                ]);
            }


        }

        return view('categories.subcategories', compact('category', 'subcategories'));
    }


    public function showProducts($categorySlug, $subcategorySlug, $productCategorySlug = null, $brandSlug, $typeSlug, $vintageSlug, $modelSlug) 
    {
        $data = CategoryResolverService::getProductData($categorySlug, $subcategorySlug, $productCategorySlug, $brandSlug, $typeSlug, $vintageSlug, $modelSlug);
        return CategoryControllerDispatcher::renderProductPage($data);
    }

    public function handle(Category $category, SubCategory $subcategory, ?string $productCategorySlug = null, ?string $brandSlug = null)
    {
        [$productCategorySlug, $brandSlug] = UrlNormalizer::normalize($productCategorySlug, $brandSlug);

        $productCategories = $subcategory->productCategories;

        $isAllProductsSlug = $productCategorySlug === 'osszes_termek' || $productCategorySlug === null;

        if ($isAllProductsSlug) {
            if ($productCategories->count() > 1) {
                return view('categories.productcategories', [
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'productCategories' => $productCategories,
                ]);
            }

            if ($productCategories->count() === 1) {
                $productCategorySlug = $productCategories->first()->slug;
            }
        }

        $data = CategoryResolverService::resolve($category, $subcategory, $productCategorySlug, $brandSlug);

        return CategoryControllerDispatcher::dispatch($data);
    }

    public function index(Category $category, SubCategory $subcategory)
    {
        $productCategories = ProductCategory::where('subcategory_id', $subcategory->subcategory_id)->get();

        if ($productCategories->count() > 0) {
            return view('categories.productcategories', [
                'category' => $category,
                'subcategory' => $subcategory,
                'productCategories' => $productCategories
            ]);
        }

        if (!$category->requires_model) {
            $products = Product::whereIn(
                'product_category_id',
                $subcategory->productCategories()->pluck('id')
            )->get();

            return view('categories.products', [
                'category' => $category,
                'subcategory' => $subcategory,
                'products' => $products
            ]);
        }

        return view('categories.brands', [
            'category' => $category,
            'subcategory' => $subcategory,
            'brands' => Brand::all(),
            'rareBrands' => RareBrand::all(),
        ]);
    }

    public function brand(Category $category, SubCategory $subcategory, $brandSlug)
    {
        logger()->info('brand() called', [
            'category' => $category->toArray(),
            'subcategory' => $subcategory->toArray(),
            'brandSlug' => $brandSlug,
            'category_requires_model' => $category->requires_model
        ]);

        if (!$category->requires_model) {
            logger()->warning('Category does not require model, abort 404');
            abort(404);
        }

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            logger()->info('Brand found', ['brand' => $brand->toArray()]);
            return view('categories.type', [
                'category' => $category,
                'subcategory' => $subcategory,
                'brand' => $brand,
                'types' => $brand->types
            ]);
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        logger()->warning('Brand not found, checking RareBrand', ['brandSlug' => $brandSlug]);
        return view('categories.raretypes', [
            'category' => $category,
            'subcategory' => $subcategory,
            'rareBrand' => $rareBrand,
            'rareTypes' => $rareBrand->types
        ]);
    }

    public function productCategory(Category $category, SubCategory $subcategory, ProductCategory $productCategory)
    {
        if ($category->requires_model) {
            return view('categories.brands', [
                'category' => $category,
                'subcategory' => $subcategory,
                'productCategory' => $productCategory,
                'brands' => Brand::all(),
                'rareBrands' => RareBrand::all(),
            ]);
        }

        $products = Product::where('product_category_id', $productCategory->product_category_id)->get();

        return view('categories.products', [
            'category' => $category,
            'subcategory' => $subcategory,
            'productCategory' => $productCategory,
            'products' => $products
        ]);
    }


    public function productCategoryBrand(Category $category, SubCategory $subcategory, ProductCategory $productCategory, $brandSlug) 
    {
        if (!$category->requires_model) abort(404);

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            return view('categories.type', [
                'category' => $category,
                'subcategory' => $subcategory,
                'productCategory' => $productCategory,
                'brand' => $brand,
                'types' => $brand->types
            ]);
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        return view('categories.raretypes', [
            'category' => $category,
            'subcategory' => $subcategory,
            'productCategory' => $productCategory,
            'rareBrand' => $rareBrand,
            'rareTypes' => $rareBrand->types
        ]);
    }

   public function vintage($categorySlug, $subcategorySlug, $brandSlug, $typeSlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::where('slug', $subcategorySlug)->firstOrFail();
        $productCategory = null;

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('slug', $typeSlug)
                ->where('brand_id', $brand->id)
                ->firstOrFail();

            $vintages = Vintage::where('type_id', $type->id)->get();

            return view('categories.vintage', compact(
                'category', 'subcategory', 'productCategory', 'brand', 'type', 'vintages'
            ));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('slug', $typeSlug)
            ->where('rare_brand_id', $rareBrand->id)
            ->firstOrFail();

        $vintages = RareVintage::where('type_id', $type->id)->get();

        return view('categories.rarevintage', compact(
            'category', 'subcategory', 'productCategory', 'rareBrand', 'type', 'vintages'
        ));
    }

    public function productCategoryVintage($categorySlug, $subcategorySlug, $productCategorySlug, $brandSlug, $typeSlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::where('slug', $subcategorySlug)->firstOrFail();
        $productCategory = ProductCategory::where('slug', $productCategorySlug)->firstOrFail();

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('slug', $typeSlug)
                ->where('brand_id', $brand->id)
                ->firstOrFail();

            $vintages = Vintage::where('type_id', $type->id)->get();

            return view('categories.vintage', compact(
                'category', 'subcategory', 'productCategory', 'brand', 'type', 'vintages'
            ));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('slug', $typeSlug)
            ->where('rare_brand_id', $rareBrand->id)
            ->firstOrFail();

        $vintages = RareVintage::where('type_id', $type->id)->get();

        return view('categories.rarevintage', compact(
            'category', 'subcategory', 'productCategory', 'rareBrand', 'type', 'vintages'
        ));
    }

    public function model($categorySlug, $subcategorySlug, $brandSlug, $typeSlug, $vintageSlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::with('fuelType')
            ->where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();
        $productCategory = null;

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('slug', $typeSlug)->where('brand_id', $brand->id)->firstOrFail();
            $vintage = Vintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
            $models = BrandModel::forVintage($vintage)
                ->when(
                    $subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($query) => $query->where('fuel_type_id', $subcategory->fuel_type_id)
                )
                ->with('fuelType')
                ->get();
            $groupedModels = BrandModel::groupedByFuel($models);

            return view('categories.model', compact(
                'category','subcategory','productCategory','brand','type','vintage','models','groupedModels'
            ));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('slug', $typeSlug)->where('rare_brand_id', $rareBrand->id)->firstOrFail();
        $vintage = RareVintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
        $models = RareBrandModel::forVintage($vintage)
            ->when(
                $subcategory->fuelType && !$subcategory->fuelType->is_universal,
                fn($query) => $query->where('fuel_type_id', $subcategory->fuel_type_id)
            )
            ->with('fuelType')
            ->get();
        $groupedModels = RareBrandModel::groupedByFuel($models);

        return view('categories.raremodel', compact(
            'category','subcategory','productCategory','rareBrand','type','vintage','models','groupedModels'
        ));
    }

    public function productCategoryModel($categorySlug, $subcategorySlug, $productCategorySlug, $brandSlug, $typeSlug, $vintageSlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::with('fuelType')
            ->where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();
        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->firstOrFail();

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('slug', $typeSlug)->where('brand_id', $brand->id)->firstOrFail();
            $vintage = Vintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
            $models = BrandModel::forVintage($vintage)
                ->when(
                    $subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($query) => $query->where('fuel_type_id', $subcategory->fuel_type_id)
                )
                ->with('fuelType')
                ->get();
            $groupedModels = BrandModel::groupedByFuel($models);

            return view('categories.model', compact(
                'category','subcategory','productCategory','brand','type','vintage','models','groupedModels'
            ));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('slug', $typeSlug)->where('rare_brand_id', $rareBrand->id)->firstOrFail();
        $vintage = RareVintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
        $models = RareBrandModel::forVintage($vintage)
            ->when(
                $subcategory->fuelType && !$subcategory->fuelType->is_universal,
                fn($query) => $query->where('fuel_type_id', $subcategory->fuel_type_id)
            )
            ->with('fuelType')
            ->get();
        $groupedModels = RareBrandModel::groupedByFuel($models);

        return view('categories.raremodel', compact(
            'category','subcategory','productCategory','rareBrand','type','vintage','models','groupedModels'
        ));
    }

    public function product($categorySlug, $subcategorySlug, $brandSlug, $typeSlug, $vintageSlug, $modelSlug) 
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::with('fuelType')
            ->where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();

        $productCategory = null;

        $brand = Brand::where('slug', $brandSlug)->first();
        $rareBrand = null;

        if ($brand) {
            $type = Type::where('slug', $typeSlug)->where('brand_id', $brand->id)->firstOrFail();
            $vintage = Vintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
            $model = BrandModel::forVintage($vintage)->where('slug', $modelSlug)
                ->when($subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($q) => $q->where('fuel_type_id', $subcategory->fuel_type_id))
                ->with('fuelType')
                ->firstOrFail();

            $products = Product::whereHas('partVehicles', fn($q) => $q->where('unique_code', $model->unique_code))
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->get();

            return view('categories.products', compact(
                'category', 'subcategory', 'productCategory',
                'brand', 'type', 'vintage', 'model', 'products'
            ));
        } else {
            $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
            $type = RareType::where('slug', $typeSlug)->where('rare_brand_id', $rareBrand->id)->firstOrFail();
            $vintage = RareVintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
            $model = RareBrandModel::forVintage($vintage)->where('slug', $modelSlug)
                ->when($subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($q) => $q->where('fuel_type_id', $subcategory->fuel_type_id))
                ->with('fuelType')
                ->firstOrFail();

            $products = Product::whereHas('partVehicles', fn($q) => $q->where('unique_code', $model->unique_code))
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->get();

            return view('categories.rareproducts', compact(
                'category', 'subcategory', 'productCategory',
                'rareBrand', 'type', 'vintage', 'model', 'products'
            ));
        }
    }

    public function productCategoryProduct($categorySlug, $subcategorySlug, $productCategorySlug, $brandSlug, $typeSlug, $vintageSlug, $modelSlug)
    {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::with('fuelType')
            ->where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();

        $productCategory = ProductCategory::where('slug', $productCategorySlug)
            ->where('subcategory_id', $subcategory->subcategory_id)
            ->firstOrFail();

        $brand = Brand::where('slug', $brandSlug)->first();
        $rareBrand = null;

        if ($brand) {
            $type = Type::where('slug', $typeSlug)->where('brand_id', $brand->id)->firstOrFail();
            $vintage = Vintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
            $model = BrandModel::forVintage($vintage)->where('slug', $modelSlug)
                ->when($subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($q) => $q->where('fuel_type_id', $subcategory->fuel_type_id))
                ->with('fuelType')
                ->firstOrFail();

            $products = Product::whereHas('partVehicles', fn($q) => $q->where('unique_code', $model->unique_code))
                ->where('product_category_id', $productCategory->product_category_id)
                ->get();

            return view('categories.products', compact(
                'category', 'subcategory', 'productCategory',
                'brand', 'type', 'vintage', 'model', 'products'
            ));
        } else {
            $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
            $type = RareType::where('slug', $typeSlug)->where('rare_brand_id', $rareBrand->id)->firstOrFail();
            $vintage = RareVintage::where('slug', $vintageSlug)->where('type_id', $type->id)->firstOrFail();
            $model = RareBrandModel::forVintage($vintage)->where('slug', $modelSlug)
                ->when($subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($q) => $q->where('fuel_type_id', $subcategory->fuel_type_id))
                ->with('fuelType')
                ->firstOrFail();

            $products = Product::whereHas('partVehicles', fn($q) => $q->where('unique_code', $model->unique_code))
                ->where('product_category_id', $productCategory->product_category_id)
                ->get();

            return view('categories.rareproducts', compact(
                'category', 'subcategory', 'productCategory',
                'rareBrand', 'type', 'vintage', 'model', 'products'
            ));
        }
    }


 

}
