<?php

namespace Devinweb\LaravelHyperpay\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Hyperpay
{
    /**
     *
     */
    public function getCheckoutId(Model $user, $amount);

    /**
     *
     */
    public function getPaymentStatus(string $resourcePath, string $brand);

    /**
     *
     */
    public function isSuccessfulResponse(array $response) : bool;

    /**
     *
     */
    public function getMessageFromError(array $response) : ?string;

    public function prepareCheckout(Model $user, $amount);
    public function pending();
    public function merchantTransactionId();
}
