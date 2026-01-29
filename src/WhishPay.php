<?php

namespace AhmadChebbo\WhishPay;

use AhmadChebbo\WhishPay\Facades\WhishPay as Facade;

class WhishPay
{
    public static function getBalance()
    {
        return Facade::getBalance();
    }

    public static function createPayment($dto)
    {
        return Facade::createPayment($dto);
    }

    public static function getStatus($currency, $externalId)
    {
        return Facade::getStatus($currency, $externalId);
    }
}