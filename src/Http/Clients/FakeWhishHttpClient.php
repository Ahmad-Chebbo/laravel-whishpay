<?php

namespace AhmadChebbo\WhishPay\Http\Clients;

use AhmadChebbo\WhishPay\Contracts\HttpClientContract;

class FakeWhishHttpClient implements HttpClientContract
{
    public function get(string $uri): array
    {
        return [
             'balance' => 1000000
        ];
    }

    public function post(string $uri, array $data): array
    {
        return match ($uri) {

            '/payment/whish' => [
                'collectUrl' => 'https://fake.whish/checkout'
            ],

            '/payment/collect/status' => [
                'collectStatus' => 'success',
                'payerPhoneNumber' => '96100000000'
            ],

            default => []
        };
    }
}
