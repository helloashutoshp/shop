<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Tempimage;
use Illuminate\Http\Request;

class imageController extends Controller
{
    public function index(Request $req)
    {
        $images = $req->file('image');  // Change to `$images` to handle multiple files
        // dd($images);
        $response = [];

        foreach ($images as $image) {  // Loop through each uploaded file
            $ext = $image->getClientOriginalExtension();
            $newname = time() . rand(1000, 9999) . '.' . $ext;
            $imgTable = new Tempimage();
            $imgTable->name = $newname;
            $imgTable->save();

            $image->move(public_path() . '/temp', $newname);

            $response[] = [
                'status' => true,
                'imageId' => $imgTable->id,
                'imagePath' => asset('temp/' . $newname),
                'message' => 'Image uploaded successfully'
            ];
        }

        // dd($response);

        return response()->json($response);
    }

    public function sinleIndex(Request $req)
    {
        $image = $req->file('image');  // Change to `$images` to handle multiple files
        $response = []; 
            $ext = $image->getClientOriginalExtension();
            $newname = time() . rand(1000, 9999) . '.' . $ext;
            $imgTable = new Tempimage();
            $imgTable->name = $newname;
            $imgTable->save();

            $image->move(public_path() . '/temp', $newname);
            // dd($imgTable->id);
            return response()->json([
                'status' => true,
                'imageId' => $imgTable->id,
                'imagePath' => asset('temp/' . $newname),
                'message' => 'Image uploaded successfully'
            ]);
    }
}

// https://chatgpt.com/c/67cf0728-36e8-8000-abd1-b4aff166cb8d
// https://chatgpt.com/share/67d9bd5b-8940-8001-8e50-118889698788
