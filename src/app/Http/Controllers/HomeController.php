<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;

class HomeController extends Controller
{
    public function index() {
        $brands = Brand::select('slug', 'name', 'logo')
            ->orderBy('name', 'asc')
            ->get();

    }
}
