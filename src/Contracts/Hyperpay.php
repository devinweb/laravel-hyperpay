<?php

namespace Devinweb\LaravelHyperpay\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Hyperpay
{
    public function checkout(array $trackable_data, Model $user, $amount, $brand, Request $request);

    public function paymentStatus(string $resourcePath, string $brand);
}
