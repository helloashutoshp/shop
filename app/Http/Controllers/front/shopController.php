<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use App\Models\CountryModel;
use App\Models\Product;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;

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
        //  dd($id);
        // $product = Product::find($id);
        $user_id = Auth::id();
        dd($user_id);
    }

    public function checkOut()
    {
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
        return view('front.checkout',['country'=>$country]);
    }
}
