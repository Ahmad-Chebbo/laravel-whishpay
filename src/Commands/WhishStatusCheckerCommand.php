<?php

namespace AhmadChebbo\WhishPay\Commands;

use AhmadChebbo\WhishPay\Contracts\WhishPayContract;
use Illuminate\Console\Command;

class WhishStatusCheckerCommand extends Command
{
    protected $signature = 'whish:status-checker';

    protected $description = 'Check status of pending Whish payments';

    public function handle(WhishPayContract $whish)
    {
        // Developer defines how to fetch pending payments.
        if (! class_exists(config('whish-pay.payment_model'))) {
            $this->error('Define payment_model in config.');

            return;
        }

        $model = config('whish-pay.payment_model');

        $pending = $model::where('status', 'pending')->limit(50)->get();

        foreach ($pending as $payment) {

            $status = $whish->getStatus(
                $payment->currency,
                $payment->external_id
            );

            if (isset($status->status) && $status->status === 'success') {

                $payment->update(['status' => 'paid']);

                // optional:
                // event(new PaymentPaid($payment));

                $this->info("Payment {$payment->id} marked as PAID");
            }
        }

        return self::SUCCESS;
    }
}
