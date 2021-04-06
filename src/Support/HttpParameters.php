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
     * @param float $amount
     * @param Model $user
     * @param array hyperpay config file with extra data added during the process
     * @param \Devinweb\LaravelHyperpay\Contracts\BillingInterface $billing
     *
     * @return array
     */
    public function postParams($amount, $user, $heyPerPayConfig, $billing): array
    {
        $body = $this->getBodyParameters($amount, $user, $heyPerPayConfig);

        $billing_parameters = $this->getBillingParameters($billing);

        $parameters = array_merge($body, $billing_parameters);

        return $parameters;
    }

    /**
     * Get the entity id base on the checkout id of its for VISA/MASTER or MADA.
     *
     * @param string $checkout_id
     *
     * @return array
     */
    public function getParams($checkout_id): array
    {
        $entityId = $this->getEntityId($checkout_id);

        return ['entityId' => $entityId];
    }

    /**
     * Generate the basic user parameters.
     *
     * @param float $amount
     * @param Model $user
     * @param array hyperpay config file with extra data added during the process
     *
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
            'customer.browser.userAgent' => $heyPerPayConfig['userAgent'],
            'customer.browser.language' => config('app.locale'),
        ];

        if ($heyPerPayConfig['sandboxMode']) {
            $body_parameters['testMode'] = 'EXTERNAL';
        }

        return $body_parameters;
    }

    /**
     * Get the billing data from the Billing class if a user generate one.
     *
     * @param Devinweb\LaravelHyperpay\Contracts\BillingInterface $billing
     *
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
     * @param string $id transaction id
     *
     * @return string
     */
    protected function getEntityId($id)
    {
        $transaction = (new TransactionBuilder())->findByIdOrCheckoutId($id);
        $entityId = config('hyperpay.entityId');
        if ($transaction->brand === 'mada') {
            $entityId = config('hyperpay.entityIdMada');
        }

        return $entityId;
    }
}
