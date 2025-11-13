<?php

namespace App\Services;

use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\Product;
use App\Models\Brands\Type;
use App\Models\RareBrands\Type as RareType;
use App\Models\Brands\Vintage;
use App\Models\RareBrands\Vintage as RareVintage;
use App\Models\Brands\BrandModel;
use App\Models\RareBrands\RareBrandModel;

class CategoryResolverService
{
    public static function resolveSubcategories(Category $category): array
    {
        $subcategories = $category->subcategories;

        if ($subcategories->count() === 1) {
            $subcategory = $subcategories->first();

            if (!$category->requires_model) {
                $products = Product::where('subcategory_id', $subcategory->subcategory_id)->get();

                return [
                    'category' => $category,
                    'subcategory' => $subcategory,
                    'products' => $products
                ];
            }

            $productCategories = $subcategory->productCategories;

            return [
                'category' => $category,
                'subcategory' => $subcategory,
                'productCategories' => $productCategories
            ];
        }

        return [
            'category' => $category,
            'subcategories' => $subcategories
        ];
    }

    public static function resolve(Category $category, SubCategory $subcategory, ?string $productCategorySlug = null, ?string $brandSlug = null): array
    {
        logger()->info('CategoryResolverService::resolve called', [
        'category' => $category->slug,
        'subcategory' => $subcategory->slug,
        'productCategorySlug' => $productCategorySlug,
        'brandSlug' => $brandSlug,
        'category_requires_model' => $category->requires_model,
        'subcategory_productCategories_count' => $subcategory->productCategories->count(),
    ]);

        if (!$brandSlug && $productCategorySlug) {
            $brandSlug = $productCategorySlug;
            $productCategorySlug = null;
        }

        $data = [
            'category' => $category,
            'subcategory' => $subcategory,
            'productCategory' => null,
            'brand' => null,
            'rareBrand' => null
        ];

        if ($productCategorySlug) {
            $data['productCategory'] = ProductCategory::where('slug', $productCategorySlug)
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->firstOrFail();
        }

        if ($brandSlug) {
            $brand = Brand::where('slug', $brandSlug)->first();
            if ($brand) {
                $data['brand'] = $brand; 
                $data['types'] = $brand->types;
            } else {
                $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
                $data['rareBrand'] = $rareBrand; 
                $data['rareTypes'] = $rareBrand->types;
            }
        }


        if ($data['productCategory']) {
            if ($category->requires_model) {
                $data['brands'] = Brand::all();
                $data['rareBrands'] = RareBrand::all();
            } else {
                $data['products'] = Product::where('product_category_id', $data['productCategory']->id)->get();
            }
        } else {
            $productCategories = $subcategory->productCategories;
            if ($productCategories->isNotEmpty()) {
                $data['productCategories'] = $productCategories;
            } elseif ($category->requires_model) {
                $data['brands'] = Brand::all();
                $data['rareBrands'] = RareBrand::all();
            } else {
                $data['products'] = Product::where('subcategory_id', $subcategory->subcategory_id)->get();
            }
        }

        logger()->info('CategoryResolverService::resolve result keys', array_keys($data));
        return $data;
    }

    public static function getData(
        string $categorySlug,
        string $subcategorySlug,
        ?string $productCategorySlug,
        string $brandSlug,
        string $typeSlug
    ): array {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();

        $productCategory = $productCategorySlug
            ? ProductCategory::where('slug', $productCategorySlug)
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->firstOrFail()
            : null;

        $brand = Brand::where('slug', $brandSlug)->first();
        $data = [
            'category' => $category,
            'subcategory' => $subcategory,
            'productCategory' => $productCategory,
            'brand' => $brand,
            'rareBrand' => null,
            'type' => null,
            'vintages' => null
        ];

        if ($brand) {
            $data['type'] = $brand->types()->where('slug', $typeSlug)->firstOrFail();
            $data['vintages'] = Vintage::where('type_id', $data['type']->id)->get();
        } else {
            $rareBrand = RareBrand::where('slug', $brandSlug)->firstOrFail();
            $data['rareBrand'] = $rareBrand;
            $data['type'] = RareType::where('rare_brand_id', $rareBrand->id)
                ->where('slug', $typeSlug)
                ->firstOrFail();

            if (RareVintage::where('type_id', $data['type']->id)->exists()) {
                $data['vintages'] = RareVintage::where('type_id', $data['type']->id)->get();
            }
        }

        return $data;
    }

    public static function getModelData(
        string $categorySlug,
        string $subcategorySlug,
        ?string $productCategorySlug,
        string $brandSlug,
        string $typeSlug,
        string $vintageSlug
    ): array {
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::with('fuelType')
            ->where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();

        $productCategory = $productCategorySlug
            ? ProductCategory::where('slug', $productCategorySlug)
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->firstOrFail()
            : null;

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

            return compact('category','subcategory','productCategory','brand','type','vintage','models','groupedModels');
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

        return compact('category','subcategory','productCategory','rareBrand','type','vintage','models','groupedModels');
    }

    public static function getProductData(
        string $categorySlug,
        string $subcategorySlug,
        ?string $productCategorySlug,
        string $brandSlug,
        string $typeSlug,
        string $vintageSlug,
        string $modelSlug,
    ): array {
        $brand = null;
        $rareBrand = null;
        $type = null;
        $vintage = null;
        $model = null;
        $products = null;
        $category = Category::where('slug', $categorySlug)->firstOrFail();
        $subcategory = SubCategory::with('fuelType')
            ->where('slug', $subcategorySlug)
            ->where('category_id', $category->kategory_id)
            ->firstOrFail();

        $productCategory = $productCategorySlug
            ? ProductCategory::where('slug', $productCategorySlug)
                ->where('subcategory_id', $subcategory->subcategory_id)
                ->first()
            : null;

        $brand = Brand::where('slug', $brandSlug)->first();
        if ($brand) {
            $type = Type::where('slug', $typeSlug)
                        ->where('brand_id', $brand->id)
                        ->firstOrFail();
            $vintage = Vintage::where('slug', $vintageSlug)
                              ->where('type_id', $type->id)
                             ->firstOrFail();
            $model = BrandModel::forVintage($vintage)
                ->where('slug', $modelSlug)
                ->when(
                    $subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($query) => $query->where('fuel_type_id', $subcategory->fuel_type_id)
                )
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
                ->when(
                    $subcategory->fuelType && !$subcategory->fuelType->is_universal,
                    fn($query) => $query->where('fuel_type_id', $subcategory->fuel_type_id)
                )
                ->with('fuelType')
                ->firstOrFail();
        }

        $products = Product::whereHas('oemNumbers.partVehicles', function ($query) use ($model, $subcategory) {
            $query->where('unique_code', $model->unique_code)
                  ->whereHas('oemNumber', fn($q) => $q->whereHas('product', fn($p) => $p->where('subcategory_id', $subcategory->subcategory_id)));
        })
        ->when($productCategory && $productCategory->id, fn($q) => $q->where('product_category_id', $productCategory->id))
        ->get();

        return compact('category','subcategory','productCategory','brand','rareBrand','type','vintage','model','products');
    }
}
