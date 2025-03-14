<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cate;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class shoppingController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null)
    {
        $categorySelected = "";
        $subCategorySelected = "";
        $brandValue = [];
        $priceTo =  "100";
        $priceFrom = "10000";
        $category = Cate::orderby('name', 'ASC')->with('subCategoryStatus')->where('status', 1)->get();
        $brand = Brand::orderby('name', 'ASC')->where('status', 1)->get();
        $product = Product::where('status', 1);
        if (!empty($categorySlug)) {
            $category_id = Cate::where('slug', $categorySlug)->first();
            $product =  $product->where('cate_id', $category_id->id);
            $categorySelected = $category_id->id;
        }

        if (!empty($subCategorySlug)) {
            $subcategory_id = Subcategory::where('slug', $subCategorySlug)->first();
            $product =  $product->where('sub_cate_id', $subcategory_id->id);
            $subCategorySelected = $subcategory_id->id;
        }

        if (!empty($request->brands)) {
            $brandValue = explode(',', $request->brands);
            $product =  $product->whereIn('brand_id', $brandValue);
        }

        if (!empty($request->rangeFrom) || !empty($request->rangeTo)) {
            if ($request->rangeTo == 10000) {
                $product =  $product->whereBetween('price', [intval($request->rangeFrom), 100000000000000000000]);
            } else {
                $product =  $product->whereBetween('price', [intval($request->rangeFrom), intval($request->rangeTo)]);
            }
            $priceTo =  $request->rangeFrom;
            $priceFrom = $request->rangeTo;
        }


        if (!empty($request->priceRange)) {
            if ($request->priceRange == "latest") {
                $product = $product->orderby('id', 'DESC');
                $sort = "latest";
            } else if ($request->priceRange == "low") {
                $product = $product->orderby('price', 'ASC');
                $sort = "low";
            } else {
                $product = $product->orderby('price', 'DESC');
                $sort = "high";
            }
        } else {
            $product = $product->orderby('id', 'DESC');
            $sort = "";
        }

        $product = $product->paginate(10);

        return view('front.shop', ['category' => $category, 'brand' => $brand, 'product' => $product, 'categorySelected' => $categorySelected, 'subCategorySelected' => $subCategorySelected, 'brandValue' => $brandValue, 'priceTo' => $priceTo, 'priceFrom' => $priceFrom, 'sort' => $sort]);
    }

    public function product($slug)
    {
        $product = Product::where('slug', $slug)->with('product_img')->first();
        if($product == NULL){
            abort(404);
        }
        $items = collect();
        $prod = Product::find($product->id);
        $productArray = [];
        if (!empty($prod->related_product)) {
            $productArray = explode(',', $prod->related_product);
            $items = Product::whereIn('id', $productArray)->with('product_img')->get();
            // dd($items->product_img->first());
        }

        return view('front.product', ['product' => $product,'items' => $items]);
    }

    
}
