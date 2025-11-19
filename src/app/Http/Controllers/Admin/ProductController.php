<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['subcategory', 'productCategory'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.termekek.index', compact('products'));
    }

    public function create()
    {
        $subcategories = SubCategory::orderBy('name')->get();
        $productCategories = ProductCategory::orderBy('name')->get();

        return view('admin.termekek.create', compact('subcategories', 'productCategories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.termekek.index')
            ->with('success', 'Termék sikeresen létrehozva!');
    }

    public function edit(Product $product)
    {
        $subcategories = SubCategory::orderBy('name')->get();
        $productCategories = ProductCategory::orderBy('name')->get();

        return view('admin.termekek.edit', compact('product', 'subcategories', 'productCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product);

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.termekek.index')
            ->with('success', 'Termék sikeresen frissítve!');
    }

    public function destroy(Product $product)
    {
        $product->forceDelete();

        return redirect()->route('admin.termekek.index')
            ->with('success', 'Termék törölve!');
    }

    private function validateProduct(Request $request, ?Product $product = null)
    {
        return $request->validate([
            'subcategory_id' => 'nullable|exists:subcategories,subcategory_id',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'name' => 'required|string|max:255|unique:products,name,' . ($product?->id ?? 'NULL') . ',id',
            'article_number' => 'nullable|string|max:255',
            'manufacturer' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:10',
            'stock' => 'nullable|integer|min:0',
            'is_active' => 'required|boolean',
            'image' => 'nullable|image|max:4096',
        ]);
    }
}
