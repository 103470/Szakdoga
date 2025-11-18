<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Brands\Type;

class BrandTypeController extends Controller
{
    public function index()
    {
        $types = Type::with('brand')
            ->whereHas('brand')
            ->join('brands', 'brand_types.brand_id', '=', 'brands.id')
            ->orderBy('brands.name', 'asc') 
            ->select('brand_types.*') 
            ->get();

        return view('admin.markak.tipusok.index', compact('types'));
    }

    public function create()
    {
        $brands = Brand::orderBy('name')->get();

        return view('admin.markak.tipusok.create', compact('brands'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255'
        ]);

        Type::create([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.markak.tipusok.index')
            ->with('success', 'Típus sikeresen hozzáadva!');
    }

    public function edit(Type $type)
    {
        $brands = Brand::orderBy('name')->get();

        return view('admin.markak.tipusok.edit', compact('type', 'brands'));
    }

    public function update(Request $request, Type $type)
    {
        $request->validate([
            'brand_id' => 'required|exists:brands,id',
            'name' => 'required|string|max:255'
        ]);

        $type->update([
            'brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return redirect()
            ->route('admin.markak.tipusok.index')
            ->with('success', 'Típus sikeresen frissítve!');
    }

    public function destroy(Type $type)
    {
        foreach ($type->vintages as $vintage) {
            foreach ($vintage->models as $model) {
                $model->forceDelete();
            }
            $vintage->forceDelete();
        }

        $type->forceDelete();

        return redirect()
            ->route('admin.markak.tipusok.index')
            ->with('success', 'Típus és minden hozzá tartozó rekord törölve!');
    }
}
