<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\discountModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Stripe\Discount;

class discountController extends Controller
{
    public function index(Request $request)
    {
        $discount = discountModel::latest();
        $keyword = $request->keyword;
        if ($keyword) {
            $discount = $discount->where('name', 'like', '%' . $keyword . '%')->orWhere('code', 'like', '%' . $keyword . '%');
        }
        $discount = $discount->paginate(10);
        return view('admin.discount.list', ['discount' => $discount]);
    }
    public function create()
    {
        return view('admin.discount.create');
    }
    public function store(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'code' => 'required|unique:discount',
            'maxuses' => 'required',
            'maxuser' => 'required',
            'dvalue' => 'required',
            'minimum_amount' => 'required',
            'starts_at' => 'required',
            'ends_at' => 'required',
        ], [
            '*.required' => 'This field is required.',   // Common message for all 'required' fields
            'code.unique' => 'This discount code already exists.' // Specific message for 'code' uniqueness
        ]);
        if ($validator->passes()) {
            if (!empty($req->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $req->starts_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ["starts_at" => "Starts date should not be less than the current date"],

                    ]);
                }
            }

            if (!empty($req->starts_at) && !empty($req->ends_at)) {
                $ends_at = Carbon::createFromFormat('Y-m-d H:i:s', $req->ends_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $req->starts_at);
                if ($ends_at->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ["ends_at" => "End date should greater than start date"],

                    ]);
                }
            }

            $discount = new discountModel();
            $discount->code = $req->code;
            $discount->name = $req->name;
            $discount->description = $req->code;
            $discount->max_uses = $req->maxuses;
            $discount->max_uses_user = $req->maxuser;
            $discount->type = $req->dtype;
            $discount->dicount_amount = $req->dvalue;
            $discount->minimum_amount = $req->minimum_amount;
            $discount->status = $req->status;
            $discount->starts_at = $req->starts_at;
            $discount->ends_at = $req->ends_at;
            $discount->save();
            session()->flash('success', 'Successfully discount created');
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
    public function edit($id)
    {
        $discount = discountModel::find($id);
        return view('admin.discount.edit', ['discount' => $discount]);
    }
    public function update(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required',
            'code' => 'required|unique:discount,code,' . $req->id . 'id',
            'maxuses' => 'required',
            'maxuser' => 'required',
            'dvalue' => 'required',
            'minimum_amount' => 'required',
            'starts_at' => 'required',
            'ends_at' => 'required',
        ], [
            '*.required' => 'This field is required.',   // Common message for all 'required' fields
            'code.unique' => 'This discount code already exists.' // Specific message for 'code' uniqueness
        ]);
        if ($validator->passes()) {
            if (!empty($req->starts_at)) {
                $now = Carbon::now();
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $req->starts_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ["starts_at" => "Starts date should not be less than the current date"],

                    ]);
                }
            }

            if (!empty($req->starts_at) && !empty($req->ends_at)) {
                $ends_at = Carbon::createFromFormat('Y-m-d H:i:s', $req->ends_at);
                $startAt = Carbon::createFromFormat('Y-m-d H:i:s', $req->starts_at);
                if ($ends_at->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ["ends_at" => "End date should greater than start date"],

                    ]);
                }
            }

            $discount = discountModel::find($req->id);
            $discount->code = $req->code;
            $discount->name = $req->name;
            $discount->description = $req->code;
            $discount->max_uses = $req->maxuses;
            $discount->max_uses_user = $req->maxuser;
            $discount->type = $req->dtype;
            $discount->dicount_amount = $req->dvalue;
            $discount->minimum_amount = $req->minimum_amount;
            $discount->status = $req->status;
            $discount->starts_at = $req->starts_at;
            $discount->ends_at = $req->ends_at;
            $discount->update();
            session()->flash('success', 'Successfully discount created');
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
    public function delete($id)
    {
        $discount = discountModel::find($id);
        $discount->delete();
        session()->flash('success', 'Successfully item deleted');
            return response()->json([
                'status' => true,
                'message' => "Item deleted",

            ]);

    }
}
