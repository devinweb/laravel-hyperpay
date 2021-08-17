<?php

namespace Devinweb\LaravelHyperpay\Tests\Fixtures;

use GuzzleHttp\Psr7\Response;

class HyperPayResponses
{
    public function paymentStatusFailResponse($checkoutId)
    {
        $response = json_encode([
            'result'=>[
                'code'=>'200.300.404',
                'description'=>'invalid or missing parameter - (opp) No payment session found for the requested id - are you mixing test/live servers or have you paid more than 30min ago?',
            ],
            'body' => [
                'message'=>'invalid or missing parameter - (opp) No payment session found for the requested id - are you mixing test/live servers or have you paid more than 30min ago?',
            ],
            'buildNumber'=>'9120ea88d7333eef72b6478cb82dfc3848056187@2021-08-16 03:42:29 +0000',
            'timestamp'=>'2021-08-16 21:21:34+0000',
            'ndc'=>$checkoutId,
        ], true);

        return new Response(200, ['Content-Type' => 'application/json'], $response);
    }

    public function paymentStatusSuccessResponse()
    {
        $response = json_encode(
            [
                'id' => '8ac7a4a27b4c7867017b510d33dd7188',
                'paymentType' => 'DB',
                'paymentBrand' => 'VISA',
                'amount' => '92.00',
                'currency' => 'EUR',
                'descriptor' => '3761.2763.7448 OPP_Channel',
                'result'=>[
                    'code' => '000.100.110',
                    'description' => "Request successfully processed in 'Merchant in Integrator Test Mode'",
                ],
                'resultDetails' =>[
                    'clearingInstituteName' => 'Elavon-euroconex_UK_Test',
                ],
                'card'=>[
                    'bin' => '411111',
                    'last4Digits' => '1111',
                    'holder' => 'imad',
                    'expiryMonth' => '09',
                    'expiryYear' => '2021',
                    'issuer' => [
                        'bank' => 'JPMORGAN CHASE BANK, N.A.',
                        'website' => 'HTTP://WWW.JPMORGANCHASE.COM',
                        'phone' => '1-212-270-6000',
                    ],
                    'type' => 'CREDIT',
                    'country' => 'US',
                    'maxPanLength' => '16',
                    'regulatedFlag' => 'Y',
                ],
                'customer' => [
                    'ip' => '160.177.231.229',
                    'ipCountry' => 'MA',
                ],
                'threeDSecure' => [
                    'eci' => '00',
                    'xid' => 'CAACCVVUlwCXUyhQNlSXAAAAAAA=',
                    'paRes' => 'pares',
                ],
                'customParameters' =>[
                    'SHOPPER_EndToEndIdentity' => 'c7ad8e85531539f63ad3485ef50d78cc774a78845425c2ef8008624e191e87d6',
                    'CTPE_DESCRIPTOR_TEMPLATE' => '',
                    'FEEDZAI_DATA_FEED' => 'true',
                ],
                'risk' =>[
                    'score' => '100',
                ],
                'buildNumber' => '9120ea88d7333eef72b6478cb82dfc3848056187@2021-08-16 03:42:29 +0000',
                'timestamp' => '2021-08-16 22:20:30.285+0000',
                'ndc' => '1A30CA9E1E3AED50C924D81E5708EDE6.uat01-vm-tx03',
            ],
            true
        );

        return new Response(200, ['Content-Type' => 'application/json'], $response);
    }

    public function prepareCheckoutResponse()
    {
        $response = json_encode([
            'result'=>[
                'code'=>'000.200.100',
                'description'=>'successfully created checkout',
            ],
            'buildNumber'=>'9120ea88d7333eef72b6478cb82dfc3848056187@2021-08-16 03:42:29 +0000',
            'timestamp'=>'2021-08-16 17:34:10+0000',
            'ndc'=>'7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03',
            'id'=>'7A987D6B411CE700AFFCA163D679703E.uat01-vm-tx03',
        ], true);

        return new Response(200, ['Content-Type' => 'application/json'], $response);
    }
}
