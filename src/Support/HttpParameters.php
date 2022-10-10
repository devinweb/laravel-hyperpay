<?php

namespace Devinweb\LaravelHyperpay\Support;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Illuminate\Database\Eloquent\Model;

class HttpParameters
{
    /**
     * Get the parameters that used in the request with hyperpay
     * to initilize the transaction and generate the form.
     *
     * @param  float  $amount
     * @param  Model  $user
     * @param array hyperpay config file with extra data added during the process
     * @param  \Devinweb\LaravelHyperpay\Contracts\BillingInterface  $billing
     * @return array
     */
    public function postParams($amount, $user, $heyPerPayConfig, $billing, $register_user): array
    {
        $body = $this->getBodyParameters($amount, $user, $heyPerPayConfig);
        if ($register_user) {
            $body = array_merge($body, $this->registerPaymentData());
        }
        $billing_parameters = $this->getBillingParameters($billing);

        $parameters = array_merge($body, $billing_parameters);

        return $parameters;
    }

    /**
     * Get the entity id base on the checkout id of its for VISA/MASTER or MADA.
     *
     * @param  string  $checkout_id
     * @return array
     */
    public function getParams($checkout_id): array
    {
        $entityId = $this->getEntityId($checkout_id);

        return ['entityId' => $entityId];
    }

    /**
     * Generate the params that used in the recurring payment
     * @param string $amount
     * @param string $shopperResultUrl
     * @param string $checkout_id That define the entity_id related to the registration id.
     * @return array
     */
    public function postRecurringPayment($amount, $shopperResultUrl, $checkout_id)
    {
        $currency = config('hyperpay.currency');

        return array_merge([
            "standingInstruction.mode"=>"REPEATED",
            "standingInstruction.type" => "RECURRING",
            "standingInstruction.source"=>"MIT",
            "amount"=> $amount,
            "currency" => $currency,
            "paymentType"=>"PA",
            "shopperResultUrl" => $shopperResultUrl
        ], $this->getParams($checkout_id));
    }

    /**
     * Generate the basic user parameters.
     *
     * @param  float  $amount
     * @param  Model  $user
     * @param array hyperpay config file with extra data added during the process
     * @return array
     */
    protected function getBodyParameters($amount, Model $user, $heyPerPayConfig): array
    {
        $body_parameters = [
            'entityId' => $heyPerPayConfig['entityId'],
            'amount' => $amount,
            'currency' => $heyPerPayConfig['currency'],
            'paymentType' => 'DB',
            'merchantTransactionId' => $heyPerPayConfig['merchantTransactionId'],
            'notificationUrl' => url('/').$heyPerPayConfig['notificationUrl'],
            'customer.email' => $user->email,
            'customer.givenName' => $user->name,
            'customer.surname' => $user->name,
            // 'customer.mobile' => '',
        ];

        return $body_parameters;
    }

    /**
     * The init recurring payment params
     * @return array
     */
    protected function registerPaymentData()
    {
        return [
            "standingInstruction.mode"=>"INITIAL",
            "standingInstruction.type" => "RECURRING",
            "standingInstruction.source"=>"CIT",
            "createRegistration"=>true,
        ];
    }

    /**
     * Get the billing data from the Billing class if a user generate one.
     *
     * @param  Devinweb\LaravelHyperpay\Contracts\BillingInterface  $billing
     * @return array
     */
    protected function getBillingParameters($billing): array
    {
        if ($billing instanceof BillingInterface) {
            return $billing->getBillingData();
        }

        return [];
    }

    /**
     * Find the entityId from the transaction if its for MADA of else.
     *
     * @param  string  $id  transaction id
     * @return string
     */
    protected function getEntityId($id)
    {
        $transaction = (new TransactionBuilder())->findByIdOrCheckoutId($id);

        $entityId = config('hyperpay.entityId');

        if ($transaction->brand === 'mada') {
            $entityId = config('hyperpay.entityIdMada');
        }

        if ($transaction->brand === 'applepay') {
            $entityId = config('hyperpay.entityIdApplePay');
        }

        return $entityId;
    }
}
