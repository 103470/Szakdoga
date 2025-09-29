<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{
    public function type(Brand $brand)
    {
        $brand->load(['types' => function($query) {
            $query->orderBy('name', 'asc');
        }]);

        return view('brands.type', compact('brand', 'chunks'));
    }
}
