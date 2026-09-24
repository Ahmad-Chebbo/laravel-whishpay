<?php

namespace AhmadChebbo\WhishPay\Pipelines;

use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\Exceptions\WhishException;
use Closure;

class CurrencyValidationPipeline
{
    public function handle(PaymentDTO $dto, Closure $next)
    {
        $allowed = config('whish-pay.allowed_currencies', ['USD', 'LBP', 'AED']);

        if (! in_array($dto->currency->value, $allowed)) {
            throw new WhishException("Unsupported currency: {$dto->currency->value}");
        }

        return $next($dto);
    }
}
