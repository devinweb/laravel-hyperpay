<?php

namespace Devinweb\LaravelHyperpay\Http\Controllers;

use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use Illuminate\Routing\Controller;
use Devinweb\LaravelHyperpay\Http\Middleware\VerifyRedirectUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Billing\HyperPayBilling;
use Illuminate\Support\Str;

class HyperPayPaymentController extends Controller
{
    /**
     * Create a new PaymentController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware(VerifyRedirectUrl::class);
    }

    /**
     * Display the form to gather additional payment verification for the given payment.
     *
     * @param  string  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($token_id)
    {
        return view('hyperpay::payment', [
            'redirect' => url(request('redirect', '/')),
        ]);
    }
    
    /**
     *
     *
     *
     *
     */
    public function payment(Request $request)
    {
        $model = config('hyperpay.model');
        $user = app($model)->first();
        $amount = 1;
        $brand = $request->brand;
        $trackable_data = [
            'product' => 'teams',
            'product_id'=> Str::random('64'),
        ];
        return LaravelHyperpay::addBilling(new HyperPayBilling($request))->checkout($trackable_data, $user, $amount, $brand, $request);
    }


    /**
     *
     *
     *
     */
    public function paymentStatus(Request $request)
    {
        $resourcePath = $request->get('resourcePath');
        $checkout_id = $request->get('id');
        return LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);
    }

    /**
     *
     *
     *
     */
    public function checkout()
    {
        return view('hyperpay::checkout');
    }

    /**
     *
     *
     *
     */
    public function finalize(Request $request)
    {
        return view('hyperpay::finalize', [
            "id" => $request->get('id'),
            "resourcePath" => $request->get('resourcePath')
        ]);
    }
}
