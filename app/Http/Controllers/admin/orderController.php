<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\orderModel;
use App\Models\oredrItem;
use Illuminate\Http\Request;

class orderController extends Controller
{
    public function  orders(Request $req)
    {
        $orders = orderModel::latest();
        $keyword = $req['keyword'];
        if ($keyword) {
            $orders = $orders->where(function ($query) use ($keyword) {
                $query->where('email', 'LIKE', "%{$keyword}%")
                    ->orWhere('id', 'LIKE', "%{$keyword}%")
                    ->orWhere('firstName', 'LIKE', "%{$keyword}%");
            });
        }
        $orders = $orders->paginate(10);
        return view('admin.orders.orderList', ['orders' => $orders]);
    }

    public function orderDetail($id)
    {
        $order = orderModel::select('order.*', 'country.name as countryName')->where('order.id', $id)->leftJoin('country', 'country.id', 'order.country_id')->first();
        $items = oredrItem::where('order_id', $id)->get();
        return view('admin.orders.orderDetail', ['order' => $order, 'items' => $items]);
    }

    public function statusUpdate(Request $request)
    {
        $id = $request->status_id;
        $status = $request->status;
        $order = orderModel::find($id);
        $order->delivery_status =  $status;
        $order->update();
        return redirect()->back();
    }
}
