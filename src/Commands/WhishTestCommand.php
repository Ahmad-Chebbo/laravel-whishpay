<?php

namespace AhmadChebbo\WhishPay\Commands;

use AhmadChebbo\WhishPay\Contracts\WhishPayContract;
use Illuminate\Console\Command;
use Throwable;

class WhishTestCommand extends Command
{
    protected $signature = 'whish:test';

    protected $description = 'Test Whish Pay API connectivity and credentials';

    public function handle(WhishPayContract $whish)
    {
        $this->info('Testing Whish API connection...');

        try {

            $balance = $whish->getBalance();

            $this->info('✅ Connection successful!');
            $this->line('Balance: '.$balance);

            return self::SUCCESS;
        } catch (Throwable $e) {

            $this->error('❌ Connection failed.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }
    }
}
