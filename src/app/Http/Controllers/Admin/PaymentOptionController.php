<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentOption;

class PaymentOptionController extends Controller
{
    public function index()
    {
        $options = PaymentOption::orderBy('name')->paginate(20);

        return view('admin.fizetesi.index', compact('options'));
    }

    public function create()
    {
        return view('admin.fizetesi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'fee'         => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'required|in:0,1',
            'type'        => 'required|in:card,cod',
        ]);

        PaymentOption::create([
            'name'        => $request->name,
            'fee'         => $request->fee,
            'description' => $request->description,
            'is_active'   => $request->is_active,
            'type'        => $request->type, 
        ]);

        return redirect()->route('admin.fizetesi.index')
                         ->with('success', 'Fizetési opció sikeresen létrehozva.');
    }

    public function edit(PaymentOption $option)
    {
        return view('admin.fizetesi.edit', compact('option'));
    }

    public function update(Request $request, PaymentOption $option)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'fee'         => 'nullable|numeric|min:0',
            'description' => 'nullable|string',
            'is_active'   => 'required|in:0,1',
            'type'        => 'required|in:card,cod',
        ]);

        $option->update([
            'name'        => $request->name,
            'fee'         => $request->fee,
            'description' => $request->description,
            'is_active'   => $request->is_active,
            'type'        => $request->type,
        ]);

        return redirect()->route('admin.fizetesi.index')
                         ->with('success', 'Fizetési opció sikeresen frissítve.');
    }

    public function destroy(PaymentOption $option)
    {
        $option->delete();

        return redirect()->route('admin.fizetesi.index')
                         ->with('success', 'Fizetési opció sikeresen törölve.');
    }
}
