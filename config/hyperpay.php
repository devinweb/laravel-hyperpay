<?php

return [
    'sandboxMode' => env('SANDBOX_MODE', true),

    'entityIdMada' => env('ENTITY_ID_MADA'),

    'entityId' => env('ENTITY_ID'),

    'access_token' => env('ACCESS_TOKEN'),

    'currency' => env('CURRENCY', 'SAR'),

    'redirect_url' => '/hyperpay/finalize',

    'model' => env('PAYMENT_MODEL', class_exists(App\Models\User::class) ? App\Models\User::class : App\User::class),
    /**
     * if you are using multi-tenant you can create a new model like.
     *
     * use Hyn\Tenancy\Traits\UsesTenantConnection;
     * use Devinweb\LaravelHyperpay\Models\Transaction as ModelsTransaction;
     * class Transaction extends ModelsTransaction {
     *
     *  use UsesTenantConnection;
     *
     * }
     */
    'transaction_model' => 'Devinweb\LaravelHyperpay\Models\Transaction',
    'notificationUrl' => '/hyperpay/webhook',
];
