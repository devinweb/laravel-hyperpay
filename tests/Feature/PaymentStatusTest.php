<?php

namespace Devinweb\LaravelHyperpay\Tests;

use Devinweb\LaravelHyperpay\Events\FailTransaction;
use Devinweb\LaravelHyperpay\Events\SuccessTransaction;
use Devinweb\LaravelHyperpay\LaravelHyperpay;
use Devinweb\LaravelHyperpay\Models\Transaction;
use Devinweb\LaravelHyperpay\Tests\Fixtures\HyperPayResponses;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

class PaymentStatusTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->faker = \Faker\Factory::create();
        $this->checkoutId = $this->faker->uuid;
        $this->mock = new MockHandler([]);

        $handlerStack = HandlerStack::create($this->mock);
        $this->client = new Client(['handler' => $handlerStack]);
        $this->hyperpay = new LaravelHyperpay($this->client);

        config()->set('hyperpay.sandboxMode', true);
        config()->set('hyperpay.entityIdMada', '28df736b-5e0b-4d99-a4a9-e701e550a9c1');
        config()->set('hyperpay.entityId', '44b3c638-f5dc-4b44-99c0-bdad68919f8b');
        config()->set('hyperpay.access_token', '467f7720-7fda-4bd6-b4c7-6ac790792487');
        $this->user = $this->createCustomer();
    }

    /** @test  */
    public function payment_status_for_fail_transaction()
    {
        Event::fake();

        $transaction = Transaction::create([
            'id' => $this->faker->uuid,
            'user_id' => $this->user->id,
            'checkout_id' => $this->checkoutId,
            'brand' => 'default',
            'status' => 'pending',
            'amount' => '10.0',
            'currency' => 'SAR',
            'trackable_data' => [
                'product_id'=>'bc842310-371f-49d1-b479-ad4b387f6630', 'product_type'=>'t-shirt', 'amount'=>10,
            ],
            'data' => [
                'code'=>'000.200.100',
                'description'=>'successfully created checkout',
            ],
        ]);

        // (new HyperPayResponses())->paymentStatusFailResponse($this->checkoutId),
        //   (new HyperPayResponses())->paymentStatusSuccessResponse(),

        $this->mock->reset();
        $this->mock->append((new HyperPayResponses())->paymentStatusFailResponse($this->checkoutId));

        $resourcePath = "/v1/checkouts/{$this->checkoutId}/payment";
        $response = $this->hyperpay->paymentStatus($resourcePath, $this->checkoutId);

        $transaction = Transaction::whereCheckoutId($this->checkoutId)->first();

        $this->assertArrayHasKey('message', (array) $response->getData());
        $this->assertEquals($response->status(), 422);
        $this->assertEquals($transaction->status, 'cancel');
        Event::assertDispatched(FailTransaction::class);
    }

    /** @test  */
    public function payment_status_for_success_transaction()
    {
        Event::fake();

        $transaction = Transaction::create([
            'id' => $this->faker->uuid,
            'user_id' => $this->user->id,
            'checkout_id' => $this->checkoutId,
            'brand' => 'default',
            'status' => 'pending',
            'amount' => '10.0',
            'currency' => 'SAR',
            'trackable_data' => [
                'product_id'=>'bc842310-371f-49d1-b479-ad4b387f6630',
                'product_type'=>'t-shirt',
                'amount'=>10,
            ],
            'data' => [
                'code'=>'000.200.100',
                'description'=>'successfully created checkout',
            ],
        ]);

        $this->mock->reset();

        $this->mock->append(
            (new HyperPayResponses())->paymentStatusSuccessResponse($this->checkoutId),
        );

        $resourcePath = "/v1/checkouts/{$this->checkoutId}/payment";
        $response = $this->hyperpay->paymentStatus($resourcePath, $this->checkoutId);

        $transaction = Transaction::whereCheckoutId($this->checkoutId)->first();

        $this->assertArrayHasKey('message', (array) $response->getData());
        $this->assertEquals($response->status(), 200);
        $this->assertEquals($transaction->status, 'success');
        Event::assertDispatched(SuccessTransaction::class);
    }
}
