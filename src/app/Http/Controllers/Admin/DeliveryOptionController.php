<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryOption;
use Illuminate\Http\Request;

class DeliveryOptionController extends Controller
{
    public function index()
    {
        return view('admin.szallitasi.index', [
            'options' => DeliveryOption::orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('admin.szallitasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        DeliveryOption::create([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'is_active'   => $request->is_active,
        ]);

        return redirect()->route('admin.szallitasi.index')
            ->with('success', 'Szállítási mód sikeresen létrehozva.');
    }

    public function edit(DeliveryOption $option)
    {
        return view('admin.szallitasi.edit', [
            'option' => $option
        ]);
    }

    public function update(Request $request, DeliveryOption $option)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'nullable|boolean',
        ]);

        $option->update([
            'name'        => $request->name,
            'price'       => $request->price,
            'description' => $request->description,
            'is_active'   => $request->is_active,
        ]);

        return redirect()->route('admin.szallitasi.index')
            ->with('success', 'Szállítási mód frissítve.');
    }

    public function destroy(DeliveryOption $option)
    {
        $option->delete();

        return redirect()->route('admin.szallitasi.index')
            ->with('success', 'Szállítási mód törölve.');
    }
}
