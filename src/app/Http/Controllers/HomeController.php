<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\Category;

class HomeController extends Controller
{
    public function index() {
        $brands = Brand::select('slug', 'name', 'logo')
            ->orderBy('name', 'asc')
            ->get();

        $rareBrands = RareBrand::select('slug', 'name')
            ->orderBy('name', 'asc')
            ->get();

        $categories = Category::select('slug', 'name', 'icon') 
            ->orderBy('name', 'asc')
            ->get();

        return view('home', compact('brands', 'rareBrands', 'categories'));
    }
}
