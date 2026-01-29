<?php

namespace AhmadChebbo\WhishPay\Commands;

use Illuminate\Console\Command;

class WhishInstallCommand extends Command
{
    protected $signature = 'whish:install';

    protected $description = 'Install the Whish Pay package';

    public function handle()
    {
        $this->call('vendor:publish', [
            '--tag' => 'whish-config',
        ]);

        $this->info('Whish Pay published config file.');

        // Prompt the user for environment variables
        $mode = $this->choice('Choose Whish Pay mode', ['sandbox', 'production'], 0);
        $channel = $this->ask('Enter your Whish Pay channel');
        $secret = $this->ask('Enter your Whish Pay secret');
        $productionUrl = $this->ask('Enter your Whish Pay production URL (as provided by Whish)', 'https://api.whish.money/itel-service/api');
        $sandboxUrl = $this->ask('Enter your Whish Pay sandbox URL (as provided by Whish)', 'https://api.sandbox.whish.money/itel-service/api');
        $websiteUrl = $this->ask('Enter your website URL (for webhooks or reference, e.g., https://your-site.com)');
        $timeout = $this->ask('Set an API timeout in seconds', 30);

        // Prepare .env lines
        $envLines = [
            "WHISH_MODE={$mode}",
            "WHISH_CHANNEL={$channel}",
            "WHISH_SECRET={$secret}",
            "WHISH_PRODUCTION_URL={$productionUrl}",
            "WHISH_SANDBOX_URL={$sandboxUrl}",
            "WHISH_WEBSITE_URL={$websiteUrl}",
            "WHISH_TIMEOUT={$timeout}",
        ];

        // Attempt to append to .env
        $envPath = base_path('.env');
        $envAdded = false;

        if (is_writable($envPath)) {
            file_put_contents($envPath, PHP_EOL . implode(PHP_EOL, $envLines) . PHP_EOL, FILE_APPEND);
            $this->info('.env updated with Whish Pay configuration!');
            $envAdded = true;
        } else {
            $this->warn("Could not write to .env file. Please add the following lines manually:\n");
            foreach($envLines as $line) {
                $this->line($line);
            }
        }

        // Summary
        $this->info('Whish Pay environment configuration summary:');
        $this->table(['Key', 'Value'], collect($envLines)->map(function($l) {
            [$k, $v] = explode('=', $l, 2);
            return [$k, $v];
        }));

        $this->info('Whish Pay installation completed!');
        if ($envAdded) {
            $this->info('You can now configure any additional options in config/whish-pay.php.');
        } else {
            $this->warn('Remember to add the environment variables above to your .env file.');
        }
    }
}
