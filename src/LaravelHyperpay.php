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
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
        if (!config('hyperpay.sandboxMode')) {
            $this->gateway_url = 'https://oppwa.com';
        }
    }

    /**
     *
     *
     */
    public function mada()
    {
        $this->config['entityId'] = config('hyperpay.entityIdMada');
    }

    /**
     * Add billing data to the payment body
     * @param BillingInterface $billing;
     *
     * return $this
     */
    public function addBilling(BillingInterface $billing)
    {
        $this->billing = $billing;
        return $this;
    }


    /**
     *
     *
     */
    public function checkout(array $trackable_data, Model $user, $amount, $brand, Request $request)
    {
        $this->brand = $brand;

        if (strtolower($this->brand) == 'mada') {
            $this->mada();
        }

        $trackable_data = array_merge($trackable_data, [
            'amount' => $amount
        ]);

        return $this->prepareCheckout($user, $trackable_data, $request);
    }

    /**
     *
     *
     */
    protected function prepareCheckout(Model $user, array $trackable_data, $request)
    {
        $this->token = $this->generateToken();
        $this->config['merchantTransactionId'] = $this->token;
        $this->config['userAgent'] = $request->server('HTTP_USER_AGENT');
        $result =  (new HttpClient($this->client, $this->gateway_url.'/v1/checkouts', $this->config))->post(
            $parameters = (new HttpParameters())->postParams(Arr::get($trackable_data, 'amount'), $user, $this->config, $this->billing)
        );

        $response = (new HttpResponse($result, null, $parameters))
            ->setUser($user)
            ->setTrackableData($trackable_data)
            ->addScriptUrl($this->gateway_url)
            ->addShopperResultUrl()
            ->prepareCheckout();

        
        return $response;
    }

    /**
     *
     *
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
     *
     *
     */
    public function isSuccessfulResponse(array $response): bool
    {
        return false;
    }

    /**
     *
     *
     *
     */
    public function getMessageFromError(array $response): ?string
    {
        return '';
    }

    /**
     *
     *
     */
    public function pending()
    {
    }

    /**
     *
     *
     *
     */
    public function merchantTransactionId()
    {
        return $this->token;
    }

    /**
     *
     *
     *
     */
    private function generateToken()
    {
        return Str::random('64');
    }
}
