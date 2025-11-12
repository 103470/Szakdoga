<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\RareBrands\Type as RareType;
use App\Models\RareBrands\Vintage as RareVintage;
use App\Models\Brands\Vintage;
use App\Models\Brands\Type;
use App\Models\Brands\BrandModel;
use App\Models\RareBrands\RareBrandModel;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\Product;

class BrandController extends Controller
{
    /*public function type(Brand $brand)
    {
        $brand->load(['types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);

        return view('brands.type', compact('brand', 'chunks'));
    }*/

    public function type($slug)
    {
        $brand = Brand::where('slug', $slug)->first();
        if ($brand) {
            $types = Type::where('brand_id', $brand->id)
                ->orderBy('name', 'asc')
                ->get();
            return view('brands.type', compact('brand', 'types'));
    
        }

        $rareBrand = RareBrand::where('slug', $slug)->first();
        if ($rareBrand) {
            $types = RareType::where('rare_brand_id', $rareBrand->id)->get();
            return view('rarebrands.type', compact('rareBrand', 'types'));
        }

        abort(404);
    }

    public function vintage($brandSlug, $typeSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                ->where('slug', $typeSlug)
                ->firstOrFail();

            $vintages = Vintage::where('type_id', $type->id)->get();
            if ($vintages->count() === 1) {
                $singleVintage = $vintages->first();
                return redirect()->route('modell', [
                    'brandSlug' => $brand->slug,
                    'typeSlug' => $type->slug,
                    'vintageSlug' => $singleVintage->slug
                ]);
            }

            if ($vintages->count() > 1) {
                return view('brands.vintage', compact('brand', 'type', 'vintages'));
            }

            abort(404);
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

        $vintages = RareVintage::where('type_id', $type->id)->get();

        if ($vintages->count() === 1) {
            $singleVintage = $vintages->first();
            return redirect()->route('modell', [
                'brandSlug' => $rareBrand->slug,
                'typeSlug' => $type->slug,
                'vintageSlug' => $singleVintage->slug
            ]);
        }

        if ($vintages->count() > 1) {
            return view('rarebrands.vintage', compact('rareBrand', 'type', 'vintages'));
        }

        abort(404);
    }

    public function model($brandSlug, $typeSlug, $vintageSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->first();

        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

            $vintage = Vintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

            $models = BrandModel::forVintage($vintage)->get();

            if ($models->count() === 1) {
                $singleModel = $models->first();
                return redirect()->route('kategoria', [
                    'brandSlug' => $brand->slug,
                    'typeSlug' => $type->slug,
                    'vintageSlug' => $vintage->slug,
                    'modelSlug' => $singleModel->slug
                ]);
            }

            $groupedModels = BrandModel::groupedByFuel($models);

            return view('brands.model', compact('brand', 'type', 'vintage', 'models', 'groupedModels'));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();

        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

        $vintage = RareVintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

        $models = RareBrandModel::forVintage($vintage)->get();

        if ($models->count() === 1) {
            $singleModel = $models->first();
            return redirect()->route('kategoria', [
                'brandSlug' => $rareBrand->slug,
                'typeSlug' => $type->slug,
                'vintageSlug' => $vintage->slug,
                'modelSlug' => $singleModel->slug
            ]);
        }

        $groupedModels = RareBrandModel::groupedByFuel($models);

        return view('rarebrands.model', compact('rareBrand', 'type', 'vintage', 'models', 'groupedModels'));
    }

    public function categories($brandSlug, $typeSlug, $vintageSlug, $modelSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->first();

        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

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
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();

        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

        $vintage = RareVintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

        $model = RareBrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

        $categories = Category::where('requires_model', true)
                              ->orderBy('name')
                              ->get();

        return view('rarebrands.categories', compact('rareBrand', 'type', 'vintage', 'model', 'categories'));
    }

