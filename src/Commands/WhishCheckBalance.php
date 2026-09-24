<?php

namespace AhmadChebbo\WhishPay\Commands;

use AhmadChebbo\WhishPay\Facades\WhishPay;
use Illuminate\Console\Command;

class WhishCheckBalance extends Command
{
    protected $signature = 'whish:balance {currency=USD : The currency to check the balance for (e.g. USD, LBP, AED)}';

    protected $description = 'Check the current Whish Pay account balance';

    public function handle()
    {
        $this->info('Connecting to Whish Pay...');

        $currency = strtoupper($this->argument('currency') ?? 'USD');

        try {
            $balance = WhishPay::getBalance(); // Assumes current API supports only one balance (total)
            $this->table(['Currency', 'Balance'], [
                [$currency, number_format($balance, 2)],
            ]);
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
