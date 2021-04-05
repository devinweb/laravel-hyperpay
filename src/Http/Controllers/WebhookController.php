<?php

namespace Devinweb\LaravelHyperpay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleWebhook(Request $request)
    {
        Log::info(['hook' => $request->all()]);
    }
}
