<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Cate;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class subCategoryController extends Controller
{
    public function create()
    {
        $category = Cate::orderby('name', 'ASC')->where('status',1)->get();
        return view('admin.subcategory.create', ['category' => $category]);
    }

    public function store(Request $req)
    {

        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_cate',
            'category' => 'required'
        ]);
        if ($validator->passes()) {
            $category = new Subcategory();
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->showHome = $req->show;
            $category->cate_id = $req->category;
            $category->save();
            session()->flash('success', 'Successfully sub category created');
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
        $category = Subcategory::select('sub_cate.*', 'cat.name as categoryName')->latest('sub_cate.id')->leftJoin(
            'cat',
            'cat.id',
            'sub_cate.cate_id'
        );
        if ($keyword) {
            $category = $category->where('sub_cate.name', 'like', '%' . $keyword . '%');
            $category = $category->orWhere('cat.name', 'like', '%' . $keyword . '%');
        }
        $category = $category->paginate(10);
        return view('admin.subcategory.list', ['category' => $category]);
    }


    public function destroy($id)
    {
        $category = Subcategory::find($id);
        if (!$category) {
            session()->flash('error', 'category not found');
            return response()->json([
                'status' => false,
                'message' => 'no catetgory found'
            ]);
        }
        $category->delete();
        // File::delete(public_path() . '/uploads/category_img/' . $category->image);
        // File::delete(public_path() . '/uploads/category_img/thumb/' . $category->image);
        // session()->flash('success', 'Item deleted');
        return response()->json([
            'status' => true,
            'message' => 'deleted'
        ]);
    }

    public function edit($id)
    {
        $maincategory = Cate::orderby('name', 'ASC')->get();
        $category = Subcategory::select('sub_cate.*', 'cat.name as categoryName' ,'cat.id as categoryId')
            ->latest('sub_cate.id')
            ->leftJoin('cat', 'cat.id', '=', 'sub_cate.cate_id')
            ->where('sub_cate.id', $id) // Replace $id with the specific ID you want to fetch
            ->first(); // Use `first` to fetch a single record
        if (empty($category)) {
            session()->flash('error', ' category not found');
            return redirect()->route('sub-category-list');
        }
        return view('admin.subcategory.edit', ['category' => $category, 'maincategory' => $maincategory]);
    }
    public function update(Request $req)
    {
        $category = Subcategory::find($req->cat_id);

        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:sub_cate,slug,' . $category->id . 'id',
            'category' => 'required'
        ]);
        if ($validator->passes()) {
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->cate_id = $req->category;
            $category->showHome = $req->show;
            $category->update();
            session()->flash('success', 'Successfully sub category created');
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
