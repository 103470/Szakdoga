<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PhonePrefix;

class PhonePrefixesController extends Controller
{
    public function index()
    {
        $prefixes = PhonePrefix::orderBy('prefix')->get();

        return view('admin.elohivoszamok.index', compact('prefixes'));
    }

    public function create()
    {
        return view('admin.elohivoszamok.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'prefix' => 'required|string|max:10|unique:phone_prefixes,prefix',
            'country' => 'required|string|max:100',
        ]);

        PhonePrefix::create([
            'prefix'  => $request->prefix,
            'country' => $request->country,
        ]);

        return redirect()
            ->route('admin.elohivoszamok.index')
            ->with('success', 'Előhívószám sikeresen hozzáadva.');
    }

    public function edit(PhonePrefix $prefix)
    {
        return view('admin.elohivoszamok.edit', [
            'prefixItem' => $prefix
        ]);
    }

    public function update(Request $request, PhonePrefix $prefix)
    {
        $request->validate([
            'prefix' => 'required|string|max:10|unique:phone_prefixes,prefix,' . $prefix->id,
            'country' => 'required|string|max:100',
        ]);

        $prefix->update([
            'prefix'  => $request->prefix,
            'country' => $request->country,
        ]);

        return redirect()
            ->route('admin.elohivoszamok.index')
            ->with('success', 'Előhívószám sikeresen frissítve.');
    }

    public function destroy(PhonePrefix $prefix)
    {
        $prefix->delete();

        return redirect()
            ->route('admin.elohivoszamok.index')
            ->with('success', 'Előhívószám törölve.');
    }
}
