<?php

namespace Devinweb\LaravelHyperpay;

use Devinweb\LaravelHyperpay\Console\BillingCommand;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Route;

class LaravelHyperpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerResources();
        $this->registerPublishing();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
                'prefix' => 'hyperpay',
                'namespace' => 'Devinweb\LaravelHyperpay\Http\Controllers',
                'as' => 'hyperpay.',
            ], function () {
                $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
            });
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        $this->loadJsonTranslationsFrom(__DIR__.'/../resources/lang');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'hyperpay');
    }


    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/hyperpay.php' => config_path('hyperpay.php'),
            ], 'hyperpay-config');


            if (! class_exists('CreateTransactionsTable')) {
                $this->publishes([
                    __DIR__ . '/../database/migrations/create_transactions_table.php.stub' => database_path('migrations/' . date('Y_m_d_His', time()) . '_create_transactions_table.php'),
                    ], 'hyperpay-migrations');
            }

            $this->commands([
                BillingCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/hyperpay.php', 'hyperpay');

        // Register the main class to use with the facade
        $this->app->singleton('laravelHyperpay', function () {
            return new LaravelHyperpay(new GuzzleClient());
        });
    }
}
