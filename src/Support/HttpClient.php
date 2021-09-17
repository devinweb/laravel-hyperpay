<?php

namespace Devinweb\LaravelHyperpay\Support;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

final class HttpClient
{
    /**
     * @var Config
     */
    protected $config = [];

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
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
    }

    /**
     * Create a post sever-to-server request.
     *
     * @param  array  $parameters
     * @return Response
     */
    public function post(array $parameters): Response
    {
        try {
            $response = $this->client->post($this->path, [
                'form_params' => $parameters,
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                    'Authorization' => 'Bearer '.$this->config['access_token'],
                ],
            ]);

            return $response;
        } catch (RequestException $e) {
            $response = $e->getResponse();

            return $response;
        }
    }

    /**
     * Create a get request to hyperpay used to check the status.
     *
     * @param  array  $parameters
     * @return Response
     */
    public function get(array $parameters): Response
    {
        try {
            $response = $this->client->get($this->path, [
                'query' => $parameters,
                'headers' => [
                    'Authorization' => 'Bearer '.$this->config['access_token'],
                    'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                ],
            ]);

            return $response;
        } catch (RequestException $e) {
            $response = $e->getResponse();

            return $response;
        }
    }
}
