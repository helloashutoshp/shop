<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use App\Models\shippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class shoppingCharge extends Controller
{
    public function index()
    {
        $country = CountryModel::get();
        $charges = shippingCharge::leftJoin('country', 'country.id', 'shipcharge.country_id')->get();
        // dd($charges);
        return view('admin.shipping.create', ['country' => $country, 'charge' => $charges]);
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'country' => 'required',
            'charge' => 'required',
        ]);
        if ($validate->passes()) {
            $shipping = new shippingCharge();
            $shipping->country_id = $request->country;
            $shipping->charge = $request->charge;
            $shipping->save();
            session()->flash('success', 'Successfully charge added');
            return response()->json([
                'status' => true,
                'message' => "validation done"
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validate->errors()
            ]);
        }
    }
}
