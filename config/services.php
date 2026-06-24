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

    /*
    |--------------------------------------------------------------------------
    | WhatsApp Order Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi nomor WhatsApp admin dan pesan template yang digunakan
    | pada CTA "Pesan Undangan" di landing page. Nomor harus dalam format
    | internasional tanpa tanda "+" (contoh: 6281234567890).
    |
    */

    'whatsapp' => [
        'admin_number' => env('WHATSAPP_ADMIN_NUMBER', '6281234567890'),
        'order_message' => env('WHATSAPP_ORDER_MESSAGE', "Halo Admin Teman Seakad,\n\nSaya tertarik menggunakan layanan Undangan Pernikahan Digital.\n\nNama:\nTanggal Pernikahan:\nPaket:\nJumlah Undangan:\n\nTerima kasih."),
    ],

];
