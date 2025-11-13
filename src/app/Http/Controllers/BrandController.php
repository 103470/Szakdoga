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
use App\Services\BrandResolverService;

class BrandController extends Controller
{
    /*public function type(Brand $brand)
    {
        $brand->load(['types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);

        return view('brands.type', compact('brand', 'chunks'));
    }*/

    protected $resolveService;

    public function __construct(BrandResolverService $resolveService)
    {
        $this->resolveService = $resolveService;
    }

    public function type($data)
    {
        $types = $data['types']; 
        $brand = $data['brand'];
        $view = $data['view'];

        return view($view, compact('brand', 'types'));
    }



    public function vintage($data)
    {
        if ($data['vintages']->count() === 1) {
            $singleVintage = $data['vintages']->first();
            return redirect()->route('modell', [
                'brandSlug' => $data['brand']->slug,
                'typeSlug' => $data['type']->slug,
                'vintageSlug' => $singleVintage->slug
            ]);
        }

        return view('brands.vintage', [
            'brand' => $data['brand'],
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

        $groupedModels = BrandModel::groupedByFuel($models);

        return view('brands.model', [
            'brand' => $brand,
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

        return view('brands.categories', [
            'brand' => $data['brand'],
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

            return view('brands.products', [
                'brand' => $data['brand'],
                'type' => $data['type'],
                'vintage' => $data['vintage'],
                'model' => $data['model'],
                'category' => $data['category'],
                'singleSubcategory' => $singleSubcategory,
                'products' => $products
            ]);
        }

        return view('brands.subcategories', [
            'brand' => $data['brand'],
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

            return view('brands.products', [
                'brand' => $data['brand'],
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
            return view('brands.productcategories', [
                'brand' => $data['brand'],
                'type' => $data['type'],
                'vintage' => $data['vintage'],
                'model' => $data['model'],
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'productCategories' => $productCategories
            ]);
        }

        $products = Product::where('subcategory_id', $data['subcategory']->subcategory_id)->get();

        return view('brands.products', [
            'brand' => $data['brand'],
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
            $productCategory = $data['subcategory']->productCategories()
                ->where('slug', $productCategorySlug)
                ->first();

            $products = $productCategory
                ? Product::whereHas('oemNumbers.partVehicles', function ($query) use ($uniqueId) {
                        $query->where('unique_code', $uniqueId);
                    })
                    ->where('product_category_id', $productCategory->product_category_id)
                    ->get()
                : collect();
        }

        $view = $data['isRare'] ? 'rarebrands.products' : 'brands.products';
        $brandVar = $data['isRare'] ? 'rareBrand' : 'brand';

        return view($view, array_merge([
            $brandVar => $data['brand'],
            'type' => $data['type'],
            'vintage' => $data['vintage'],
            'model' => $data['model'],
            'category' => $data['category'],
            'subcategory' => $data['subcategory'],
            'productCategory' => $productCategory,
            'products' => $products
        ]));
    }


}
