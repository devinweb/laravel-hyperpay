<?php

namespace Devinweb\LaravelHyperpay\Traits;

trait ManageUserTransactions
{
    /**
     * Get all of the subscriptions for the Stripe model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        $transaction_model = config('hyperpay.transaction_model');

        return $this->hasMany($transaction_model, $this->getForeignKey())->orderBy('created_at', 'desc');
    }
}
