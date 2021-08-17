<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Devinweb\LaravelHyperpay\Contracts\BillingInterface;
use Devinweb\LaravelHyperpay\LaravelHyperpay;
use Devinweb\LaravelHyperpay\Tests\Fixtures\HyperPayResponses;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

class PrepareCheckoutTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        config(["hyperpay.sandboxMode" => true]);
        config(["hyperpay.entityIdMada" => "28df736b-5e0b-4d99-a4a9-e701e550a9c1"]);
        config(["hyperpay.entityId" => "8a8294174d0595bb014d05d82e5b01d2"]);
        config(["hyperpay.access_token" => "OGE4Mjk0MTc0ZDA1OTViYjAxNGQwNWQ4MjllNzAxZDF8OVRuSlBjMm45aA=="]);
        $mock = new MockHandler([
          (new HyperPayResponses())->prepareCheckoutResponse()
        ]);


        $handlerStack = HandlerStack::create($mock);
        $this->client = new Client(['handler' => $handlerStack]);
        $this->hyperpay = new LaravelHyperpay($this->client);
        $this->faker = \Faker\Factory::create();
        $this->request = new Request([], [], [], [], [], [], '');
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


class HyperPayBilling implements BillingInterface
{
    /**
     * Get the billing data.
     *
     * @return array
     */
    public function getBillingData(): array
    {
        return [
            'billing.street1' => 'Wilaya center, Avenue Ali Yaeta, étage 3, n 31, Tétouan',
            'billing.city' => 'TETOUAN',
            'billing.state' => 'TETOUAN',
            'billing.country' => 'MA',
            'billing.postcode' => '35000'
        ];
    }
}
