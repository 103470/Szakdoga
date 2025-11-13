<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\RareBrand;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\RareBrandController;
use App\Models\Brands\Type;
use App\Models\RareBrands\Type as RareType;
use App\Models\Brands\Vintage;
use App\Models\RareBrands\Vintage as RareVintage;
use App\Models\Brands\BrandModel;
use App\Models\RareBrands\RareBrandModel;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\Product;

class BrandResolverService
{
    /**
     * Create a new class instance.
     */
    public function resolveType(string $slug)
    {
        $brand = Brand::where('slug', $slug)->first();
        if ($brand) {
            return [
                'isRare' => false,
                'brand' => $brand
            ];
        }

        $rareBrand = RareBrand::where('slug', $slug)->first();
        if ($rareBrand) {
            return [
                'isRare' => true,
                'brand' => $rareBrand
            ];
        }

        abort(404, 'Márka nem található.');
    }

    public function resolveVintage($brandSlug, $typeSlug)
    {
        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();
            $vintages = Vintage::where('type_id', $type->id)->get();

            return [
                'isRare' => false,
                'brand' => $brand,
                'type' => $type,
                'vintages' => $vintages
            ];
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();
        $vintages = RareVintage::where('type_id', $type->id)->get();

        return [
            'isRare' => true,
            'brand' => $rareBrand,
            'type' => $type,
            'vintages' => $vintages
        ];
    }


    public function resolveModel(string $brandSlug, string $typeSlug, string $vintageSlug): array
    {
        if ($brand = Brand::where('slug', $brandSlug)->first()) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();
            $vintage = Vintage::where('type_id', $type->id)
                            ->where('slug', $vintageSlug)
                            ->firstOrFail();
            $models = BrandModel::forVintage($vintage)->get();

            return [
                'brand' => $brand,
                'type' => $type,
                'vintage' => $vintage,
                'models' => $models,
                'isRare' => false
            ];
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('rare_brand_id', $rareBrand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();
        $vintage = RareVintage::where('type_id', $type->id)
                            ->where('slug', $vintageSlug)
                            ->firstOrFail();
        $models = RareBrandModel::forVintage($vintage)->get();

        return [
            'brand' => $rareBrand,
            'type' => $type,
            'vintage' => $vintage,
            'models' => $models,
            'isRare' => true
        ];
    }



    public function resolveCategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug)
    {
        if ($brand = Brand::where('slug', $brandSlug)->first()) {
            $type = Type::where('brand_id', $brand->id)
                        ->where('slug', $typeSlug)
                        ->firstOrFail();
            $vintage = Vintage::where('type_id', $type->id)
                            ->where('slug', $vintageSlug)
                            ->firstOrFail();
            $model = BrandModel::where('slug', $modelSlug)
                            ->where('type_id', $type->id)
                            ->firstOrFail();

            return [
                'brand' => $brand,
                'type' => $type,
                'vintage' => $vintage,
                'model' => $model,
                'isRare' => false
            ];
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

        return [
            'brand' => $rareBrand,
            'type' => $type,
            'vintage' => $vintage,
            'model' => $model,
            'isRare' => true
        ];
    }


    public function resolveSubcategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug)
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

            return [
                'brand' => $brand,
                'type' => $type,
                'vintage' => $vintage,
                'model' => $model,
                'category' => $category,
                'isRare' => false
            ];
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

        return [
            'brand' => $rareBrand,
            'type' => $type,
            'vintage' => $vintage,
            'model' => $model,
            'category' => $category,
            'isRare' => true
        ];
    }

    public function resolveProductCategory($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug)
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

            return [
                'brand' => $brand,
                'type' => $type,
                'vintage' => $vintage,
                'model' => $model,
                'category' => $category,
                'subcategory' => $subcategory,
                'isRare' => false
            ];
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

        return [
            'brand' => $rareBrand,
            'type' => $type,
            'vintage' => $vintage,
            'model' => $model,
            'category' => $category,
            'subcategory' => $subcategory,
            'isRare' => true
        ];
    }

    public function resolveProducts($brandSlug, $typeSlug, $vintageSlug, $modelSlug, $categorySlug, $subcategorySlug,$productCategorySlug = null) 
    {
        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('brand_id', $brand->id)->where('slug', $typeSlug)->firstOrFail();
            $vintage = Vintage::where('type_id', $type->id)->where('slug', $vintageSlug)->firstOrFail();
            $model = BrandModel::where('slug', $modelSlug)->where('type_id', $type->id)->firstOrFail();
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            $subcategory = SubCategory::where('slug', $subcategorySlug)
                                    ->where('category_id', $category->kategory_id)
                                    ->firstOrFail();

            $productCategory = null;
            if ($productCategorySlug && $productCategorySlug !== 'osszes_termek') {
                $productCategory = ProductCategory::where('slug', $productCategorySlug)
                    ->where('subcategory_id', $subcategory->subcategory_id)
                    ->first();
            }

            return [
                'brand' => $brand,
                'type' => $type,
                'vintage' => $vintage,
                'model' => $model,
                'category' => $category,
                'subcategory' => $subcategory,
                'productCategory' => $productCategory,
                'isRare' => false
            ];
        }

        $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
        $type = RareType::where('rare_brand_id', $rareBrand->id)->where('slug', $typeSlug)->firstOrFail();
        $vintage = RareVintage::where('type_id', $type->id)->where('slug', $vintageSlug)->firstOrFail();
        $model = RareBrandModel::where('slug', $modelSlug)->where('type_id', $type->id)->firstOrFail();
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::where('slug', $subcategorySlug)
                                ->where('category_id', $category->kategory_id)
                                ->firstOrFail();

        $productCategory = null;
        if ($productCategorySlug && $productCategorySlug !== 'osszes_termek') {
            $productCategory = ProductCategory::where('slug', $productCategorySlug)
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->first();
        }

        return [
            'brand' => $rareBrand,
            'type' => $type,
            'vintage' => $vintage,
            'model' => $model,
            'category' => $category,
            'subcategory' => $subcategory,
            'productCategory' => $productCategory,
            'isRare' => true
        ];
    }



}
