<?php

namespace AhmadChebbo\WhishPay\Support;

class IdempotencyKey
{
    public static function generate(int $externalId): string
    {
        return hash('sha256', $externalId.'|'.microtime());
    }
}