    public function subcategories($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug)
    {
        $brand = Brand::where('slug', $brandSlug)->first();

        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

            $vintage = Vintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

            $model = BrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $subcategories = SubCategory::where('category_id', $category->kategory_id)
                ->where(function ($query) use ($model) {
                    $query->whereHas('fuelType', function ($q) use ($model) {
                        $q->where('id', $model->fuel_type_id)
                          ->orWhere('is_universal', true);
                    });
                })
                ->get();

            if ($subcategories->count() === 1) {
                $singleSubcategory = $subcategories->first();

                $productCategories = $singleSubcategory->productCategories;

                if ($productCategories->isNotEmpty()) {
                    return redirect()->route('termekkategoria', [
                        'brandSlug' => $brand->slug,
                        'typeSlug' => $type->slug,
                        'vintageSlug' => $vintage->slug,
                        'modelSlug' => $model->slug,
                        'categorySlug' => $category->slug,
                        'subcategorySlug' => $singleSubcategory->slug,
                    ]);
                }

                $products = Product::where('subcategory_id', $singleSubcategory->subcategory_id)->get();

                return view('brands.products', compact(
                    'brand', 'type', 'vintage', 'model', 'category', 'singleSubcategory', 'products'
                ));
            }

            return view('brands.subcategories', compact('brand', 'type', 'vintage', 'model', 'category', 'subcategories'));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();

        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

        $vintage = RareVintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

        $model = RareBrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $subcategories = SubCategory::where('category_id', $category->kategory_id)
            ->where(function ($query) use ($model) {
                $query->whereHas('fuelType', function ($q) use ($model) {
                    $q->where('id', $model->fuel_type_id)
                      ->orWhere('is_universal', true);
                });
            })
            ->get();

        if ($subcategories->count() === 1) {
            $singleSubcategory = $subcategories->first();
            $productCategories = $singleSubcategory->productCategories;

            if ($productCategories->isNotEmpty()) {
                return redirect()->route('termekkategoria', [
                    'brandSlug' => $brand->slug,
                    'typeSlug' => $type->slug,
                    'vintageSlug' => $vintage->slug,
                    'modelSlug' => $model->slug,
                    'categorySlug' => $category->slug,
                    'subcategorySlug' => $singleSubcategory->slug,
                ]);
            }

            $products = Product::where('subcategory_id', $singleSubcategory->subcategory_id)->get();

            return view('rarebrands.products', compact(
                'rareBrand', 'type', 'vintage', 'model', 'category', 'singleSubcategory', 'products'
            ));
        }

        return view('rarebrands.subcategories', compact('rareBrand', 'type', 'vintage', 'model', 'category', 'subcategories'));
    }

    public function productCategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug)
    {
        $brand = Brand::where('slug', $brandSlug)->first();

        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

            $vintage = Vintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

            $model = BrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

            $category = Category::where('slug', $categorySlug)->firstOrFail();

            $subcategory = SubCategory::where('slug', $subcategorySlug)
                                      ->where('category_id', $category->kategory_id)
                                      ->firstOrFail();

            $productCategories = $subcategory->productCategories()->get();

            if ($productCategories->count() === 1) {
                $singleProductCategory = $productCategories->first();

                $products = Product::where('product_category_id', $singleProductCategory->product_category_id)->get();

                return view('brands.products', compact(
                    'brand', 'type', 'vintage', 'model', 'category', 'subcategory', 'singleProductCategory', 'products'
                ));
            }

            if ($productCategories->isNotEmpty()) {
                return view('brands.productcategories', compact(
                    'brand', 'type', 'vintage', 'model', 'category', 'subcategory', 'productCategories'
                ));
            }

            $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get();

            return view('brands.products', compact(
                'brand', 'type', 'vintage', 'model', 'category', 'subcategory', 'products'
            ));
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();

        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

        $vintage = RareVintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

        $model = RareBrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

        $category = Category::where('slug', $categorySlug)->firstOrFail();

        $subcategory = SubCategory::where('slug', $subcategorySlug)
                                  ->where('category_id', $category->kategory_id)
                                  ->firstOrFail();

        $productCategories = $subcategory->productCategories()->get();

        if ($productCategories->count() === 1) {
            $singleProductCategory = $productCategories->first();

            $products = Product::where('product_category_id', $singleProductCategory->product_category_id)->get();

            return view('rarebrands.products', compact(
                'rareBrand', 'type', 'vintage', 'model', 'category', 'subcategory', 'singleProductCategory', 'products'
            ));
        }

        if ($productCategories->isNotEmpty()) {
            return view('rarebrands.productcategories', compact(
                'rareBrand', 'type', 'vintage', 'model', 'category', 'subcategory', 'productCategories'
            ));
        }

        $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get();

        return view('rarebrands.products', compact(
            'rareBrand', 'type', 'vintage', 'model', 'category', 'subcategory', 'products'
        ));
    }

    public function products($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug, $productCategorySlug) 
    {
        $brand = Brand::where('slug', $brandSlug)->first();

        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

            $vintage = Vintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

            $model = BrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

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
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();

        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();

        $vintage = RareVintage::where('type_id', $type->id)
                              ->where('slug', $vintageSlug)
                              ->firstOrFail();

        $model = RareBrandModel::where('slug', $modelSlug)
                               ->where('type_id', $type->id)
                               ->firstOrFail();

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

        return view('rarebrands.products', compact(
            'rareBrand', 'type', 'vintage', 'model', 'category', 'subcategory', 'productCategory', 'products'
        ));
    }

}
