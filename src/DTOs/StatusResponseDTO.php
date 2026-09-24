<?php

namespace AhmadChebbo\WhishPay\DTOs;

class StatusResponseDTO
{
    public function __construct(
        public readonly string $status,
        public readonly ?string $payerPhone
    ) {}
}
