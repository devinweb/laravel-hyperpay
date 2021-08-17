<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Devinweb\LaravelHyperpay\LaravelHyperpayServiceProvider;


class UserTest extends TestCase
{

    public function test_user_can_get_pending_transactions() 
    {
      $user = $this->createCustomer();
      
      $pending_transactions = $user->transactions()->pending()->get();

      $this->assertEquals([], $pending_transactions->toArray());
    }
}
