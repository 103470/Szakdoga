<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminMarkaController extends Controller
{
    public function index()
    {
        return view('admin.markak.index');
    }

    public function create()
    {
        return view('admin.markak.create');
    }

    public function store(Request $request)
    {
        // ide jön majd az adatmentés logika
    }

    public function edit($id)
    {
        return view('admin.markak.edit');
    }

    public function update(Request $request, $id)
    {
        // frissítés logika
    }

    public function destroy($id)
    {
        // törlés logika
    }
}
