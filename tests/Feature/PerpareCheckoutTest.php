<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Devinweb\LaravelHyperpay\LaravelHyperpay;
use Devinweb\LaravelHyperpay\LaravelHyperpayServiceProvider;
use Mockery;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Request as GuzzleHttpRequest;
use GuzzleHttp\Exception\RequestException;


class PrepareCheckoutTest extends TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    $response = json_encode([

      "result"=>[
        "code"=>"000.200.100",
        "description"=>"successfully created checkout"
      ],
      "buildNumber"=>"9120ea88d7333eef72b6478cb82dfc3848056187@2021-08-16 03:42:29 +0000",
      "timestamp"=>"2021-08-16 17:34:10+0000",
      "ndc"=>"7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03",
      "id"=>"7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03"
    ]
  , true);
    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], $response),
      new Response(202, ['X-Foo' => 'Bar'], 'Hello, World'),
      new RequestException('Error Communicating with Server', new GuzzleHttpRequest('GET', 'test'))
    ]);

    $handlerStack = HandlerStack::create($mock);
    $this->client = new Client(['handler' => $handlerStack]);
    $this->hyperpay = new LaravelHyperpay($this->client);
    $this->faker = \Faker\Factory::create();
    $this->request = new Request([], [], [], [], [], [], '');
    config()->set("hyperpay.sandboxMode", true);
    config()->set("hyperpay.entityIdMada", "28df736b-5e0b-4d99-a4a9-e701e550a9c1");
    config()->set("hyperpay.entityId", "44b3c638-f5dc-4b44-99c0-bdad68919f8b");
    config()->set("hyperpay.access_token", "467f7720-7fda-4bd6-b4c7-6ac790792487");
    $this->user = $this->createCustomer();
  }
  
  /** @test */
  public function user_can_prepare_checkout_for_default_brands_visa_master() 
  {
    $trackable_data = [
      'product_id'=>  $this->faker->uuid,
      'product_type' => "t-shirt-{$this->faker->colorName}"
    ];
    $user = $this->user;
    $amount = 10;
    $brand = 'VISA';
    $response = $this->hyperpay->checkout($trackable_data, $user, $amount, $brand, $this->request);


    $this->assertEquals($response->getData()->message, 'successfully created checkout');
    $this->assertEquals($response->getData()->status, 200);
    $this->assertEquals($response->getData()->script_url, 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03');
    $this->assertEquals($response->getData()->shopperResultUrl, config()->get('hyperpay.redirect_url'));


    $this->assertDatabaseHas('transactions', [
      'user_id' => $user->id,
      'checkout_id' => "7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03",
      'status' => "pending",
      'amount' => "10.0",
      'brand' => 'default'
    ]);

    $pending_transaction = $user->transactions()->pending()->first();

    $this->assertEquals($pending_transaction->trackable_data, array_merge($trackable_data, ["amount" =>$amount]));

  }


  /** @test */
  public function user_can_prepare_checkout_for_mada_brand() 
  {
    $trackable_data = [
      'product_id'=>  $this->faker->uuid,
      'product_type' => "t-shirt-{$this->faker->colorName}"
    ];
    $user = $this->user;
    $amount = 10;
    $brand = 'MADA';
    $response = $this->hyperpay->checkout($trackable_data, $user, $amount, $brand, $this->request);


    $this->assertEquals($response->getData()->message, 'successfully created checkout');
    $this->assertEquals($response->getData()->status, 200);
    $this->assertEquals($response->getData()->script_url, 'https://test.oppwa.com/v1/paymentWidgets.js?checkoutId=7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03');
    $this->assertEquals($response->getData()->shopperResultUrl, config()->get('hyperpay.redirect_url'));


    $this->assertDatabaseHas('transactions', [
      'user_id' => $user->id,
      'checkout_id' => "7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03",
      'status' => "pending",
      'amount' => "10.0",
      'brand' => 'mada'
    ]);

    $pending_transaction = $user->transactions()->pending()->first();

    $this->assertEquals($pending_transaction->trackable_data, array_merge($trackable_data, ["amount" =>$amount]));

  }
}
