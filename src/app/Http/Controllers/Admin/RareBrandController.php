<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RareBrand;
use App\Models\RareBrands\Type;
use App\Models\RareBrands\Vintage;
use App\Models\RareBrands\RareBrandModel;

class RareBrandController extends Controller
{
    public function index()
    {
        $rareBrands = RareBrand::all();
        return view('admin.ritkamarkak.index', compact('rareBrands'));
    }

    public function create()
    {
        return view('admin.ritkamarkak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $slug = \Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (RareBrand::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        RareBrand::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return redirect()->route('admin.ritkamarkak.index')->with('success', 'Ritka márka hozzáadva!');
    }

    public function edit($id)
    {
        $rareBrand = RareBrand::findOrFail($id);
        return view('admin.ritkamarkak.edit', compact('rareBrand'));
    }

    public function update(Request $request, $id)
    {
        $rareBrand = RareBrand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $slug = \Str::slug($request->name);
        $originalSlug = $slug;
        $counter = 1;

        while (RareBrand::where('slug', $slug)->where('id', '!=', $rareBrand->id)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $rareBrand->update([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return redirect()->route('admin.ritkamarkak.index')->with('success', 'Ritka márka frissítve!');
    }

    public function destroy($id)
    {
        $rareBrand = RareBrand::withTrashed()->findOrFail($id);

        foreach ($rareBrand->types as $type) {
            foreach ($type->vintages as $vintage) {
                foreach ($vintage->models as $model) {
                    $model->forceDelete();
                }
                $vintage->forceDelete();
            }
            $type->forceDelete();
        }

        $rareBrand->forceDelete();

        return redirect()->route('admin.ritkamarkak.index')->with('success', 'Ritka márka és minden kapcsolódó rekord végleg törölve!');
    }
}
