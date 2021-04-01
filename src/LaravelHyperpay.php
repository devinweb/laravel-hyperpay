<?php

namespace Devinweb\LaravelHyperpay;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Devinweb\LaravelHyperpay\Contracts\Hyperpay;
use Devinweb\LaravelHyperpay\Support\HttpClient;
use Devinweb\LaravelHyperpay\Support\HttpParameters;
use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

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
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
        $this->config = config('hyperpay');
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
    public function checkout(Model $user, $amount, $brand, Request $request)
    {
        $this->brand = $brand;

        if (strtolower($this->brand) == 'mada') {
            $this->mada();
        }

        $checkout_data = $this->prepareCheckout($user, $amount, $request);
        
        $redirect_url = "https://03087d1495a5.ngrok.io". config('hyperpay.redirect_url');
        
        return array_merge($checkout_data, [
            "shopperResultUrl" => $redirect_url
        ]);
    }

    /**
     *
     *
     */
    protected function prepareCheckout(Model $user, $amount, $request)
    {
        $this->token = $this->generateToken();
        $this->config['merchantTransactionId'] = $this->token;
        $this->config['userAgent'] = $request->server('HTTP_USER_AGENT');
        $parameters = (new HttpParameters())->postParams($amount, $user, $this->config, $this->billing);
        $response =  (new HttpClient($this->client, '/v1/checkouts', $this->config))->post($parameters);
        $this->createTransationInDB($response, $user);
        return $response;
    }

    /**
     *
     *
     */
    public function paymentStatus(string $resourcePath, string $checkout_id)
    {
        $parameters = (new HttpParameters())->getParams($checkout_id);
        return (new HttpClient($this->client, $resourcePath, $this->config))->get($parameters);
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

    /**
     *
     *
     */
    protected function createTransationInDB($data, Model $user)
    {
        if (Arr::has($data, 'result')) {
            if (Arr::get($data, 'result.code') == '000.200.100') {
                $transaction = config('hyperpay.transaction_model');
                app($transaction)->create([
                    "id" => Arr::get($data, 'merchantTransactionId'),
                    "user_id" => $user->id,
                    "checkout_id" => Arr::get($data, 'id'),
                    "status" => Arr::get($data, 'result'),
                    "amount" => Arr::get($data, 'amount'),
                    "currency" => Arr::get($data, 'currency'),
                    "brand" => $this->getBrand($data['entityId'])
                ]);
            }
        }
    }

    protected function getBrand($entityId)
    {
        if ($entityId == config('entityIdMada')) {
            return 'mada';
        }
        return "default";
    }
}
