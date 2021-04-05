<?php

namespace Devinweb\LaravelHyperpay\Support;

use Devinweb\LaravelHyperpay\Models\Transaction;
use Illuminate\Support\Arr;

class TransactionBuilder
{
    /**
     * The model that is transacting.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $owner;

    /**
     * Create a new transaction builder instance.
     *
     * @param  mixed  $owner
     * @return void
     */
    public function __construct($owner = null)
    {
        $this->owner = $owner;
    }

    /**
     *@param  array  $transactionData
     *@return \Deviwnweb\LaravelHyperpay\Models\Transaction
     */
    public function create(array $transactionData)
    {
        $this->currentUserCleanOldPendingTransaction();

        $transaction = $this->owner->transactions()->create([
            'id' => Arr::get($transactionData, 'merchantTransactionId'),
            'user_id' => $this->owner->id,
            'checkout_id' => Arr::get($transactionData, 'id'),
            'status' => 'pending',
            'amount' => Arr::get($transactionData, 'amount'),
            'currency' => Arr::get($transactionData, 'currency'),
            'brand' => $this->getBrand($transactionData['entityId']),
            'data' => Arr::get($transactionData, 'result'),
            'trackable_data' => Arr::get($transactionData, 'trackable_data'),
        ]);

        return $transaction;
    }

    public function findByIdOrCheckoutId($id)
    {
        $transaction_model = config('hyperpay.transaction_model');
        $transaction = app($transaction_model)->whereId($id)->orWhere('checkout_id', $id)->first();

        return $transaction;
    }

    protected function getBrand($entityId)
    {
        if ($entityId == config('hyperpay.entityIdMada')) {
            return 'mada';
        }

        return 'default';
    }

    protected function currentUserCleanOldPendingTransaction()
    {
        $transaction = $this->owner->transactions()->where('status', 'pending')->first();
        if ($transaction) {
            $transaction->delete();
        }
    }
}
