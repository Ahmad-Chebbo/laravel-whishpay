<?php

namespace AhmadChebbo\WhishPay\Pipelines;

use Closure;
use Illuminate\Support\Facades\Log;
use AhmadChebbo\WhishPay\DTOs\PaymentDTO;

class PaymentLoggingPipeline
{
    public function handle(PaymentDTO $dto, Closure $next)
    {
        try {

            Log::info('Whish payment attempt', [
                'external_id' => $dto->externalId,
                'amount' => $dto->amount,
                'currency' => $dto->currency->value,
                'invoice' => $dto->invoice,
            ]);
        } catch (\Throwable $e) {
            // NEVER block payment because of logging failure
        }

        return $next($dto);
    }
}
