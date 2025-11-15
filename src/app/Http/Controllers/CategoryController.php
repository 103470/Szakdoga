<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\CategoryResolverService;
use App\Services\CategoryControllerDispatcher;
use App\Models\Product;
use App\Services\UrlNormalizer; 
use App\Models\SubCategory;


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












}
