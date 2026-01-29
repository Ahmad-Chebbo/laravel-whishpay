<?php

namespace AhmadChebbo\Whishpay\Pipelines;


class ValidatePaymentPayload
{
    public function handle($dto, $next)
    {
        if (!filter_var($dto->successCallbackUrl, FILTER_VALIDATE_URL)) {
            throw new \InvalidArgumentException('Invalid callback URL');
        }

        return $next($dto);
    }
}