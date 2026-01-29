<?php

namespace AhmadChebbo\WhishPay\Commands;

use Illuminate\Console\Command;

class WhishFakeCommand extends Command
{
    protected $signature = 'whish:fake {state=on}';

    protected $description = 'Enable or disable fake Whish responses';

    public function handle()
    {
        $state = strtolower($this->argument('state'));

        if (!in_array($state, ['on', 'off'])) {
            $this->error('State must be ON or OFF');
            return;
        }

        $envFile = base_path('.env');
        $fakeValue = $state === 'on' ? 'true' : 'false';
        $envKey = 'WHISH_FAKE';

        if (!file_exists($envFile)) {
            $this->error('.env file does not exist.');
            return;
        }

        $envContents = file_get_contents($envFile);

        // Pattern to find the config line (with or without comment)
        $pattern = '/^' . preg_quote($envKey) . '=.*/m';

        if (preg_match($pattern, $envContents)) {
            // Replace if exists
            $newEnvContents = preg_replace($pattern, $envKey . '=' . $fakeValue, $envContents);
        } else {
            // Add at the end if not exists
            $newEnvContents = rtrim($envContents) . PHP_EOL . $envKey . '=' . $fakeValue . PHP_EOL;
        }

        file_put_contents($envFile, $newEnvContents);

        $this->info("Fake mode toggled: WHISH_FAKE={$fakeValue}");
    }
}
