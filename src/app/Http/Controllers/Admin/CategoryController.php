<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Carbon\Carbon;

class CategoryController extends Controller
{
     public function index()
    {
        $categories = Category::orderBy('name')->paginate(20);

        return view('admin.kategoriak.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.kategoriak.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        if ($request->hasFile('icon')) {
            $path = $request->file('icon')->store('category', 'public'); 
            $validated['icon'] = $path;
        }

        Category::create($validated);

        return redirect()->route('admin.kategoriak.index')
            ->with('success', 'Kategória sikeresen létrehozva!');
    }

    public function edit(Category $category)
    {
        return view('admin.kategoriak.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category);

        if ($request->hasFile('icon') && $request->file('icon')->isValid()) {
            $path = $request->file('icon')->store('category', 'public');
            $validated['icon'] = $path;
        }

        $category->update($validated);

        return redirect()->route('admin.kategoriak.index')
            ->with('success', 'Kategória sikeresen frissítve!');
    }


    public function destroy(Category $category)
    {
        if ($category->subcategories()->exists()) {
            foreach ($category->subcategories as $sub) {
                $sub->forceDelete();
            }
        }

        $category->forceDelete();

        return redirect()->route('admin.kategoriak.index')  
            ->with('success', 'Kategória és minden hozzá tartozó al-kategória törölve!');
    }

    private function validateCategory(Request $request, ?Category $category = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->kategory_id . ',kategory_id',
            'icon' => 'nullable|image|max:2048'
        ]);
    }

}
