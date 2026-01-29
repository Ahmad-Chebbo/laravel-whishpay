<?php

namespace AhmadChebbo\WhishPay\Providers;

use AhmadChebbo\WhishPay\Commands\{
    WhishCheckBalance,
    WhishHealthCommand,
    WhishWebhookCommand,
    WhishFakeCommand,
    WhishStatusCheckerCommand,
    WhishTestCommand,
    WhishInstallCommand
};
use AhmadChebbo\WhishPay\Contracts\HttpClientContract;
use AhmadChebbo\WhishPay\Contracts\WhishPayContract;
use AhmadChebbo\WhishPay\Http\Clients\FakeWhishHttpClient;
use AhmadChebbo\WhishPay\Http\Clients\WhishHttpClient;
use AhmadChebbo\WhishPay\Services\WhishPayService;
use Illuminate\Support\ServiceProvider;

class WhishPayServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/whish-pay.php',
            'whish-pay'
        );

        $this->app->bind(HttpClientContract::class, function () {

            if (config('whish-pay.fake')) {
                return new FakeWhishHttpClient();
            }

            return new WhishHttpClient();
        });

        $this->app->singleton(WhishPayContract::class, function ($app) {
            return new WhishPayService(
                $app->make(HttpClientContract::class),
                $app->make(\Illuminate\Pipeline\Pipeline::class),
            );
        });

        $this->app->alias(WhishPayContract::class, 'whish-pay');
    }


    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->commands([
                WhishCheckBalance::class,
                WhishHealthCommand::class,
                WhishWebhookCommand::class,
                WhishFakeCommand::class,
                WhishStatusCheckerCommand::class,
                WhishTestCommand::class,
                WhishInstallCommand::class,
            ]);


            $this->publishes([
                __DIR__ . '/../../config/whish-pay.php' => config_path('whish-pay.php'),
            ], 'whish-config');
        }
    }
}