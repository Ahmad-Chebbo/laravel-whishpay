<?php

namespace AhmadChebbo\WhishPay\Http\Clients;

use Illuminate\Support\Facades\Http;
use AhmadChebbo\WhishPay\Contracts\HttpClientContract;
use AhmadChebbo\WhishPay\Exceptions\WhishException;
use AhmadChebbo\WhishPay\Support\IdempotencyKey;
use Illuminate\Support\Facades\Log;

class WhishHttpClient implements HttpClientContract
{
    protected function client()
    {
        $mode = config('whish-pay.mode', 'sandbox'); // default
        $baseUrl = config('whish-pay.sandbox_url');

        // If the mode is 'production' try to override the base URL
        if ($mode === 'production' && config('whish-pay.production_url')) {
            $baseUrl = config('whish-pay.production_url');
        } 

        return Http::baseUrl($baseUrl)
            ->retry(3, 200)
            ->withHeaders([
                'channel' => config('whish-pay.channel'),
                'secret' => config('whish-pay.secret'),
                'websiteUrl' => config('whish-pay.website_url'),
                'User-Agent' => config('whish-pay.user_agent'),
                // 'Idempotency-Key' => IdempotencyKey::generate($data['externalId']),
            ]);
    }

    public function get(string $uri): array
    {
        return $this->handle(
            $this->client()->get($uri)
        );
    }

    public function post(string $uri, array $data): array
    {
        return $this->handle(
            $this->client()->post($uri, $data)
        );
    }

    protected function handle($response): array
    {
        if (!$response->successful()) {
            throw new WhishException('API request failed.');
        }

        $json = $response->json();

        if (!($json['status'] ?? false)) {

            Log::error('Whish API response error', [
                'response' => $response->body(),
                'status_code' => $response->status(),
            ]);

            throw new WhishException(
                $json['dialog']['message'] ?? 'Whish error.'
            );
        }

        return $json['data'] ?? [];
    }
}
