<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Orchestra\Testbench\TestCase;
use Devinweb\LaravelHyperpay\LaravelHyperpayServiceProvider;

class ExampleTest extends TestCase
{

    protected function getPackageProviders($app)
    {
        return [LaravelHyperpayServiceProvider::class];
    }
    
    /** @test */
    public function true_is_true()
    {
        $this->assertTrue(true);
    }
}
