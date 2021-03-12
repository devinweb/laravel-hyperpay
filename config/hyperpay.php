<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'payment_mode' => env('PAYMENT_MODE', 'staging'),
    "entityIdMada" => env('ENTITY_ID_MADA'),
    "entityId" => env('ENTITY_ID'),
    "access_token" => env('ACCESS_TOKEN'),
    "currency" => 'SAR'
];
