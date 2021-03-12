<?php
namespace Devinweb\LaravelHyperpay\Support;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Illuminate\Database\Eloquent\Model;

class HttpPayload
{
    public function paramaters($amount, $user, array $heyperpayConfig, $billing)
    {
        $body = $this->getBodyParameters($amount, $user, $heyperpayConfig);

        $billing_parameters = $this->getBillingParameters($billing);
       
        $parameters = array_merge($body, $billing_parameters);
        
        return $parameters;
    }

    /**
     *
     *
     */
    protected function getBillingParameters($billing): array
    {
        if ($billing instanceof BillingInterface) {
            return $billing->getBillingData();
        }
        return [];
    }

    /**
     *
     *
     */
    protected function getBodyParameters($amount, Model $user, $heyperpayConfig): array
    {
        return [
            'entityId' => $heyperpayConfig['entityId'],
            'amount' => $amount,
            'currency' => $heyperpayConfig['currency'],
            'paymentType' => 'DB',
            'merchantTransactionId' => $heyperpayConfig['merchantTransactionId'],
            'customer.email' => $user->email
        ];
    }
}
