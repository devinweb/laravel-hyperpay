<?php
namespace Devinweb\LaravelHyperpay\Support;

use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

final class HttpResponse
{
    /**
    * @var Http status
    */
    const OK = 200;
    const ERROR = 400;


    /**
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }


    public function prepareCheckout($parameters = [], $gateway_url)
    {
        $body = (array )json_decode((string) $this->response->getBody(), true);
        $result =  array_merge($body, $parameters, ['script_url' => $gateway_url."/v1/paymentWidgets.js?checkoutId={$body['id']}"]);
        Log::info(['prepare_checkout' => $body]);
        return $result;
    }
    
    
    public function paymentStatus()
    {
        $body = (array )json_decode((string) $this->response->getBody(), true);
        Log::info(['payment_status' => $body]);
        return $this->response($body);
    }


    public function finalResponse()
    {
        return $this->paymentStatus();
    }
    
    protected function response($body)
    {
        $successCodePattern = '/^(000\.000\.|000\.100\.1|000\.[36])/';
        $successManualReviewCodePattern = '/^(000\.400\.0|000\.400\.100)/';
        if (Arr::has($body, 'result.code')) {
            $message = Arr::get($body, 'result.description');
            $result = array_merge($body, ['message' => $message]);
            if (preg_match($successCodePattern, Arr::get($body, 'result.code')) || preg_match($successManualReviewCodePattern, Arr::get($body, 'result.code'))) {
                return response()->json($result, self::OK);
            }
            return response()->json($result, self::ERROR);
        }
        Log::info(['error' =>__("failed_message")]);
        return response()->json(['message' => __("failed_message")], self::ERROR);
    }
}
