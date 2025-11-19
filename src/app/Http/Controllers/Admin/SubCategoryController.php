<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use App\Models\FuelType;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subcategories = SubCategory::with('category')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.alkategoriak.index', compact('subcategories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();


        return view('admin.alkategoriak.create', compact('categories', 'fuelTypes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateSubCategory($request);

        SubCategory::create($validated);

        return redirect()->route('admin.alkategoriak.index')
            ->with('success', 'Alkategória sikeresen létrehozva!');
    }

    public function edit(SubCategory $subcategory)
    {
        $categories = Category::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();


        return view('admin.alkategoriak.edit', compact('subcategory', 'categories', 'fuelTypes'));
    }

    public function update(Request $request, SubCategory $subcategory)
    {
        $validated = $this->validateSubCategory($request, $subcategory);

        $subcategory->update($validated);

        return redirect()->route('admin.alkategoriak.index')
            ->with('success', 'Alkategória sikeresen frissítve!');
    }

    public function destroy(SubCategory $subcategory)
    {
        $subcategory->forceDelete();

        return redirect()->route('admin.alkategoriak.index')
            ->with('success', 'Alkategória törölve!');
    }

    private function validateSubCategory(Request $request, ?SubCategory $subcategory = null)
    {
        return $request->validate([
            'category_id' => 'required|exists:categories,kategory_id',
            'name' => 'required|string|max:255|unique:subcategories,name,' . ($subcategory?->subcategory_id ?? 'NULL') . ',subcategory_id',
            'fuel_type_id' => 'nullable|exists:fuel_types,id',
        ]);
    }
}
