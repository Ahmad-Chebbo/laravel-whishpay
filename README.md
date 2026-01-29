# Laravel Whish Pay

A clean, production-ready Laravel wrapper for the **Whish Payment Gateway API** designed for excellent developer experience, reliability, and scalability.

---

## ✨ Features

✅ Simple Laravel integration
✅ Facade + Dependency Injection support
✅ Automatic request headers
✅ Config publishing
✅ Health diagnostics command
✅ Cron-ready payment status checker
✅ Enum support
✅ Extensible architecture
✅ DTO-based requests  
✅ Typed responses  
✅ Pipeline validation  
✅ Fake client for testing  
✅ Idempotency-ready design  
✅ Retry-capable HTTP layer  
✅ Webhook generator  
✅ Strong exception handling  

---

## 📦 Requirements

- PHP **8.1+**
- Laravel **10 / 11  / 12**
- HTTPS-enabled server

---

## 🧱 Folder Architecture

```
src/
├── Commands
├── Contracts
├── DTOs
├── Enums
├── Exceptions
├── Facades
├── Http
│   ├── Clients
│   └── Middleware
├── Pipelines
├── Services
└── Support
```

This structure ensures:

- High testability  
- Clean dependency injection  
- Future gateway expansion  
- Minimal breaking changes  

---

## 🚀 Quick Start

### Using the Facade

```php
use AhmadChebbo\WhishPay\DTOs\PaymentDTO;
use AhmadChebbo\WhishPay\Enums\Currency;
use AhmadChebbo\WhishPay\WhishPay;

$dto = new PaymentDTO(
    amount: 100,
    currency: Currency::USD,
    invoice: 'Order #501',
    externalId: 501,
    successCallbackUrl: route('success'),
    failureCallbackUrl: route('failure'),
    successRedirectUrl: url('/thanks'),
    failureRedirectUrl: url('/error'),
);

$response = Whish::createPayment($dto);

return redirect($response->collectUrl);
```

---
## Typed Responses

```php
$response->collectUrl;
$status->status;
$status->payerPhone;
```

No guessing fields.

No broken payloads.

---

## 🔄 Verify Payment Status

Always verify the payment server-side before marking an order as paid.

```php
use AhmadChebbo\WhishPay\WhishPay;

// Get status using the Facade.
// Returns a typed StatusResponseDTO with convenient properties.
$statusResponse = WhishPay::getStatus('USD', 1001);

if ($statusResponse->status === 'success') {
    // Mark order as paid
    // Optionally access payer phone: $statusResponse->payerPhone
}
```
---

🎯 Or if you prefer type safety with the Currency enum:
```php
use AhmadChebbo\WhishPay\Enums\Currency;

$statusResponse = WhishPay::getStatus(Currency::USD->value, 1001);
if ($statusResponse->status === 'success') {
    // Mark order as paid
}
```

---

## 💰 Check Account Balance (ONLY for LBP for now)

Easily retrieve your WhishPay account balance.

```php
use AhmadChebbo\WhishPay\WhishPay;

// Get account balance using the Facade.
$balance = WhishPay::getBalance();

echo "Current balance: $balance USD";
```
---

## 💳 Recommended Payment Flow

1. Create local order.
2. Generate a unique `externalId`.
3. Call `createPayment()`.
4. Redirect the user to Whish.
5. Receive callback.
6. Verify payment via `getStatus()`.
7. Mark order as paid.

⚠️ **Never trust redirects alone. Always verify with the API.**

---

## 🧪 Fake Mode (Local Development)

Avoid hitting the real API during development or CI.

```env
WHISH_FAKE=true
```

Fake responses include:

- Large test balance
- Dummy checkout URL
- Successful payment status

Perfect for:

✅ Local development  
✅ CI pipelines  
✅ Staging environments  

---

## 🛠 Artisan Commands

### Install Package

```bash
php artisan whish:install
```

Publishes configuration automatically.

---

### Test API Credentials

```bash
php artisan whish:test
```

Checks:

- API connectivity  
- Headers  
- Credentials  

---

### Health Diagnostics

```bash
php artisan whish:health
```

Runs deep checks:

- Config validation  
- API reachability  
- Credential verification  
- Timeout detection  

Perfect for production debugging.

---

### Generate Webhook Controller

```bash
php artisan whish:webhook
```

Creates a ready-to-use controller.

Then register the route:

```php
Route::get('/whish/webhook', [WhishWebhookController::class, 'handle']);
```

---

