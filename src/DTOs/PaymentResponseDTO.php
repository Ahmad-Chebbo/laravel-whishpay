<?php

namespace AhmadChebbo\WhishPay\DTOs;

class PaymentResponseDTO
{
    public function __construct(
        public readonly string $collectUrl
    ) {}
}
