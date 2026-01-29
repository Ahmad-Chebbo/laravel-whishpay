<?php

namespace AhmadChebbo\WhishPay\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static float getBalance()
 * @method static string createPayment(PaymentResponseDTO $params)
 * @method static StatusResponseDTO getStatus(string $currency, int $externalId)
 */
class WhishPay extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \AhmadChebbo\WhishPay\Contracts\WhishPayContract::class;
    }
}