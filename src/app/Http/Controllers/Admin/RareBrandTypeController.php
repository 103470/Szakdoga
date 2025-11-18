<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RareBrand;
use App\Models\RareBrands\Type;

class RareBrandTypeController extends Controller
{
    public function index()
    {
        $types = Type::with('rareBrand')
            ->get()
            ->sortBy(function($type) {
                return $type->rareBrand?->name . $type->name;
            })
            ->values();


        return view('admin.ritkamarkak.tipusok.index', compact('types'));
    }

    public function create()
    {
        $brands = RareBrand::orderBy('name')->get();

        return view('admin.ritkamarkak.tipusok.create', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'brand_id' => 'required|exists:rare_brands,id',
            'name' => 'required|string|max:255'
        ]);

        Type::create([
            'rare_brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.ritkamarkak.tipusok.index')
            ->with('success', 'Ritka típus sikeresen hozzáadva!');
    }

    public function edit(Type $type)
    {
        $brands = RareBrand::orderBy('name')->get();

        return view('admin.ritkamarkak.tipusok.edit', compact('type', 'brands'));
    }

    public function update(Request $request, Type $type)
    {
        $request->validate([
            'brand_id' => 'required|exists:rare_brands,id',
            'name' => 'required|string|max:255'
        ]);

        $type->update([
            'rare_brand_id' => $request->brand_id,
            'name' => $request->name,
        ]);

        return redirect()->route('admin.ritkamarkak.tipusok.index')
            ->with('success', 'Ritka típus sikeresen frissítve!');
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

        return redirect()->route('admin.ritkamarkak.tipusok.index')
            ->with('success', 'Ritka típus és minden kapcsolódó rekord törölve!');
    }

}
