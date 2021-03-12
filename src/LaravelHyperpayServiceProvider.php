<?php

namespace Devinweb\LaravelHyperpay;

use Devinweb\LaravelHyperpay\Console\BillingCommand;
use Devinweb\LaravelHyperpay\Contracts\Brand\BrandInterface;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client as GuzzleClient;

class LaravelHyperpayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-hyperpay');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-hyperpay');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('hyperpay.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-hyperpay'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-hyperpay'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-hyperpay'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
            
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
