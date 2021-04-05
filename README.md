# Laravel HyperPay

<a href="https://github.styleci.io/repos/347104704"><img src="https://github.styleci.io/repos/347104704/shield?branch=master" alt="StyleCI Shield"></a>
[![Latest Version on Packagist](https://img.shields.io/packagist/v/devinweb/laravel-hyperpay.svg?style=flat-square)](https://packagist.org/packages/devinweb/laravel-hyperpay)
[![Build Status](https://img.shields.io/travis/devinweb/laravel-hyperpay/master.svg?style=flat-square)](https://travis-ci.org/devinweb/laravel-hyperpay)
[![Total Downloads](https://img.shields.io/packagist/dt/devinweb/laravel-hyperpay.svg?style=flat-square)](https://packagist.org/packages/devinweb/laravel-hyperpay)

Laravel HyperPay provides an easy way to handle all the transactions with different states.

## Installation

You can install the package via composer:

```bash
composer require devinweb/laravel-hyperpay
```

## Database migration

`Laravel-hyperpay` provides a migration to handle its own transaction, don't forget to run the migration after installation

```bash
php artisan migrate
```

If you want to make an update or change the path of the migration, you can publish it using `vendor:publish`

```bash
php artisan vendor:publish --tag="hyperpay-migrations"
```

This migration has a model named `Transaction`, if your app use [multi-tenancy](https://tenancy.dev/docs/hyn/5.5/installation), you can create a new transaction model based on the `hyperpay transaction` model.

```php

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Devinweb\LaravelHyperpay\Models\Transaction as ModelsTransaction;

class Transaction extends ModelsTransaction
{
    use UsesTenantConnection;
}
```

then don't forget the update the `transaction_model` path in the config file `app/hyperpay.php`

```php
<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    //
    "transaction_model" => 'YOUR_NEW_TRANSACTION_MODEL_NAMESPACE',
    //
];

```

## Setup and configuration

You can also publish the config file using

```bash
php artisan vendor:publish --tag="hyperpay-config"
```

After that you can see the file in `app/hyperpay.php`

Before start using `Laravel-hyperpay`, add the `ManageUserTransaction` trait to your User model, this trait provides mutliple tasks to allow you to perform the transaction process from the given user.

```php

use Devinweb\LaravelHyperpay\Traits\ManageUserTransactions;

class User extends Authenticatable
{
    use ManageUserTransactions;
}
```

This package use User model that will be `App\User` or `App\Models\User`, if else you can define your user model using the `.env`

```bash
PAYMENT_MODEL=App\Models\User
```

## HyperPay Keys

Next, you should configure your hyperpay environment in your application's `.env`

```bash
SANDBOX_MODE=true
ACCESS_TOKEN=
ENTITY_ID_MADA=
ENTITY_ID=
# default SAR
CURRENCY=
```

## Creating a transaction

To create a transaction in hyperpay using this package, we need to to prepare the checkout then generate the form.

### Prepare the checkout

```php

use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;

class PaymentController extends  Controller
{
    public function prepareCheckout(Request $request)
    {
        $trackable = [
            'product_id'=> 'bc842310-371f-49d1-b479-ad4b387f6630',
            'product_type' => 't-shirt'
        ];
        $user = User::first();
        $amout = 10;
        $brand = 'VISA' // MASTER OR MADA

        return LaravelHyperpay::checkout($trackable_data, $user, $amount, $brand, $request);
    }
}
```

you can also attach the billing data to the checkout by creating the billing class using this command, all billing files you can find them in `app/Billing` folder.

```bash
php artisan make:billing HyperPayBilling
```

then use

```php
use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;
use App\Billing\HyperPayBilling

LaravelHyperpay::addBilling(new HyperPayBilling())->checkout($trackable_data, $user, $amount, $brand, $request);

```

Next the response returned by the `prepareCheckout` actions

```json
{
    buildNumber: ""
    id: "RANDOME_ID.uat01-vm-tx04"
    message: "successfully created checkout"
    ndc: "RANDOME_ID.uat01-vm-tx04"
    result: {code: "000.200.100", description: "successfully created checkout"}
    script_url: "https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=RANDOME_ID.uat01-vm-tx04"
    shopperResultUrl: "shopperResultUrl"
    status: 200
    timestamp: "2021-04-05 11:16:50+0000"
}
```

To create the payment form you just need to add the following lines of HTML/JavaScript to your page.

```html
<script src="{{ script_url }}"></script>
<form
    action="{{shopperResultUrl}}"
    class="paymentWidgets"
    data-brands="VISA MASTER"
></form>
```

### Payment status

After the transaction process hyperpay redirect the user to the merchant page using the `shopperResultUrl` that you can configure it in the config file `app/hyperpay.php`, by updating the `redirect_url` value.

```php
// app/hyperpay.php

return [
    //
    "redirect_url" => "/hyperpay/finalize",
    //
]

```

After redirection you can use an action the handle the finalize step

```php

use Devinweb\LaravelHyperpay\Facades\LaravelHyperpay;

class PaymentController extends Controller
{
    public function paymentStatus(Request $request)
    {
        $resourcePath = $request->get('resourcePath');
        $checkout_id = $request->get('id');
        return LaravelHyperpay::paymentStatus($resourcePath, $checkout_id);
    }
}

```

### Events handlers

`Laravel-hyperpay` providers two events during the transaction process, after finalize this package fire for successfull transaction

| Event                                              | Description         |
| -------------------------------------------------- | ------------------- |
| Devinweb\LaravelHyperpay\Events\SuccessTransaction | success transaction |
| Devinweb\LaravelHyperpay\Events\FailTransaction    | fail transaction    |

Each event of them contains the `trackable_data` that used to prepare the checkout/

### Testing

```bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email imad@devinweb.com instead of using the issue tracker.

## Credits

-   [darbaoui imad](https://github.com/devinweb)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).

```

```
