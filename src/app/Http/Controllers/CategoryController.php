<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Services\CategoryResolverService;
use App\Services\CategoryControllerDispatcher;
use App\Models\Product;

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
                    'subcategory' => $subcategory->slug
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


}
