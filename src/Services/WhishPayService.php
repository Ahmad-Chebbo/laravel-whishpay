<?php

namespace AhmadChebbo\WhishPay\Services;

use AhmadChebbo\WhishPay\Contracts\HttpClientContract;
use AhmadChebbo\WhishPay\Contracts\WhishPayContract;
use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\DTOs\PaymentResponseDTO;
use AhmadChebbo\WhishPay\DTOs\StatusResponseDTO;
use AhmadChebbo\WhishPay\Exceptions\WhishException;
use Illuminate\Pipeline\Pipeline;

class WhishPayService implements WhishPayContract
{
    public function __construct(
        protected HttpClientContract $client,
        protected Pipeline $pipeline
    ) {}

    /**
     * Get account balance
     */
    public function getBalance(): float
    {
        $data = $this->client->get('/payment/account/balance');

        return (float) ($data['balance'] ?? 0);
    }

    /**
     * Create payment
     */
    public function createPayment(PaymentDTO $dto): PaymentResponseDTO
    {
        if (config('whish-pay.enable_pipeline')) {
            // Pass DTO through validation pipeline
            $dto = $this->pipeline
                ->send($dto)
                ->through(config('whish-pay.pipelines'))
                ->thenReturn();
        }

        $data = $this->client->post(
            '/payment/whish',
            $dto->toArray()
        );

        if (! isset($data['collectUrl'])) {
            throw new WhishException('Invalid payment response from Whish.');
        }

        return new PaymentResponseDTO(
            collectUrl: $data['collectUrl']
        );
    }

    /**
     * Get payment status
     */
    public function getStatus(string $currency, int $externalId): StatusResponseDTO
    {
        $data = $this->client->post(
            '/payment/collect/status',
            [
                'currency' => $currency,
                'externalId' => $externalId,
            ]
        );

        if (! isset($data['collectStatus'])) {
            throw new WhishException('Invalid status response.');
        }

        return new StatusResponseDTO(
            status: $data['collectStatus'],
            payerPhone: $data['payerPhoneNumber'] ?? null
        );
    }
}
