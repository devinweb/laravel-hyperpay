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
use Devinweb\LaravelHyperpay\Models\Transaction;
use Illuminate\Support\Facades\Event;
use Devinweb\LaravelHyperpay\Events\FailTransaction;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentStatusTest extends TestCase
{
 

  public function setUp(): void
  {
    parent::setUp();

    $this->faker = \Faker\Factory::create();
    $this->checkoutId = $this->faker->uuid;

    $response  = json_encode([
        "result"=>[
          "code"=>"200.300.404",
          "description"=>"invalid or missing parameter - (opp) No payment session found for the requested id - are you mixing test/live servers or have you paid more than 30min ago?"
        ],
        "body" => [
          "message"=>"invalid or missing parameter - (opp) No payment session found for the requested id - are you mixing test/live servers or have you paid more than 30min ago?"
        ],
        "buildNumber"=>"9120ea88d7333eef72b6478cb82dfc3848056187@2021-08-16 03:42:29 +0000",
        "timestamp"=>"2021-08-16 21:21:34+0000",
        "ndc"=>$this->checkoutId
    ], true);

    $mock = new MockHandler([
      new Response(200, ['Content-Type' => 'application/json'], $response),
    ]);

    $handlerStack = HandlerStack::create($mock);
    $this->client = new Client(['handler' => $handlerStack]);
    $this->hyperpay = new LaravelHyperpay($this->client);
    

    config()->set("hyperpay.sandboxMode", true);
    config()->set("hyperpay.entityIdMada", "28df736b-5e0b-4d99-a4a9-e701e550a9c1");
    config()->set("hyperpay.entityId", "44b3c638-f5dc-4b44-99c0-bdad68919f8b");
    config()->set("hyperpay.access_token", "467f7720-7fda-4bd6-b4c7-6ac790792487");
    $this->user = $this->createCustomer();

  }


  /** @test  */

  public function payment_status_for_fail_transaction() 
  {
        Event::fake();

        $transaction = Transaction::create([
          "id" => $this->faker->uuid,
          'user_id' => $this->user->id,
          'checkout_id' => $this->checkoutId,
          'brand' => 'default',
          'status' => 'pending',
          'amount' => '10.0',
          'currency' => "SAR",
          'trackable_data' => [
            "product_id"=>"bc842310-371f-49d1-b479-ad4b387f6630","product_type"=>"t-shirt","amount"=>10
          ],
          "data" => [
              "code"=>"000.200.100",
              "description"=>"successfully created checkout"
            ],
          ]);
        
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

  }


  // {
  //   "id":"8ac7a4a27b4c7867017b510d33dd7188",
  //   "paymentType":"DB",
  //   "paymentBrand":"VISA",
  //   "amount":"92.00",
  //   "currency":"EUR",
  //   "descriptor":"3761.2763.7448 OPP_Channel",
  //   "result":{
  //     "code":"000.100.110",
  //     "description":"Request successfully processed in 'Merchant in Integrator Test Mode'"
  //   },
  //   "resultDetails":{
  //     "clearingInstituteName":"Elavon-euroconex_UK_Test"
  //   },
  //   "card":{
  //     "bin":"411111",
  //     "last4Digits":"1111",
  //     "holder":"imad",
  //     "expiryMonth":"09",
  //     "expiryYear":"2021",
  //     "issuer":{
  //       "bank":"JPMORGAN CHASE BANK, N.A.",
  //       "website":"HTTP://WWW.JPMORGANCHASE.COM",
  //       "phone":"1-212-270-6000"
  //     },
  //     "type":"CREDIT",
  //     "country":"US",
  //     "maxPanLength":"16",
  //     "regulatedFlag":"Y"
  //   },
  //   "customer":{
  //     "ip":"160.177.231.229",
  //     "ipCountry":"MA"
  //   },
  //   "threeDSecure":{
  //     "eci":"00",
  //     "xid":"CAACCVVUlwCXUyhQNlSXAAAAAAA=",
  //     "paRes":"pares"
  //   },
  //   "customParameters":{
  //     "SHOPPER_EndToEndIdentity":"c7ad8e85531539f63ad3485ef50d78cc774a78845425c2ef8008624e191e87d6",
  //     "CTPE_DESCRIPTOR_TEMPLATE":"",
  //     "FEEDZAI_DATA_FEED":"true"
  //   },
  //   "risk":{
  //     "score":"100"
  //   },
  //   "buildNumber":"9120ea88d7333eef72b6478cb82dfc3848056187@2021-08-16 03:42:29 +0000",
  //   "timestamp":"2021-08-16 22:20:30.285+0000",
  //   "ndc":"1A30CA9E1E3AED50C924D81E5708EDE6.uat01-vm-tx03"
  // }


}