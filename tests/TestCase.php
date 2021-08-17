<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Devinweb\LaravelHyperpay\LaravelHyperpayServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Devinweb\LaravelHyperpay\Tests\Fixtures\User;

abstract class TestCase extends OrchestraTestCase
{
  use RefreshDatabase;

  public function setUp(): void
  {

    parent::setUp();
    // additional setup
  }

  protected function defineDatabaseMigrations()
  {
    $this->loadLaravelMigrations();
  }
    
  protected function getPackageProviders($app)
  {
    return [LaravelHyperpayServiceProvider::class];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
  }


  protected function createCustomer($description = 'imad', array $options = []): User
    {
        return User::create(array_merge([
            'email' => "{$description}@hyperpay-laravel.com",
            'name' => 'Darbaoui imad',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ], $options));
    }
}
