<?php
namespace Devinweb\LaravelHyperpay\Contracts;

interface BillingInterface
{
    public function getBillingData(): array;
}
