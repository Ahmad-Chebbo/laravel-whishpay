<?php

namespace AhmadChebbo\WhishPay\Commands;

use AhmadChebbo\WhishPay\Contracts\WhishPayContract;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class WhishHealthCommand extends Command
{
    protected $signature = 'whish:health';

    protected $description = 'Run a health check for Whish Pay integration';

    public function handle(WhishPayContract $whish)
    {
        $this->info('Running Whish health checks...');
        $this->newLine();

        $config = config('whish-pay');

        // ✅ Config validation
        foreach (['channel', 'secret', 'website_url'] as $key) {
            if (empty($config[$key])) {
                $this->error("Missing config: whish-pay.$key");

                return self::FAILURE;
            }
        }

        $this->info('✔ Config OK');

        // ✅ Connectivity test
        try {

            $sandboxUrl = $config['sandbox_url'] ?? null;
            $prodUrl = $config['production_url'] ?? null;

            $allReachable = true;

            if ($sandboxUrl) {
                try {
                    Http::timeout(5)->get($sandboxUrl);
                    $this->info('✔ Sandbox API reachable');
                } catch (Throwable $e) {
                    $allReachable = false;
                    $this->error('Cannot reach Whish Sandbox API.');
                }
            } else {
                $this->line('ℹ️ Sandbox URL not configured.');
            }

            if ($prodUrl) {
                try {
                    Http::timeout(5)->get($prodUrl);
                    $this->info('✔ Production API reachable');
                } catch (Throwable $e) {
                    $allReachable = false;
                    $this->error('Cannot reach Whish Production API.');
                }
            } else {
                $this->line('ℹ️ Production URL (base_url) not configured.');
            }

            if (! $allReachable) {
                return self::FAILURE;
            }

        } catch (Throwable $e) {

            $this->error('Cannot reach Whish API.');

            return self::FAILURE;
        }

        // ✅ Credential test
        try {

            $balance = $whish->getBalance();

            $this->info('✔ Credentials valid');
            $this->line('Balance: '.$balance);
        } catch (Throwable $e) {

            $this->error('Credentials failed: '.$e->getMessage());

            return self::FAILURE;
        }

        $this->newLine();
        $this->info('Whish integration is HEALTHY ✅');

        return self::SUCCESS;
    }
}
