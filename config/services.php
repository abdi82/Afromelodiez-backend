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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),  // Your Facebook App ID
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'), // Your Facebook App Secret
    'redirect' => env('FACEBOOK_CALLBACK_URL'),
],

  'google' => [
        'client_id' => '440887791065-h5qk426u04np0jmj9mchi10682mue7hb.apps.googleusercontent.com',
        'client_secret' => 'l35zlPgWhKx-M2FlY6Ntp-rL',
        'redirect' => 'http://112.196.64.118/bettingapp/login/google/callback',
    ],

];
