<?php

namespace AhmadChebbo\WhishPay\Pipelines;

use Closure;
use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\Exceptions\WhishException;

class AmountLimitPipeline
{
    public function handle(PaymentDTO $dto, Closure $next)
    {
        $min = config('whish-pay.limits.min', 1);
        $max = config('whish-pay.limits.max', 5000);

        if ($dto->amount < $min) {
            throw new WhishException("Minimum payment amount is {$min}");
        }

        if ($dto->amount > $max) {
            throw new WhishException("Maximum payment amount is {$max}");
        }

        return $next($dto);
    }
}
