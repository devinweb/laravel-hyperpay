<?php

namespace Devinweb\LaravelHyperpay;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Devinweb\LaravelHyperpay\Contracts\Brand\BrandInterface;
use Devinweb\LaravelHyperpay\Contracts\Hyperpay;
use Devinweb\LaravelHyperpay\Model\User;
use Devinweb\LaravelHyperpay\Support\HttpPayload;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class LaravelHyperpay implements Hyperpay
{
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
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(GuzzleClient $client)
    {
        $this->client = $client;
        $this->config = config('hyperpay');
        if ($this->config['payment_mode'] == 'staging') {
            $this->config['testMode'] = 'EXTERNAL';
        }
    }

    /**
     *
     *
     */
    public function mada()
    {
        $this->config['entityId'] = config('hyperpay.entityIdMada');
        
        return $this;
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

    public function prepareCheckout(Model $user, $amount)
    {
        return $this->getCheckoutId($user, $amount);
    }

    /**
     *
     *
     */
    public function getCheckoutId(Model $user, $amount)
    {
        $this->token = $this->generateToken();
        $this->config['merchantTransactionId'] = $this->token;
        return (new HttpPayload())->paramaters($amount, $user, $this->config, $this->billing);
    }

    /**
     *
     *
     */
    public function getPaymentStatus(string $resourcePath, string $brand)
    {
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

    public function pending()
    {
    }

    public function merchantTransactionId()
    {
        return $this->token;
    }

    private function generateToken()
    {
        return Str::random('64');
    }
}
