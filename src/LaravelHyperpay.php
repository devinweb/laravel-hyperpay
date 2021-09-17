<?php

namespace Devinweb\LaravelHyperpay;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Devinweb\LaravelHyperpay\Contracts\Hyperpay;
use Devinweb\LaravelHyperpay\Support\HttpClient;
use Devinweb\LaravelHyperpay\Support\HttpParameters;
use Devinweb\LaravelHyperpay\Support\HttpResponse;
use Devinweb\LaravelHyperpay\Support\TransactionBuilder;
use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class LaravelHyperpay implements Hyperpay
{
    use ManageUserTransactions;

    /** @var GuzzleClient */
    protected $client;

    /**
     * @var BillingInterface
     */
    protected $billing = [];

    /**
     * @var string token
     */
    protected $token;

    /**
     * @var string brand
     */
    protected $brand;

    /**
     * @var string redirect_url
     */
    protected $redirect_url;

    /**
     * @var string hyperpay host
     */
    protected $gateway_url = 'https://test.oppwa.com';

    /**
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
        $this->config = config('hyperpay');
        if (! config('hyperpay.sandboxMode')) {
            $this->gateway_url = 'https://oppwa.com';
        }
    }

    /**
     * Set the mada entityId in the paramaters that used to prepare the checkout.
     *
     * @return void
     */
    public function mada()
    {
        $this->config['entityId'] = config('hyperpay.entityIdMada');
    }

    /**
     * Add billing data to the payment body.
     *
     * @param  BillingInterface  $billing;
     *
     * return $this
     */
    public function addBilling(BillingInterface $billing)
    {
        $this->billing = $billing;

        return $this;
    }

    /**
     * Prepare the checkout.
     *
     * @param  array  $trackable_data
     * @param  Model  $user
     * @param  float  $amount
     * @param  string  $brand
     * @param  Request  $request
     * @return \GuzzleHttp\Psr7\Response
     */
    public function checkout(array $trackable_data, Model $user, $amount, $brand, Request $request)
    {
        $this->brand = $brand;

        if (strtolower($this->brand) == 'mada') {
            $this->mada();
        }

        $trackable_data = array_merge($trackable_data, [
            'amount' => $amount,
        ]);

        return $this->prepareCheckout($user, $trackable_data, $request);
    }

    /**
     * Define the data used to generate a successful
     * response from hyperpay to generate the payment form.
     *
     * @param  Model  $user
     * @param  array  $trackable_data
     * @param  Request  $request
     * @return \GuzzleHttp\Psr7\Response
     */
    protected function prepareCheckout(Model $user, array $trackable_data, $request)
    {
        $this->token = $this->generateToken();
        $this->config['merchantTransactionId'] = $this->token;
        $this->config['userAgent'] = $request->server('HTTP_USER_AGENT');
        $result = (new HttpClient($this->client, $this->gateway_url.'/v1/checkouts', $this->config))->post(
            $parameters = (new HttpParameters())->postParams(Arr::get($trackable_data, 'amount'), $user, $this->config, $this->billing)
        );

        $response = (new HttpResponse($result, null, $parameters))
            ->setUser($user)
            ->setTrackableData($trackable_data)
            ->addScriptUrl($this->gateway_url)
            ->addShopperResultUrl($this->redirect_url)
            ->prepareCheckout();

        return $response;
    }

    /**
     * Check the payment status using $resourcePath and $checkout_id.
     *
     * @param  string  $resourcePath
     * @param  string  $checkout_id
     * @return \GuzzleHttp\Psr7\Response
     */
    public function paymentStatus(string $resourcePath, string $checkout_id)
    {
        $result = (new HttpClient($this->client, $this->gateway_url.$resourcePath, $this->config))->get(
            (new HttpParameters())->getParams($checkout_id),
        );

        $response = (new HttpResponse(
            $result,
            (new TransactionBuilder())->findByIdOrCheckoutId($checkout_id),
        ))->paymentStatus();

        return $response;
    }

    /**
     * Add merchantTransactionId.
     *
     * @param  string  $id
     * @return $this
     */
    public function addMerchantTransactionId($id)
    {
        $this->token = $id;

        return $this;
    }

    /**
     * Add redirection url to the shopper to finalize the payment.
     *
     * @param  string  $url
     * @return $this
     */
    public function addRedirectUrl($url)
    {
        $this->redirect_url = $url;

        return $this;
    }

    /**
     * Generate the token that used as merchantTransactionId to generate the payment form.
     *
     * @return string
     */
    private function generateToken()
    {
        return ($this->token) ?: Str::random('64');
    }
}
