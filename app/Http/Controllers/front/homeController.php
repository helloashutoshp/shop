<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class homeController extends Controller
{
    public function index()
    {
        $feature_product = Product::where('isfeature', 'Yes')->where('status', 1)->get();
        $latest_product = Product::orderby('id', 'DESC')->with('product_img')->where('status', 1)->take(8)->get();
        return view('front.home', ['feature_product' => $feature_product ,'latest_product' => $latest_product]);
    }
}
