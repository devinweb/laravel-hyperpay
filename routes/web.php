<?php

use Illuminate\Support\Facades\Route;

// Route::get('checkout', 'HyperPayPaymentController@checkout')->name('checkout');
// Route::post('payment', 'HyperPayPaymentController@payment')->name('payment');
// Route::post('payment-status', 'HyperPayPaymentController@paymentStatus')->name('payment-status');
// Route::get('finalize', 'HyperPayPaymentController@finalize')->name('finalize');

// Route::get('payment/{id}', 'HyperPayPaymentController@show')->name('payment');
Route::post('webhook', 'WebhookController@handleWebhook')->name('webhook');
