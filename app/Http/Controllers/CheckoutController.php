<?php

namespace App\Http\Controllers;
use App\Mail\PurchaseSuccessful;
use Cart;
use Illuminate\Support\Facades\Mail;
use Session;
use Illuminate\Http\Request;
use Stripe\Charge;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function index()
    {
        if(Cart::content()->count() == 0)
        {
            Session::flash('info', 'Your cart is still empty. do some shopping');
            return redirect()->back();
        }
        return view('checkout');
    }

    public function pay()
    {
        //dd(request()->all());
        Stripe::setApiKey('sk_test_51HDFxPD88XNR6Fa7pflRwX1kwyVNjS9wN3A6Pr6djokozRv3sZGcol3aqOeGh6d5dkWK1BAIxQYnfIiZIBYiuOS2005O0BcPTW');
        $charge = Charge::create([
            'amount' => Cart::total() * 100,
            'currency' => 'usd',
            'description' => 'Course practice selling books',
            'source' => request()->stripeToken
        ]);
        //dd('your card was charge successfully');
        Session::flash('success', 'Purchase successfull. wait for our email.');

        Cart::destroy();

        Mail::to(request()->stripeEmail)->send(new PurchaseSuccessful);

        return redirect('/');
    }
}
