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
        'client_id'     => '657190338424983', //env('FB_ID'),
        'client_secret' => '35ce6a3d9029943a2c66e6dfc8525331', //env('FB_SECRET'),
        'redirect'      => 'http://127.0.0.1:8000/callback/facebook' //env('APP_URL') . '/oauth/facebook/callback',
    ],

    'google' => [
        'client_id'     => env('GL_ID'),
        'client_secret' => env('GL_SECRET'),
        'redirect'      => env('APP_URL') . '/oauth/google/callback'
    ],

    "apple" => [
        'grant_type' => 'authorization_code',
        'client_id' => 'com.test.client',
        // 'client_secret' => $this->generateClientSecretToken(),
        // 'code' => 'xxxxxxxxxx',
        'redirect_uri' => 'https://test.com/appleLogin/redirect'
    ],

];
