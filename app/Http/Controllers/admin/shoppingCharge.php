<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use App\Models\shippingCharge;
use App\Models\userShipping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class shoppingCharge extends Controller
{
    public function index()
    {
        $country = CountryModel::get();
        $charges = shippingCharge::leftJoin('country', 'country.id', 'shipcharge.country_id')->where('shipcharge.id', '!=', 9)->get();
        $others = shippingCharge::find(9);
        return view('admin.shipping.create', ['country' => $country, 'charge' => $charges, 'others' => $others]);
    }
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'country' => 'required | unique:shipcharge,country_id',
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

    public function edit($id)
    {
        $shipping = shippingCharge::where('country_id', $id)->first();
        // dd($shipping);
        $country = CountryModel::find($id);
        return view('admin.shipping.edit', ['country' => $country, 'shipping' => $shipping]);
    }
    public function update(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'charge' => 'required',
        ]);
        if ($validate->passes()) {
            $shipping = shippingCharge::find($request->shipId);
            $shipping->charge = $request->charge;
            $shipping->update();
            session()->flash('success', 'Successfully charge updated');
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
    public function destroy($id)
    {
        $data = shippingCharge::find($id);
        $data->delete();
        session()->flash('success', 'Item deleted');
        return response()->json([
            'status' => true,
            'message' => 'deleted'
        ]);
    }

    public function otherShipUpdate(Request $request){
        $charge = $request->others;
        $others = shippingCharge::find(9);
        $others->charge = $charge;
        $others->update();
        session()->flash('success', 'Item updated');
        return response()->json([
            'status' => true,
            'message' => 'updated'
        ]);
    }
}
