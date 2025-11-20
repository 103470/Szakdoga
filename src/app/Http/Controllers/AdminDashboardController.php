<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\RareBrand;
use App\Models\Product;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $brands = Brand::all();
        $rareBrands = RareBrand::all();
        $products = Product::all();
        $orders = Order::all();

        $allBrandsCount = $brands->count() + $rareBrands->count();

        return view('admindashboard', [
            'products' => $products,
            'orders' => $orders,
            'allbrands' => $allBrandsCount,
        ]);
    }

}