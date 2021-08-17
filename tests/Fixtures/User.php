<?php

namespace Devinweb\LaravelHyperpay\Tests\Fixtures;

use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;
use Illuminate\Foundation\Auth\User as Model;

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
