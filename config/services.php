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

    'openrouter' => [
        'api_key' => env('OPENROUTER_API_KEY'),
        'base_url' => env('OPENROUTER_BASE_URL', 'https://openrouter.ai/api/v1'),
        'search_model' => env('SEARCH_MODEL', 'google/gemini-2.5-flash'),
        'text_model' => env('TEXT_MODEL', 'openai/gpt-4o-mini'),
        'image_model' => env('IMAGE_MODEL', 'bytedance/sdxl-lightning-4step'),
    ],

    'unsplash' => [
        'access_key' => env('UNSPLASH_ACCESS_KEY'),
        'secret_key' => env('UNSPLASH_SECRET_KEY'),
    ],

    'news_api' => [
        'key' => env('NEWS_API_KEY'),
    ],

    'google' => [
        'indexing_api_key'     => env('GOOGLE_INDEXING_API_KEY'),
        'indexing_client_email'=> env('GOOGLE_INDEXING_CLIENT_EMAIL'),
        'indexing_private_key' => env('GOOGLE_INDEXING_PRIVATE_KEY'),
    ],

];
