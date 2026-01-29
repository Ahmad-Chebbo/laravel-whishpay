<?php

namespace AhmadChebbo\WhishPay\Pipelines;

use Closure;
use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\Exceptions\WhishException;

class CurrencyValidationPipeline
{
    public function handle(PaymentDTO $dto, Closure $next)
    {
        $allowed = config('whish-pay.allowed_currencies', ['USD', 'LBP']);

        if (!in_array($dto->currency->value, $allowed)) {
            throw new WhishException("Unsupported currency: {$dto->currency->value}");
        }

        return $next($dto);
    }
}
