<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FuelType;

class FuelTypeController extends Controller
{
    public function index()
    {
        $fuels = FuelType::orderBy('name')->get();
        return view('admin.uzemanyag.index', compact('fuels'));
    }
    
    public function create()
    {
        return view('admin.uzemanyag.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'is_universal' => 'nullable|boolean',
        ]);

        FuelType::create([
            'name'        => $request->name,
            'is_universal'=> $request->input('is_universal', 0),
        ]);

        return redirect()->route('admin.uzemanyag.index')
            ->with('success', 'Üzemanyag típus sikeresen hozzáadva.');
    }

    public function edit(FuelType $fuel)
    {
        return view('admin.uzemanyag.edit', compact('fuel'));
    }

    public function update(Request $request, FuelType $fuel)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'is_universal' => 'nullable|boolean',
        ]);

        $fuel->update([
            'name'        => $request->name,
            'is_universal'=> $request->input('is_universal', 0),
        ]);

        return redirect()->route('admin.uzemanyag.index')
            ->with('success', 'Üzemanyag típus frissítve.');
    } 
    
    public function destroy(FuelType $fuel)
    {
        $fuel->delete();

        return redirect()->route('admin.uzemanyag.index')
            ->with('success', 'Üzemanyag típus törölve.');
    }
}
