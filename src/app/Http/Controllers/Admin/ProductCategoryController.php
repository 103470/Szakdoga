<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\SubCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $productCategories = ProductCategory::with('subcategory')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.termekkategoriak.index', compact('productCategories'));
    }

    public function create()
    {
        $subcategories = SubCategory::orderBy('name')->get();

        return view('admin.termekkategoriak.create', compact('subcategories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProductCategory($request);

        ProductCategory::create($validated);

        return redirect()->route('admin.termekkategoriak.index')
            ->with('success', 'Termékkategória sikeresen létrehozva!');
    }

    public function edit(ProductCategory $productcategory)
    {
        $subcategories = SubCategory::orderBy('name')->get();

        return view('admin.termekkategoriak.edit', compact('productcategory', 'subcategories'));
    }

    public function update(Request $request, ProductCategory $productcategory)
    {
        $validated = $this->validateProductCategory($request, $productcategory);

        $productcategory->update($validated);

        return redirect()->route('admin.termekkategoriak.index')
            ->with('success', 'Termékkategória sikeresen frissítve!');
    }

    public function destroy(ProductCategory $productcategory)
    {
        $productcategory->forceDelete();

        return redirect()->route('admin.termekkategoriak.index')
            ->with('success', 'Termékkategória törölve!');
    }

    private function validateProductCategory(Request $request, ?ProductCategory $productcategory = null)
    {
        return $request->validate([
            'subcategory_id' => 'required|exists:subcategories,subcategory_id',
            'name' => 'required|string|max:255|unique:product_categories,name,' . ($productcategory?->product_category_id ?? 'NULL') . ',product_category_id',

        ]);
    }
}
