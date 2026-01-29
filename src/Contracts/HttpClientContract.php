<?php

namespace AhmadChebbo\WhishPay\Contracts;

interface HttpClientContract
{
    public function get(string $uri): array;
    public function post(string $uri, array $data): array;
}
