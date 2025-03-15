<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use App\Models\orderModel;
use App\Models\oredrItem;
use App\Models\Product;
use App\Models\shippingCharge;
use App\Models\userShipping;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\Climate\Order;

class shopController extends Controller
{
    public function addtoCart(Request $req)
    {
        $product = Product::with('product_img')->find($req->id);
        // dd($product->qty);
        if ($product) {
            $cartArray = Cart::content();
            $productExit = false;
            foreach ($cartArray as $cart) {
                if ($cart->id == $product->id) {
                    $productExit = true;
                }
            }
            if ($productExit) {
                session()->flash('error', 'Product alreay added in cart');
                return response()->json([
                    'status' => false,
                    'slug' => $product->slug,
                    'message' => "Product alreay added in cart"
                ]);
            } else {
                Cart::add($product->id, $product->title, 1, $product->price, array('productImage' => (!empty($product->product_img)) ? $product->product_img->first() : ''));
                session()->flash('success', 'Product added in cart');
                return response()->json([
                    'status' => true,
                    'slug' => $product->slug,
                    'message' => "Product added in cart"
                ]);
            }
        } else {
            session()->flash('error', 'Product not exit');
            return response()->json([
                'status' => false,
                'message' => "No product found"
            ]);
        }
    }
    public function cart()
    {
        $cart = Cart::content();
        //    dd($cart);   
        return view('front.cart', ['cart' => $cart]);
    }

    public function cartUpdate(Request $request)
    {
        // dd('hello');
        $cartItem = Cart::get($request->id);
        $product_id = $cartItem->id;
        // dd($product_id);
        $product = Product::find($product_id);
        if ($product->trackqty == 'Yes') {
            $productQty = $product->qty;

            if ($request->quantity > $productQty) {
                return response()->json([
                    'status' => false,
                    'message' => 'Reached max quantity'
                ]);
            } else {
                Cart::update($request->id, $request->quantity);

                $subtotal = Cart::subtotal();
                $rowTotal = $cartItem->price * $cartItem->qty;
                return response()->json([
                    'status' => true,
                    'subtotal' => $subtotal,
                    'rowTotal' => $rowTotal,
                    'rowId' => $request->id,
                    'message' => "Cart updated"
                ]);
            }
        } else {
            Cart::update($request->id, $request->quantity);

            $subtotal = Cart::subtotal();
            $rowTotal = $cartItem->price * $cartItem->qty;
            return response()->json([
                'status' => true,
                'subtotal' => $subtotal,
                'rowTotal' => $rowTotal,
                'rowId' => $request->id,
                'message' => "Cart updated"
            ]);
        }
    }

    public function cartDelete(Request $request)
    {
        $rowId = $request->id;
        Cart::remove($rowId);
        return response()->json([
            'status' => true,
            'message' => "Item removed"
        ]);
    }

    public function cartItems(Request $req)
    {
        $id = $req->id;
        $user_id = Auth::id();
    }

    public function checkOut()
    {
        $user = Auth::user();
        $charge = "";
        $total = "";
        $subtotal = "";
        if ($user) {
            $address = userShipping::where('user_id', $user->id)->first();
            if ($address) {
                $country_id = $address->country_id;
                if ($country_id) {
                    $ship = shippingCharge::where('country_id', $country_id)->first();
                    if(!$ship){
                    $ship = shippingCharge::find(9);
                    }
                    $charge = $ship->charge;
                    $total = (float) str_replace(',', '', Cart::subtotal()) + (float) $charge;
                    $subtotal = number_format($total, 2, '.', ',');
                } 
            } else {
                $subtotal = Cart::subtotal();
                $charge = 100;
            }
            if (Cart::count() < 1) {
                return redirect()->route('shop');
            }
            if (Auth::check() == false) {
                if (!(session()->has('url.checkout'))) {
                    session(['url.checkout' => url()->current()]);
                }
                return redirect()->route('userLogin');
            }
            session()->forget('url.checkout');
            $country = CountryModel::all();
            return view('front.checkout', ['country' => $country, 'address' => $address, 'charge' => $charge, 'total' => $subtotal]);
        }
    }

    public function checkOutStore(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'country' => 'required',
            'address' => 'required',
            'appartment' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'mobile' => 'required|digits:10',
        ], [
            'mobile.digits' => 'Please enter a valid phone number',
            'mobile.number' => 'Please enter a valid phone number',
        ]);
        if ($validator->passes()) {
            $user = Auth::user();
            // session()->flash('success', 'Successfully  created');
            userShipping::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'user_id' => $user->id,
                    'firstName' => $req->first_name,
                    'lastName' => $req->last_name,
                    'email' => $req->email,
                    'mobile' => $req->mobile,
                    'country_id' => $req->country,
                    'address' => $req->address,
                    'appartment' => $req->appartment,
                    'city' => $req->city,
                    'state' => $req->state,
                    'zip' => $req->zip,
                ]
            );

            $order = new orderModel();
            if ($req->payment == "p-one") {
                // dd("hey");
                $shipping = $req->shippingCharge;
                $discount = 0;
                //2 → Displays 2 decimal places.
                //. → Uses a dot as the decimal separator.
                //'' → No separator for thousands.
                $subtotal = Cart::subtotal(2, '.', '');
                $total = (float) str_replace(',', '', Cart::subtotal()) + (float) $shipping;
                $grandTotal = number_format($total, 2, '.', '');
                // dd($grandTotal);
                $order->user_id = $user->id;
                $order->shipping = $shipping;
                $order->subtotal = $subtotal;
                $order->grandTotal = $grandTotal;
                $order->firstName = $req->first_name;
                $order->lastName = $req->last_name;
                $order->email = $req->email;
                $order->mobile = $req->mobile;
                $order->country_id  = $req->country;
                $order->address = $req->address;
                $order->appartment = $req->appartment;
                $order->city = $req->city;
                $order->state = $req->state;
                $order->zip = $req->zip;
                $order->note = $req->order_notes;
                $order->save();
            }
            foreach (Cart::content() as $item) {
                // dd($item->id);
                $orderItem = new oredrItem();
                $orderItem->order_id  = $order->id;
                $orderItem->product_id   = $item->id;
                $orderItem->name  = $item->name;
                $orderItem->qty  = $item->qty;
                $orderItem->price  = $item->price;
                $orderItem->total  = $item->qty * $item->price;
                $orderItem->save();
            }

            Cart::Destroy();
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

    public function thankyou()
    {
        return view('front.thankyou');
    }

    public function countryChange(Request $request)
    {
        $id = $request->id;
        $shipping = shippingCharge::where('country_id', $id)->first();
        if (!$shipping) {
            $shipping = shippingCharge::find(9);
        }
        $total = (float) str_replace(',', '', Cart::subtotal()) + (float) $shipping->charge;
        $subtotal = number_format($total, 2, '.', ',');
        // dd($shipping);
        $charge = $shipping->charge;
        return response()->json([
            'status' => true,
            'charge' => $charge,
            'subtotal' => $subtotal
        ]);
    }
}
