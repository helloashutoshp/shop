<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tempimage;
use Illuminate\Http\Request;

class imageController extends Controller
{
    public function index(Request $req){
        $image = $req->image;
        $ext = $image->getClientOriginalExtension();
        $newname = time().'.'.$ext;
        $imgTable = new Tempimage();
        $imgTable->name = $newname;
        $imgTable->save();
        $image->move(public_path().'/temp',$newname);
        $img_path = asset('temp/'.$newname);
        return response()->json([
            'status'=>true,
            'imageId'=>$imgTable->id,
            'imagePath'=>$img_path,
            'message'=>'Image uploaded successfully'
        ]);
    }
}
