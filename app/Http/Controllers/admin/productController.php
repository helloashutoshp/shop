<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Cate;
use App\Models\Product;
use App\Models\ProductImg;
use App\Models\Subcategory;
use App\Models\Tempimage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Can;
use Illuminate\Support\Facades\File;

class productController extends Controller
{
    public function create()
    {
        $category = Cate::orderBy('name', 'ASC')->where('status', 1)->get();
        $brand = Brand::orderBy('name', 'ASC')->get();
        return view('admin.product.create', ['category' => $category, 'brands' => $brand]);
    }

    public function store(Request $req)
    {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:product',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if (!empty($req['track_qty']) && $req['track_qty'] == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($req->all(), $rules);
        if ($validator->passes()) {
            $product = new Product();
            $product->title = $req->title;
            $product->slug = $req->slug;
            $product->description = $req->description;
            $product->short_description = $req->short_description;
            $product->shipping_return = $req->shipping_return;
            $product->price = $req->price;
            $product->compare_price = $req->compare_price;
            $product->cate_id = $req->category;
            $product->sub_cate_id = $req->sub_category;
            $product->brand_id = $req->brand;
            $product->isfeature = $req->is_featured;
            $product->sku = $req->sku;
            $product->barcode = $req->barcode;
            $product->trackqty = $req->track_qty;
            $product->qty = $req->qty;
            $product->status = $req->status;
            $product->related_product = !empty($req->related_product) ? implode(',', $req->related_product) : '';

            $product->save();

            if (!empty($req->productImg)) {
                foreach ($req->productImg as $imageId) {
                    $tempImg = Tempimage::find($imageId);
                    $imageName = explode('.', $tempImg->name);
                    $ext = last($imageName);
                    $productImage = new ProductImg();
                    $productImage->product_id = $product->id;
                    $productImage->save();
                    $newImagename = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image =  $newImagename;
                    $productImage->save();
                    $spath = public_path() . '/temp/' . $tempImg->name;
                    $dpath = public_path() . '/uploads/product/large/' . $newImagename;
                    File::copy($spath, $dpath);
                    // $tempFolderPath = public_path() . '/temp';
                    // File::deleteDirectory($tempFolderPath);
                }
            }

            session()->flash('success', 'Successfully product created');
            return response()->json([
                'status' => true,
                'message' => 'validation done'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function index(Request $req)
    {

        $search = $req['keyword'];
        if ($search) {
            $product = Product::with('product_img')
                ->select(
                    'prod.*',
                    'cat.name as categoryName',
                    'sub_cate.name as subCategoryName',
                    'brand.name as brandName'
                )
                ->leftJoin('cat', 'cat.id', '=', 'prod.category_id')
                ->leftJoin('sub_cate', 'sub_cate.id', '=', 'prod.sub_cate_id')
                ->leftJoin('brand', 'brand.id', '=', 'prod.brand_id')
                ->when($search, function ($query, $search) {
                    $query->where('prod.title', 'LIKE', "%{$search}%");
                })
                ->latest('prod.id')
                ->paginate(10);
        } else {
            $product = Product::with('product_img')
                ->select(
                    'prod.*',
                    'cat.name as categoryName',
                    'sub_cate.name as subCategoryName',
                    'brand.name as brandName'
                )
                ->leftJoin('cat', 'cat.id', '=', 'prod.cate_id')
                ->leftJoin('sub_cate', 'sub_cate.id', '=', 'prod.sub_cate_id')
                ->leftJoin('brand', 'brand.id', '=', 'prod.brand_id')
                ->latest('prod.id')
                ->paginate(10);
                // dd($product);

        }
        return view('admin.product.list', ['product' => $product]);
    }

    public function subCategory(Request $req)
    {
        if (!empty($req->category)) {
            $subcategory = Subcategory::where('cate_id', $req->category)->where('status', 1)->orderBy('name', 'ASC')->get();
            return response()->json([
                'status' => true,
                'subcategory' => $subcategory
            ]);
        } else {
            return response()->json([
                'status' => false,
                'subcategory' => ''
            ]);
        }
    }

    public function edit($id)
    {
        $prod = Product::find($id);
        $productImage = ProductImg::where('product_id', $id)->get();
        $productArray = [];
        $items = collect();
        if (!empty($prod->related_product)) {
            $productArray = explode(',', $prod->related_product);
            $items = Product::whereIn('id', $productArray)->get();
        }

        if (empty($prod)) {
            session()->flash('error', ' product not found');
            return redirect()->route('admin-product-list');
        }
        $category = Cate::orderby('name', 'ASC')->get();
        $brand = Brand::orderby('name', 'ASC')->get();
        $subcategory = Subcategory::where('id', $prod->sub_cate_id)
            ->orderBy('name', 'ASC')
            ->get();
        $product = Product::select(
            'prod.*',
            'cat.name as categoryName',
            'sub_cate.name as subCategoryName',
            'brand.name as brandName',
        )
            ->leftJoin('cat', 'cat.id', '=', 'prod.cate_id')
            ->leftJoin('sub_cate', 'sub_cate.id', '=', 'prod.sub_cate_id')
            ->leftJoin('brand', 'brand.id', '=', 'prod.brand_id')
            ->where('prod.id', $id)
            ->first();
        if (empty($product)) {
            session()->flash('error', ' product not found');
            return redirect()->route('admin-product-list');
        }
        return view('admin.product.edit', ['product' => $product, 'brands' => $brand, 'category' => $category, 'subcategory' => $subcategory, 'productImage' => $productImage, 'items' => $items]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $productImages = ProductImg::where('product_id', $id)->get();
        if (!$product) {
            session()->flash('error', 'product not found');
            return response()->json([
                'status' => false,
                'message' => 'no product found'
            ]);
        }
        $product->delete();
        foreach ($productImages as $productImage) {
            File::delete(public_path('uploads/product/large/' . $productImage->image));
            $productImage->delete(); // Delete the image record from the database
        }
        session()->flash('success', 'Product deleted');
        return response()->json([
            'status' => true,
            'message' => 'deleted'
        ]);
    }

    public function update(Request $req)
    {
        $product = Product::find($req->product_id);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:product,slug,' . $product->id . 'id',
            'price' => 'required|numeric',
            'sku' => 'required',
            'track_qty' => 'required',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if (!empty($req['track_qty']) && $req['track_qty'] == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }

        $validator = Validator::make($req->all(), $rules);
        if ($validator->passes()) {
            $product->title = $req->title;
            $product->slug = $req->slug;
            $product->description = $req->description;
            $product->short_description = $req->short_description;
            $product->shipping_return = $req->shipping_return;
            $product->price = $req->price;
            $product->compare_price = $req->compare_price;
            $product->cate_id = $req->category;
            $product->sub_cate_id = $req->sub_category;
            $product->brand_id = $req->brand;
            $product->isfeature = $req->is_featured;
            $product->sku = $req->sku;
            $product->barcode = $req->barcode;
            $product->trackqty = $req->track_qty;
            $product->qty = $req->qty;
            $product->status = $req->status;
            $product->related_product = !empty($req->related_product) ? implode(',', $req->related_product) : '';
            $product->update();

            session()->flash('success', 'Successfully product updated');
            return response()->json([
                'status' => true,
                'message' => 'validation done'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function updateImage(Request $req)
    {
        $image = $req->file('image');
        $pathname = $image->getPathname();
        $ext = $image->getClientOriginalExtension();
        $productImage = new ProductImg();
        $productImage->product_id = $req->product_id;
        $productImage->save();
        $newImagename = $req->product_id . '-' . $productImage->id . '-' . time() . '.' . $ext;
        $productImage->image =  $newImagename;
        $productImage->save();
        $dpath = public_path() . '/uploads/product/large/' . $newImagename;
        File::copy($pathname, $dpath);
        $imagePath = asset('uploads/product/large/' . $newImagename);
        return response()->json([
            'status' => true,
            'imageId' => $productImage->id,
            'imagePath' => $imagePath,
            'message' => 'Image uploaded successfully'

        ]);
    }

    public function deleteProductImage(Request $req)
    {
        $image = ProductImg::find($req->id);
        if (!$image) {
            return response()->json([
                "status" => false,
                "Message" => "no image found"
            ]);
        } else {
            $image->delete();
            File::delete(public_path() . '/uploads/product/large/' . $image->image);
            session()->flash('success', 'Image deleted');
            return response()->json([
                'status' => true,
                'message' => 'deleted'
            ]);
        }
    }

    public function productRelated(Request $request)
    {
        $total_product = [];
        if (!empty($request->term)) {
            $product = Product::where('title', 'like', '%' . $request->term . '%')->get();

            if (!empty($product)) {
                foreach ($product as $prod) {
                    $total_product[] = array('id' => $prod->id, 'text' => $prod->title);
                }
            }
        }
        return response()->json([
            'tags' => $total_product,
            'status' => 'success'
        ]);
    }
}
