<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class brandController extends Controller
{
    public function create()
    {
        return view('admin.brand.create');
    }

    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brand',
        ]);
        if ($validator->passes()) {
            $category = new Brand();
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->save();
            session()->flash('success', 'Successfully brand created');
            return response()->json([
                'status' => true,
                'message' => "validation done",

            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function index(Request $request)
    {
        $keyword = $request['keyword'];
        $brand =  Brand::latest();
        if ($keyword) {
            $brand = $brand->where('name', 'like', '%' . $keyword . '%');
        }
        $brand = $brand->paginate(10);
        return view('admin.brand.list', ['brand' => $brand]);
    }

    public function destroy($id)
    {
        $brand = Brand::find($id);
        if (!$brand) {
            session()->flash('error', 'brand not found');
            return response()->json([
                'status' => false,
                'message' => 'no brand found'
            ]);
        }
        $brand->delete();
        session()->flash('success', 'Item deleted');
        return response()->json([
            'status' => true,
            'message' => 'deleted'
        ]);
    }

    public function edit($id)
    {
        $brand = Brand::find($id);
        if (empty($brand)) {
            session()->flash('error', 'brand not found');
            return redirect()->route('admin-brand-list');
        }
        return view('admin.brand.edit', ['brand' => $brand]);
    }

    public function update(Request $req)
    {
        $category = Brand::find($req->cate_id);
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:brand,slug,' . $category->id . 'id',
        ]);
        if ($validator->passes()) {
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->update();
            session()->flash('success', 'Successfully brand updated');
            return response()->json([
                'status' => true,
                'message' => "validation done",

            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
