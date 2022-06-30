<?php

namespace Devinweb\LaravelHyperpay\Support;

use Devinweb\LaravelHyperpay\Events\FailTransaction;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

final class HttpResponse
{
    /**
     * @var Http status
     */
    const HTTP_UNPROCESSABLE_ENTITY = 422;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_OK = 200;

    /**
     * @var Transaction status
     */
    const TRANSACTION_CANCEL = 'cancel';
    const TRANSACTION_SUCCESS = 'success';

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
     * @var array cart|trackable_data
     */
    protected $trackable_data;

    /**
     * Create a new manager instance.
     *
     * @param  \GuzzleHttp\Client as GuzzleClient  $client
     * @return void
     */
    public function __construct(Response $response, ?Model $transaction = null, ?array $optionsData = [])
    {
        $this->response = $response;

        $this->transaction = $transaction;

        $this->optionsData = $optionsData;

        $this->checkResultStatus();
    }

    /**
     * Prepare and perform the checkout to generate an id that used to create a from.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function prepareCheckout()
    {
        $response = $this->response();
        if ($response['status'] == self::HTTP_OK) {
            (new TransactionBuilder($this->user))->create(array_merge($response, array_merge($this->optionsData, ['trackable_data' => $this->trackable_data])));
            $response = array_merge($response, [
                'script_url' => $this->script_url,
                'shopperResultUrl' => $this->shopperResultUrl,
            ]);
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Get the payment status.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function paymentStatus()
    {
        $response = $this->response();
        if ($response['status'] == self::HTTP_OK) {
            $this->updateTransaction('success', $response);
        }

        if ($response['transaction_status'] === self::TRANSACTION_CANCEL) {
            $this->updateTransaction('cancel', $response);
        }

        return response()->json($response, $response['status']);
    }

    /**
     * Get the recurring payment response.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    public function recurringPayment()
    {
        $response = $this->response();

        return  response()->json($response, $response['status']);
    }

    /**
     * Get the body of the response.
     *
     * @return array
     */
    public function body()
    {
        return (array) json_decode((string) $this->response->getBody(), true);
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
     * Add the script url that used in the front end to generate the payment form.
     *
     * @return $this
     */
    public function addScriptUrl($base_url)
    {
        $script_url = $base_url."/v1/paymentWidgets.js?checkoutId={$this->body()['id']}";
        $this->script_url = $script_url;

        return $this;
    }

    /**
     * Add the shopperResultUrl to the response parameters.
     *
     * @return $this
     */
    public function addShopperResultUrl($redirect_url)
    {
        $url = $redirect_url ?: config('hyperpay.redirect_url');

        $this->shopperResultUrl = $url;

        return $this;
    }

    /**
     * Set the given user to create a transaction to him.
     *
     * @return $this
     */
    public function setUser(Model $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set Trackable data to be stored in the Transaction then us it in the event dispatched.
     *
     * @return $this
     */
    public function setTrackableData(array $data)
    {
        $this->trackable_data = $data;

        return $this;
    }

    /**
     * Get the response final.
     *
     * @return \Illuminate\Support\Facades\Response
     */
    protected function response(): array
    {
        $body = $this->body();
        if (Arr::has($body, 'result.code')) {
            $message = Arr::get($body, 'result.description');
            $result = array_merge($body, ['message' => $message, 'transaction_status' => self::TRANSACTION_CANCEL]);
            if (preg_match(self::SUCCESS_CODE_PATTERN, Arr::get($body, 'result.code'))
                || preg_match(self::SUCCESS_MANUAL_REVIEW_CODE_PATTERN, Arr::get($body, 'result.code'))
                || Arr::get($body, 'result.code') == '000.200.100'
                ) {
                return array_merge($result, ['transaction_status' => self::TRANSACTION_SUCCESS, 'status' => self::HTTP_OK]);
            }

            return array_merge($result, ['status' => self::HTTP_UNPROCESSABLE_ENTITY]);
        }

        return array_merge($body, ['message' => __('failed_message'), 'status' => self::HTTP_UNPROCESSABLE_ENTITY]);
    }

    /**
     * Update the transation and dispatch events for both success and fail transaction.
     *
     * @param  int  $status
     * @param  array  $optionData
     * @return void
     */
    protected function updateTransaction($status, array $optionData)
    {
        $hyperpay_data = $optionData;
        $trackable_data = $this->transaction->trackable_data;

        $this->transaction->update([
            'status' => $status,
            'data' =>  $optionData,
        ]);

        if ($status == 'success') {
            event(new SuccessTransaction(array_merge(
                ['hyperpay_data' => $hyperpay_data],
                ['trackable_data' => $trackable_data]
            )));
        }

        if ($status == 'cancel') {
            event(new FailTransaction(array_merge(
                ['hyperpay_data' => $optionData],
                ['trackable_data' => $trackable_data]
            )));
        }
    }

    /**
     * Check the response status get it from hyperpay
     * if bad convert it to the ValidationException.
     *
     * @return mixed
     */
    protected function checkResultStatus()
    {
        if ($this->status() == 400) {
            throw ValidationException::withMessages($this->response());
        }

        return $this;
    }
}
