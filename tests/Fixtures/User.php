<?php

namespace Devinweb\LaravelHyperpay\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Model;
use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;

class User extends Model
{
    use ManageUserTransactions;


    protected $guarded = [];

    /**
     * Get the address to sync with Stripe.
     *
     * @return array|null
     */
    public function stripeAddress()
    {
        return [
            'city' => 'Little Rock',
            'country' => 'US',
            'line1' => 'Main Str. 1',
            'line2' => 'Apartment 5',
            'postal_code' => '72201',
            'state' => 'Arkansas',
        ];
    }
}