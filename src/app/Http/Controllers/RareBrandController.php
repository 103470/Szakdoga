<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RareBrand;
use App\Models\RareBrands\Type;
use App\Services\BrandResolverService;
use App\Models\RareBrands\RareBrandModel;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductCategory;
use App\Models\Product;

class RareBrandController extends Controller
{
    protected $resolveService;

    public function __construct(BrandResolverService $resolveService)
    {
        $this->resolveService = $resolveService;
    }

    public function type(array $data)
    {
        $brand = $data['brand'];
        $types = $data['types'];
        $view = $data['view'];

        return view($view, compact('brand', 'types'));
    }


    public function vintage($data)
    {
        if ($data['vintages']->count() === 1) {
            $singleVintage = $data['vintages']->first();
            return redirect()->route('model', [
                'brandSlug' => $data['brand']->slug,
                'typeSlug' => $data['type']->slug,
                'vintageSlug' => $singleVintage->slug
            ]);
        }

        return view('rarebrands.vintage', [
            'rareBrand' => $data['brand'],
            'type' => $data['type'],
            'vintages' => $data['vintages']
        ]);
    }

    public function model($data)
    {
        $brand = $data['brand'];
        $type = $data['type'];
        $vintage = $data['vintage'];
        $models = $data['models'];

        if ($models->count() === 1) {
            $singleModel = $models->first();
            return redirect()->route('kategoria', [
                'brandSlug' => $brand->slug,
                'typeSlug' => $type->slug,
                'vintageSlug' => $vintage->slug,
                'modelSlug' => $singleModel->slug
            ]);
        }

        $groupedModels = RareBrandModel::groupedByFuel($models);

        return view('rarebrands.model', [
            'rareBrand' => $brand,
            'type' => $type,
            'vintage' => $vintage,
            'models' => $models,
            'groupedModels' => $groupedModels
        ]);
    }

    public function categories($data)
    {
        $categories = Category::where('requires_model', true)
                            ->orderBy('name')
                            ->get();

        return view('rarebrands.categories', [
            'rareBrand' => $data['brand'],
            'type' => $data['type'],
            'vintage' => $data['vintage'],
            'model' => $data['model'],
            'categories' => $categories
        ]);
    }

    public function subcategories($data)
    {
        $subcategories = SubCategory::where('category_id', $data['category']->kategory_id)
            ->where(function ($query) use ($data) {
                $query->whereHas('fuelType', function ($q) use ($data) {
                    $q->where('id', $data['model']->fuel_type_id)
                    ->orWhere('is_universal', true);
                });
            })
            ->get();

        if ($subcategories->count() === 1) {
            $singleSubcategory = $subcategories->first();
            $productCategories = $singleSubcategory->productCategories;

            if ($productCategories->isNotEmpty()) {
                return redirect()->route('termekkategoria', [
                    'brandSlug' => $data['brand']->slug,
                    'typeSlug' => $data['type']->slug,
                    'vintageSlug' => $data['vintage']->slug,
                    'modelSlug' => $data['model']->slug,
                    'categorySlug' => $data['category']->slug,
                    'subcategorySlug' => $singleSubcategory->slug,
                ]);
            }

            $products = Product::where('subcategory_id', $singleSubcategory->subcategory_id)->get();

            return view('rarebrands.products', [
                'rareBrand' => $data['brand'],
                'type' => $data['type'],
                'vintage' => $data['vintage'],
                'model' => $data['model'],
                'category' => $data['category'],
                'singleSubcategory' => $singleSubcategory,
                'products' => $products
            ]);
        }

        return view('rarebrands.subcategories', [
            'rareBrand' => $data['brand'],
            'type' => $data['type'],
            'vintage' => $data['vintage'],
            'model' => $data['model'],
            'category' => $data['category'],
            'subcategories' => $subcategories
        ]);
    }

    public function productCategory($data)
    {
        $productCategories = $data['subcategory']->productCategories()->get();

        if ($productCategories->count() === 1) {
            $singleProductCategory = $productCategories->first();
            $products = Product::where('product_category_id', $singleProductCategory->product_category_id)->get();

            return view('rarebrands.products', [
                'rareBrand' => $data['brand'],
                'type' => $data['type'],
                'vintage' => $data['vintage'],
                'model' => $data['model'],
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'singleProductCategory' => $singleProductCategory,
                'products' => $products
            ]);
        }

        if ($productCategories->isNotEmpty()) {
            return view('rarebrands.productcategories', [
                'rareBrand' => $data['brand'],
                'type' => $data['type'],
                'vintage' => $data['vintage'],
                'model' => $data['model'],
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'productCategories' => $productCategories
            ]);
        }

        $products = Product::where('subcategory_id', $data['subcategory']->subcategory_id)->get();

        return view('rarebrands.products', [
            'rareBrand' => $data['brand'],
            'type' => $data['type'],
            'vintage' => $data['vintage'],
            'model' => $data['model'],
            'category' => $data['category'],
            'subcategory' => $data['subcategory'],
            'products' => $products
        ]);
    }


    public function products($data)
    {
        $productCategorySlug = $data['productCategorySlug'] ?? 'osszes_termek';
        $uniqueId = $data['model']->unique_code;

        if ($productCategorySlug === 'osszes_termek') {
            $products = Product::whereHas('oemNumbers.partVehicles', function ($query) use ($uniqueId) {
                    $query->where('unique_code', $uniqueId);
                })
                ->where('subcategory_id', $data['subcategory']->subcategory_id)
                ->get();

            $productCategory = null;
        } else {
            $productCategory = ProductCategory::where('slug', $productCategorySlug)
                ->where('subcategory_id', $data['subcategory']->subcategory_id)
                ->first();

            $products = $productCategory
                ? Product::whereHas('oemNumbers.partVehicles', function ($query) use ($uniqueId) {
                        $query->where('unique_code', $uniqueId);
                    })
                    ->where('product_category_id', $productCategory->product_category_id)
                    ->get()
                : collect();
        }

        return view('rarebrands.products', [
            'rareBrand' => $data['brand'],
            'type' => $data['type'],
            'vintage' => $data['vintage'],
            'model' => $data['model'],
            'category' => $data['category'],
            'subcategory' => $data['subcategory'],
            'productCategory' => $productCategory,
            'products' => $products
        ]);
    }

}
