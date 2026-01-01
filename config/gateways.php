<?php

return [
    'esewa' => [
        'enabled' => env('ESEWA_ENABLED', false),
        'merchant_id' => env('ESEWA_MERCHANT_ID', 'EPAYTEST'),
        'url' => env('ESEWA_URL', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form'),
        'verification_url' => env('ESEWA_VERIFICATION_URL', 'https://rc.esewa.com.np/mobile/transaction'),
    ],

    'khalti' => [
        'enabled' => env('KHALTI_ENABLED', false),
        'public_key' => env('KHALTI_PUBLIC_KEY', ''),
        'secret_key' => env('KHALTI_SECRET_KEY', ''),
        'url' => env('KHALTI_URL', 'https://a.khalti.com/api/v2/epayment/initiate/'),
        'verification_url' => env('KHALTI_VERIFICATION_URL', 'https://a.khalti.com/api/v2/epayment/lookup/'),
    ],
];
