<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Cate;
use App\Models\Tempimage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Intervention\Image\Laravel\Facades\Image;

class categoryCotroller extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request['keyword'];
        $category = Cate::latest();
        if ($keyword) {
            $category = $category->where('name', 'like', '%' . $keyword . '%');
        }
        $category = $category->paginate(10);
        return view('admin.category.list', ['category' => $category]);
    }
    public function create()
    {
        return view('admin.category.create');
    }
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:cat'
        ]);
        if ($validator->passes()) {
            $category = new Cate();
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->showHome = $req->show;
            $category->save();
            if (!empty($req->image_id)) {
                $tempimage = Tempimage::find($req->image_id);
                $extArray = explode('.', $tempimage->name);
                $ext = last($extArray);
                $newimgname = $category->id . '.' . $ext;
                $spath = public_path() . '/temp/' . $tempimage->name;
                $dpath = public_path() . '/uploads/category_img/' . $newimgname;
                // $thumb_img = public_path() . '/uploads/category_img/thumb/' . $newimgname;
                File::copy($spath, $dpath);
                // File::copy($spath, $thumb_img);
                // $tempFolderPath = public_path() . '/temp';
                // File::deleteDirectory($tempFolderPath);
                $category->image = $newimgname;
                $category->save();
            }
            session()->flash('success', 'Successfully category created');
            return response()->json([
                'status' => true,
                'message' => "vlaidation done",

            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($id)
    {
        $category = Cate::find($id);
        if (empty($category)) {
            session()->flash('error', ' category not found');
            return redirect()->route('category-list');
        }
        return view('admin.category.edit', ['category' => $category]);
    }
    public function update(Request $req)
    {

        $category = Cate::find($req->cat_id);
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'slug' => 'required|unique:cat,slug,' . $category->id . 'id'
        ]);
        if ($validator->passes()) {
            $category->name = $req->name;
            $category->slug = $req->slug;
            $category->status = $req->status;
            $category->showHome = $req->show;
            $category->save();
            if (!empty($req->image_id)) {
                // dd($req->image_id);
                $tempimage = Tempimage::find($req->image_id);

                // dd($tempimage->name);
                $extArray = explode('.', $tempimage->name);
                $ext = last($extArray);
                $newimgname = $category->id . '.' . $ext;
                $spath = public_path() . '/temp/' . $tempimage->name;
                $dpath = public_path() . '/uploads/category_img/' . $newimgname;
                // $thumb_img = public_path() . '/uploads/category_img/thumb/' . $newimgname;
                // $img = Image::make($spath);
                // $img->resize(450,600);
                // $img->save($thumb_img);
                File::copy($spath, $dpath);
                // $tempFolderPath = public_path() . '/temp';
                // File::deleteDirectory($tempFolderPath);
                // File::copy($spath, $thumb_img);
                $category->image = $newimgname;
                $category->save();
            }
            session()->flash('success', 'Successfully category created');
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
    public function destroy($id)
    {
        $category = Cate::find($id);
        if (!$category) {
            session()->flash('error', 'category not found');
            return response()->json([
                'status' => false,
                'message' => 'no catetgory found'
            ]);
        }
        $category->delete();
        File::delete(public_path() . '/uploads/category_img/' . $category->image);
        // File::delete(public_path() . '/uploads/category_img/thumb/' . $category->image);
        session()->flash('success', 'Item deleted');
        return response()->json([
            'status' => true,
            'message' => 'deleted'
        ]);
    }
    public function slug(Request $req)
    {
        $title = $req->title;
        $slug = Str::slug($title);
        return response()->json([
            'status' => true,
            'slug' => $slug
        ]);
    }
}



