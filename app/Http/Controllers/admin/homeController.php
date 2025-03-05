<?php

namespace App\Http\Controllers\admin;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class homeController extends Controller
{
    public function index()
    {
        // $admin = Auth::guard('admin')->user();
        // echo 'Welcome' . $admin->name . '<a href="' . route('admin-logout') . '">Logout</a>';
        return view('admin.dashboard');
    }
    public function logout()
    {
        $admin = Auth::guard('admin')->logout();
        return redirect()->route('admin-login');
    }

    
}
