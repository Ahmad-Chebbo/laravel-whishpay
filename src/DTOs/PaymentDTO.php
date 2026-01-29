<?php

namespace AhmadChebbo\WhishPay\DTOs;

use AhmadChebbo\WhishPay\Enums\Currency;
use InvalidArgumentException;

class PaymentDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly Currency $currency,
        public readonly string $invoice,
        public readonly int $externalId,
        public readonly string $successCallbackUrl,
        public readonly string $failureCallbackUrl,
        public readonly string $successRedirectUrl,
        public readonly string $failureRedirectUrl,
    ) {
        if ($amount <= 0) {
            throw new InvalidArgumentException('Amount must be greater than 0.');
        }
    }

    public function toArray(): array
    {
        return [
            'amount' => $this->amount,
            'currency' => $this->currency->value,
            'invoice' => $this->invoice,
            'externalId' => $this->externalId,
            'successCallbackUrl' => $this->successCallbackUrl,
            'failureCallbackUrl' => $this->failureCallbackUrl,
            'successRedirectUrl' => $this->successRedirectUrl,
            'failureRedirectUrl' => $this->failureRedirectUrl,
        ];
    }
}
