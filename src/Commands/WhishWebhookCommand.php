<?php

namespace AhmadChebbo\WhishPay\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class WhishWebhookCommand extends Command
{
    protected $signature = 'whish:webhook';

    protected $description = 'Generate a Whish webhook controller';

    public function handle()
    {
        $path = app_path('Http/Controllers/WhishWebhookController.php');

        if (File::exists($path)) {
            $this->error('Webhook controller already exists.');
            return;
        }

        File::put($path, $this->controllerStub());

        $this->info('Webhook controller created successfully.');
        $this->warn('Add this route:');
        $this->line("Route::get('/whish/webhook', [WhishWebhookController::class, 'handle']);");
    }

    protected function controllerStub()
    {
        return <<<PHP
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AhmadChebbo\WhishPay\Facades\WhishPay;

class WhishWebhookController extends Controller
{
    public function handle(Request \$request)
    {
        \$externalId = \$request->get('externalId', 1);
        \$currency = \$request->get('currency', 'USD');

        // ALWAYS verify payment
        \$statusResponse = WhishPay::getStatus(\$currency, \$externalId);

        // Use DTO (StatusResponseDTO) properties:
        // \$statusResponse->status (string)
        // \$statusResponse->payerPhone (?string)

        // TODO:
        // - Update order based on \$statusResponse->status
        // - If status is 'success', mark order as paid
        // - Optionally handle \$statusResponse->payerPhone
        // - Dispatch events, etc.

        return response()->json([
            'received' => true,
            'status' => \$statusResponse->status,
            'payer_phone' => \$statusResponse->payerPhone,
        ]);
    }
}
PHP;
    }
}
