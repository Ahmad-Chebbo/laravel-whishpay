<?php

namespace AhmadChebbo\WhishPay\Facades;

use AhmadChebbo\WhishPay\Contracts\WhishPayContract;
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
        return WhishPayContract::class;
    }
}
