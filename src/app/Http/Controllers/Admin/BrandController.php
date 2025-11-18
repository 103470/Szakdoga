<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Brands\Type;
use App\Models\Brands\Vintage;
use App\Models\Brands\BrandModel;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        return view('admin.markak.index', compact('brands'));
    }

    public function create()
    {
        return view('admin.markak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $brand = new Brand();
        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $brand->logo = $request->file('logo')->store('brands', 'public');
        }

        $brand->save();

        return redirect()->route('admin.markak.index')->with('success', 'Márka hozzáadva!');
    }

    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        return view('admin.markak.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $brand->name = $request->name;

        if ($request->hasFile('logo')) {
            $brand->logo = $request->file('logo')->store('brands', 'public');
        }

        $brand->save();

        return redirect()->route('admin.markak.index')->with('success', 'Márka frissítve!');
    }

    public function destroy($id)
    {
        $brand = Brand::withTrashed()->findOrFail($id);

        foreach ($brand->types as $type) {
            foreach ($type->vintages as $vintage) {
                foreach ($vintage->models as $model) {
                    $model->forceDelete();
                }
                $vintage->forceDelete();
            }
            $type->forceDelete();
        }

        $brand->forceDelete();

        return redirect()->route('admin.markak.index')->with('success', 'Márka és minden kapcsolódó rekord végleg törölve!');
    }

}
