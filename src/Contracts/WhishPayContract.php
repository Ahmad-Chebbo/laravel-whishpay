<?php 

namespace AhmadChebbo\WhishPay\Contracts;

use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\DTOs\PaymentResponseDTO;
use AhmadChebbo\WhishPay\DTOs\StatusResponseDTO;


interface WhishPayContract
{
    public function getBalance(): float;

    public function createPayment(PaymentDTO $dto): PaymentResponseDTO;

    public function getStatus(string $currency, int $externalId): StatusResponseDTO;
}
