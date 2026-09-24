<?php

namespace AhmadChebbo\WhishPay\Pipelines;

use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\Exceptions\WhishException;
use Closure;

class FraudDetectionPipeline
{
    public function handle(PaymentDTO $dto, Closure $next)
    {
        // Example rule: prevent extremely large payments
        if ($dto->amount > config('whish-pay.fraud.max_single_payment', 10000)) {
            throw new WhishException('Payment flagged by fraud detection.');
        }

        return $next($dto);
    }
}
