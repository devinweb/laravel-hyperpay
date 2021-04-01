<?php

namespace Devinweb\LaravelHyperpay\Http\Controllers;

use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use Illuminate\Routing\Controller;
use Devinweb\LaravelHyperpay\Http\Middleware\VerifyRedirectUrl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info(['hook' => $request->all()]);
    }
}
