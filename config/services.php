<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    // Kredensial bersama untuk sistem ERP (BE lain) — dipakai lintas integrasi
    // (PSB WO Step dan integrasi lain menyusul), bukan per-fitur.
    'erp' => [
        'url' => env('ERP_API_URL'),
        'key' => env('ERP_API_KEY'),
    ],

    'psb_wo_step' => [
        // 'json' = data lokal di storage/app/private/psb-wo-steps.json (aktif saat ini).
        // 'api'  = terhubung ke sistem ERP lewat PsbIntegratedRepository.
        'driver' => env('PSB_WO_STEP_DRIVER', 'json'),
    ],

];
