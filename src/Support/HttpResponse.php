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
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_OK = 200;

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
    * @var array optionsData
    */
    
    protected $optionsData;
    
    
    /**
    * @var Model user
    */
    
    protected $user;
    
    /**
    * @var string script_url
    */
    
    protected $script_url;
    
    /**
    * @var string shopperResultUrl
    */
    
    protected $shopperResultUrl;



    /**
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(Response $response, ?Model $transaction=null, ?array $optionsData = [])
    {
        $this->response = $response;

        $this->transaction = $transaction;

        $this->optionsData = $optionsData;
    }


    public function prepareCheckout()
    {
        $response =  $this->response();
        Log::info(['prepare_checkout' => $response]);
        if ($response['status'] == self::HTTP_OK) {
            (new TransactionBuilder($this->user))->create(array_merge($response, $this->optionsData));
            $response = array_merge($response, [
                'script_url' => $this->script_url,
                'shopperResultUrl' => $this->shopperResultUrl
            ]);
        }

        return response()->json($response, $response['status']);
    }
    
    
    public function paymentStatus()
    {
        $response = $this->response();
        Log::info(['payment_status' => $response]);
        if ($response['status'] == self::HTTP_OK) {
            $this->updateTransaction('success', $response);
        }
        
        if (Arr::has($response, 'body.message')) {
            $this->updateTransaction('cancel', $response);
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Get the body of the response.
     *
     * @return array
     */

    public function body()
    {
        return (array ) json_decode((string) $this->response->getBody(), true);
    }

    /**
     * Get the status code of the response.
     *
     * @return int
     */
    public function status()
    {
        return (int) $this->response->getStatusCode();
    }

    /**
     * Add the script url that used in the front end to generate the payment form
     *
     * @return $this
     */
    public function addScriptUrl($base_url)
    {
        $script_url = $base_url. "/v1/paymentWidgets.js?checkoutId={$this->body()['id']}";
        $this->script_url = $script_url;
        return $this;
    }

    /**
     * Add the shopperResultUrl to the response parameters
     *
     * @return $this
     */
    public function addShopperResultUrl()
    {
        $redirect_url = url('/'). config('hyperpay.redirect_url');

        $this->shopperResultUrl= $redirect_url;

        return $this;
    }

    /**
     * Set the given user to create a transaction to him
     *
     * @return $this
     */
    public function setUser(Model $user)
    {
        $this->user = $user;

        return $this;
    }
    
    /**
     * Get the response final
     *
     * @return \Illuminate\Support\Facades\Response
     */
    protected function response(): array
    {
        $body = $this->body();
        if (Arr::has($body, 'result.code')) {
            $message = Arr::get($body, 'result.description');
            $result = array_merge($body, ['message' => $message]);
            if (preg_match(self::SUCCESS_CODE_PATTERN, Arr::get($body, 'result.code'))
                || preg_match(self::SUCCESS_MANUAL_REVIEW_CODE_PATTERN, Arr::get($body, 'result.code'))
                || Arr::get($body, 'result.code') == '000.200.100'
                ) {
                return array_merge($result, ['status' => self::HTTP_OK]);
            }

            return array_merge($result, ['status' => self::HTTP_UNPROCESSABLE_ENTITY]);
        }
        return array_merge($body, ['message' => __("failed_message"), 'status' => self::HTTP_UNPROCESSABLE_ENTITY]);
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
