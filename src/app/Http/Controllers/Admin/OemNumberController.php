<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OemNumber;
use App\Models\Product;

class OemNumberController extends Controller
{
    public function index()
    {
        $oemNumbers = OemNumber::with('product')
            ->orderBy('oem_number')
            ->paginate(20);

        return view('admin.oemszamok.index', compact('oemNumbers'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.oemszamok.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateOem($request);

        OemNumber::create($validated);

        return redirect()
            ->route('admin.oemszamok.index')
            ->with('success', 'OEM szám sikeresen létrehozva!');
    }

    public function edit(OemNumber $oemNumber)
    {
        $products = Product::orderBy('name')->get();

        return view('admin.oemszamok.edit', compact('oemNumber', 'products'));
    }

    public function update(Request $request, OemNumber $oemNumber)
    {
        $validated = $this->validateOem($request);

        $oemNumber->update($validated);

        return redirect()
            ->route('admin.oemszamok.index')
            ->with('success', 'OEM szám sikeresen frissítve!');
    }

    public function destroy(OemNumber $oemNumber)
    {
        $oemNumber->forceDelete();

        return redirect()
            ->route('admin.oemszamok.index')
            ->with('success', 'OEM szám törölve!');
    }

    private function validateOem(Request $request)
    {
        return $request->validate([
            'product_id' => 'required|exists:products,id',
            'oem_number' => 'required|string|max:255',
        ]);
    }
}
