<?php
namespace Devinweb\LaravelHyperpay\Support;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

final class HttpClient
{
    protected $gateway_url = 'https://test.oppwa.com';

    /**
     * @var Config $config
     */
    protected $config = [];


    /**
     * @var User $user
     */

    protected $user;
    
    
    /**
     * @var string $path
     */

    protected $path;



    /**
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(GuzzleClient $client, string $path, array $config)
    {
        $this->client = $client;
        $this->config = $config;
        $this->path = $path;
        if (!Config('hyperpay.sandboxMode')) {
            $this->gateway_url = 'https://oppwa.com';
        }
    }

    /**
     *
     *
     *
     */
    public function post(array $parameters)
    {
        try {
            $response = $this->client->post($this->gateway_url . $this->path, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Authorization' => 'Bearer ' . $this->config['access_token'],
                ]
            ]);
            return (new HttpResponse($response))->prepareCheckout($parameters, $this->gateway_url);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            return (new HttpResponse($response))->finalResponse();
        }
    }
    
    /**
     *
     *
     */
    public function get($parameters, Model $transaction)
    {
        try {
            Log::info(['gateway_url' => $this->gateway_url]);
            $response = $this->client->get($this->gateway_url . $this->path, [
                'query' => $parameters,
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->config['access_token'],
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                    ]
            ]);
            

            return (new HttpResponse($response, $transaction))->paymentStatus();
        } catch (RequestException $e) {
            $response = $e->getResponse();
            return (new HttpResponse($response))->finalResponse();
        }
    }
}
