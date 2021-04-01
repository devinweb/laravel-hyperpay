<?php
namespace Devinweb\LaravelHyperpay\Support;

use Devinweb\LaravelHyperpay\Events\FailTransaction;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
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
    * @var string pattern
    */
    const SUCCESS_CODE_PATTERN = '/^(000\.000\.|000\.100\.1|000\.[36])/';
    const SUCCESS_MANUAL_REVIEW_CODE_PATTERN = '/^(000\.400\.0|000\.400\.100)/';


    /**
    * @var TransactionBuilder transaction
    */
    
    protected $transaction;



    /**
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(Response $response, ?Model $transaction=null)
    {
        $this->response = $response;

        $this->transaction = $transaction;
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
        if (Arr::has($body, 'result.code')) {
            $message = Arr::get($body, 'result.description');
            $result = array_merge($body, ['message' => $message]);
            if (preg_match(self::SUCCESS_CODE_PATTERN, Arr::get($body, 'result.code')) || preg_match(self::SUCCESS_MANUAL_REVIEW_CODE_PATTERN, Arr::get($body, 'result.code'))) {
                $this->updateTransaction('success', $result);
                return response()->json($result, self::OK);
            }
            return response()->json($result, self::ERROR);
        }
        $this->updateTransaction('canceled', $body);
        Log::info(['error' =>__("failed_message")]);
        return response()->json(['message' => __("failed_message")], self::ERROR);
    }


    protected function updateTransaction($status, array $optionData)
    {
        if ($status == 'success') {
            event(new SuccessTransaction($optionData));
        }

        if ($status == 'cancel') {
            $optionData = $this->transaction->data;
            event(new FailTransaction($optionData));
        }


        $this->transaction->update([
            "status" => $status,
            "data" =>  $optionData
        ]);
    }
}
