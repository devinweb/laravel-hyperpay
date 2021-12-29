<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Devinweb\LaravelHyperpay\LaravelHyperpayServiceProvider;
use Devinweb\LaravelHyperpay\Tests\Fixtures\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        // additional setup
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadLaravelMigrations();
    }

    protected function getPackageProviders($app)
    {
        return [LaravelHyperpayServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {

        // // import the CreatePostsTable class from the migration
        // include_once __DIR__ . '/../database/migrations/create_transactions_table.php.stub';

        // // run the up() method of that migration class
        // (new \CreateTransactionsTable)->up();
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
