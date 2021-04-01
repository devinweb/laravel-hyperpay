<?php
namespace Devinweb\LaravelHyperpay\Support;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class HttpParameters
{
    /**
     *
     *
     */
    public function postParams($amount, $user, $heyPerPayConfig, $billing): array
    {
        $body = $this->getBodyParameters($amount, $user, $heyPerPayConfig);

        $billing_parameters = $this->getBillingParameters($billing);
       
        $parameters = array_merge($body, $billing_parameters);
        
        return $parameters;
    }

    /**
     *
     *
     */
    public function getParams($checkout_id): array
    {
        $entityId = $this->getEntityId($checkout_id);
        Log::info(['entityId' => $entityId]);
        return ['entityId' => $entityId];
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
    protected function getBodyParameters($amount, Model $user, $heyPerPayConfig): array
    {
        Log::info(['notificationUrl' => 'https://03087d1495a5.ngrok.io/'.$heyPerPayConfig['notificationUrl']]);
        $body_parameters =  [
            'entityId' => $heyPerPayConfig['entityId'],
            'amount' => $amount,
            'currency' => $heyPerPayConfig['currency'],
            'paymentType' => 'DB',
            'merchantTransactionId' => $heyPerPayConfig['merchantTransactionId'],
            'notificationUrl' => 'https://03087d1495a5.ngrok.io/'.$heyPerPayConfig['notificationUrl'],
            // 'shopperResultUrl' => 'https://03087d1495a5.ngrok.io/'.$heyPerPayConfig['redirect_url'],
            'customer.email' => "ceo@ekliel.com",
            'customer.givenName' => "ABDULAZIZ",
            'customer.surname' => "ALGHAMDI",
            'customer.mobile' => '0503008404',
            'customer.browser.userAgent' => $heyPerPayConfig['userAgent'],
            'customer.browser.language' => config('app.locale'),
            // 'billing.city'  => 'tetouan',
            // 'billing.country'  => 'MA',
            // 'billing.street1'  => 'test',
            // 'billing.postcode'  => '93000',
        ];

        if ($heyPerPayConfig['sandboxMode']) {
            $body_parameters['testMode'] = 'EXTERNAL';
        }

        Log::info(['checkout_parameters' => $body_parameters]);
        return $body_parameters;
    }

    /**
     *
     *
     */
    protected function getEntityId($checkout_id)
    {
        $transaction = $this->getTransaction($checkout_id);
        $entityId = config('hyperpay.entityId');
        if ($transaction->brand === 'mada') {
            $entityId = config('hyperpay.entityIdMada');
        }

        return $entityId;
    }

    /**
     *
     *
     */
    protected function getTransaction($checkout_id): Model
    {
        $transaction_model = config('hyperpay.transaction_model');
        $transaction = app($transaction_model)->whereCheckoutId($checkout_id)->first();
        return $transaction;
    }
}
