<?php

use AhmadChebbo\WhishPay\Pipelines\ValidatePaymentPayload;

/**
 * Whish Pay Configuration
 *
 * This file is for storing the configuration values for the Whish Pay integration.
 *
 * @see https://docs.whish.money/
 *
 * Environment Variables you may use:
 * - WHISH_PRODUCTION_URL
 * - WHISH_SANDBOX_URL
 * - WHISH_MODE
 * - WHISH_CHANNEL
 * - WHISH_SECRET
 * - WHISH_WEBSITE_URL
 * - WHISH_USER_AGENT
 * - WHISH_FAKE
 */

return [

    /*
    |--------------------------------------------------------------------------
    | API URLs
    |--------------------------------------------------------------------------
    | The API endpoints for both the production and sandbox environments.
    */
    'production_url' => env('WHISH_PRODUCTION_URL', 'https://whish.money/itel-service/api'),
    'sandbox_url' => env('WHISH_SANDBOX_URL', 'https://lb.sandbox.whish.money/itel-service/api'),

    /*
    |--------------------------------------------------------------------------
    | Mode
    |--------------------------------------------------------------------------
    | Determines which API environment to use: 'production' or 'sandbox'.
    */
    'mode' => env('WHISH_MODE', 'sandbox'), // Valid values: sandbox, production

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    | Credentials and identification for requests.
    */
    'channel' => env('WHISH_CHANNEL'), // Whish issued channel ID
    'secret' => env('WHISH_SECRET'), // Whish issued secret key

    /*
    |--------------------------------------------------------------------------
    | Website URL
    |--------------------------------------------------------------------------
    | The base URL of your website (used for redirect or webhook integration).
    */
    'website_url' => env('WHISH_WEBSITE_URL'),

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    | HTTP User-Agent header to send with requests.
    */
    'user_agent' => env('WHISH_USER_AGENT', 'Whish/1.0 (https://whish.money; support@whish.money)'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    | The maximum time (seconds) to wait for a Whish server response.
    */
    'timeout' => 30,

    /*
    |--------------------------------------------------------------------------
    | Fake Mode
    |--------------------------------------------------------------------------
    | Enable to simulate API responses for testing (without hitting Whish APIs).
    */
    'fake' => env('WHISH_FAKE', false),

    /*
    |--------------------------------------------------------------------------
    | Allowed Currencies
    |--------------------------------------------------------------------------
    | The list of ISO currency codes accepted for payments.
    */
    'allowed_currencies' => ['USD', 'LBP', 'AED'],

    /*
    |--------------------------------------------------------------------------
    | Payment Amount Limits
    |--------------------------------------------------------------------------
    | Global minimum and maximum allowed payment amounts.
    */
    'limits' => [
        'min' => 1,      // Minimum allowed amount per payment
        'max' => 5000,   // Maximum allowed amount per payment
    ],

    /*
    |--------------------------------------------------------------------------
    | Fraud Detection Rules
    |--------------------------------------------------------------------------
    | Parameters for anti-fraud measures (e.g. max single payment).
    */
    'fraud' => [
        'max_single_payment' => 10000, // Absolute max for a single payment
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    | Optionally specify a custom model for storing pending payments.
    */
    // 'payment_model'  => App\Models\Payment::class,

    /*
    |--------------------------------------------------------------------------
    | Payment Pipelines
    |--------------------------------------------------------------------------
    | Define the list of pipeline classes that process payments before submission.
    | You can customize validation, logging, fraud detection, limits, etc.
    | The order matters: each pipeline gets the payment DTO, runs its logic,
    | then passes control to the next. Comment/uncomment as needed.
    */
    'enable_pipeline' => false,
    'pipelines' => [
        ValidatePaymentPayload::class,
        // \AhmadChebbo\WhishPay\Pipelines\FraudDetectionPipeline::class,
        // \AhmadChebbo\WhishPay\Pipelines\CurrencyValidationPipeline::class,
        // \AhmadChebbo\WhishPay\Pipelines\AmountLimitPipeline::class,
        // \AhmadChebbo\WhishPay\Pipelines\PaymentLoggingPipeline::class,
    ],
];