### Enable / Disable Fake Mode

```bash
php artisan whish:fake on
php artisan whish:fake off
```

*(Update your `.env` accordingly.)*

---

### Cron Payment Status Checker 🔥

Automatically verifies pending payments.

```bash
php artisan whish:status-checker
```

---

## ⏱ Schedule It

Inside `app/Console/Kernel.php`:

```php
$schedule->command('whish:status-checker')->everyFiveMinutes();
```

---

## 🔥 Validation Pipeline

Before hitting the API, every payment flows through a customizable pipeline.

Current stages include:


## ✅ Fraud Detection

Detect suspicious transactions before submission.

Examples you can implement:

- Velocity checks  
- Geo anomalies  
- Repeated failures  
- High-risk patterns  

```php
FraudDetectionPipeline::class
```

---

## ✅ Currency Validation

Ensures only supported currencies are used.

Prevents gateway rejection.

```php
CurrencyValidationPipeline::class
```

---

## ✅ Amount Limits

Protect your business from extreme charges.

Example rules:

- Minimum payment threshold  
- Maximum transaction cap  
- Tier-based limits  

```php
AmountLimitPipeline::class
```

---

## ✅ Structured Logging

Every payment attempt can be logged for:

- Audit trails  
- Financial reconciliation  
- Incident debugging  

```php
PaymentLoggingPipeline::class
```

---

## Example Pipeline Configuration

All pipelines are managed in the `whish-pay` config file.  
You can comment, uncomment, reorder, or add your own custom pipelines easily.

**Example (`config/whish-pay.php`):**
```php
return [
    // ...
    'enable_pipeline' => true,
    'pipelines' => [
        // \AhmadChebbo\WhishPay\Pipelines\ValidatePaymentPayload::class,
        // \AhmadChebbo\WhishPay\Pipelines\FraudDetectionPipeline::class,
        // \AhmadChebbo\WhishPay\Pipelines\CurrencyValidationPipeline::class,
        // \AhmadChebbo\WhishPay\Pipelines\AmountLimitPipeline::class,
        // \AhmadChebbo\WhishPay\Pipelines\PaymentLoggingPipeline::class,
    ],
    // ...
];
```

Just comment/uncomment or add new stages without ever touching core logic.

---

## ⚙️ Config Example

```php
return [

    'base_url' => env('WHISH_BASE_URL'),

    'channel' => env('WHISH_CHANNEL'),
    'secret' => env('WHISH_SECRET'),
    'website_url' => env('WHISH_WEBSITE_URL'),
    'user_agent' => env('WHISH_USER_AGENT'),

    'fake' => env('WHISH_FAKE', false),

    // Model used by status checker
    'pending_model' => App\Models\Payment::class,

    'limits' => [
        'min' => 1,      // Minimum allowed amount per payment
        'max' => 5000,   // Maximum allowed amount per payment
    ],

    'fraud' => [
        'max_single_payment' => 10000, // Absolute max for a single payment
    ],
];
```

---

# ⚙️ Status Checker Model

Define your pending payment model:

```php
'pending_model' => App\Models\Payment::class,
```

Recommended columns:

```
status (pending/paid/failed)
currency
external_id
amount
```

---

## ❗ Error Handling

The package throws:

```
AhmadChebbo\WhishPay\Exceptions\WhishException
```

Example:

```php
try {
    WhishPay::getBalance();
} catch (\AhmadChebbo\WhishPay\Exceptions\WhishException $e) {
    report($e);
}
```

---

## 🔒 Security Best Practices

- Never expose your **secret**
- Always verify payment status server-side
- Use HTTPS in production
- Generate unique `externalId`
- Log callbacks for audit trails
- Avoid trusting client redirects
- Implement fraud checks
- Consider idempotency for retries

---

## 🧪 Sandbox Testing

Use sandbox credentials.

**Successful payment test:**

- Phone: `96170902894`
- OTP: `111111`

Any other OTP results in failure.

> No OTP is delivered in sandbox mode.

---

## 🧱 Designed for Scale

This package is intentionally structured to support future upgrades like:

- Multi-gateway abstraction  
- Circuit breakers  
- Smart retries  
- Gateway failover  
- Event-driven payments  
- Webhook signatures  

Adopt it once — scale without rewriting.

---

## 🤝 Contributing

Contributions are welcome!

1. Fork the repository  
2. Create a feature branch  
3. Submit a Pull Request  

---

## 📄 License

MIT License © Ahmad Shebb
