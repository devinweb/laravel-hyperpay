<?php

namespace Devinweb\LaravelHyperpay\Contracts;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface Hyperpay
{
    /**
     *
     */
    public function checkout(Model $user, $amount, $brand, Request $request);

    /**
     *
     */
    public function paymentStatus(string $resourcePath, string $brand);

    /**
     *
     */
    public function isSuccessfulResponse(array $response) : bool;

    /**
     *
     */
    public function getMessageFromError(array $response) : ?string;

    public function pending();

    public function merchantTransactionId();
}
