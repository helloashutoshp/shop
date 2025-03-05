<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class paymentController extends Controller
{
    public function index()
    {
        return view('payment');
    }

    public function catch(Request $request)
    {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRETKEY'));
        $amount = $request->input('price');
        $payment = $stripe->charges->create([
            'amount' => $amount * 100, // Convert to smallest currency unit
            'currency' => 'INR',
            'source' => $request->input('stripe_id'),
            'description' => 'Order Payment',
        ]);
        return back()->with('success','Payment Successful');
    }
}
