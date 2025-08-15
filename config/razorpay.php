<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Razorpay Credentials
    |--------------------------------------------------------------------------
    |
    | Your Razorpay API credentials. You can find these in your Razorpay
    | Dashboard under Settings > API Keys.
    |
    */
    
    'key' => env('RAZORPAY_KEY'),
    'secret' => env('RAZORPAY_SECRET'),
    
    /*
    |--------------------------------------------------------------------------
    | Webhook Secret
    |--------------------------------------------------------------------------
    |
    | Your webhook secret for verifying webhook signatures. You can find this
    | in your Razorpay Dashboard under Settings > Webhooks.
    |
    */
    
    'webhook_secret' => env('RAZORPAY_WEBHOOK_SECRET'),
];