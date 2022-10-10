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

    'google' => [
        'client_id'     => '374268774158120',
        'client_secret' => 'dee720d17a813afadb937a78b5c698fa',
        'redirect'      => 'https://72c9a0be76b6.ngrok.io/senior/callback/facebook?userType=',
    ],
    
    'facebook' => [
        'client_id'     => '374268774158120',
        'client_secret' => 'dee720d17a813afadb937a78b5c698fa',
        'redirect'      => 'https://72c9a0be76b6.ngrok.io/senior/callback/facebook?userType=',
    ],

    'linkedin' => [
        'client_id'     => '8672w6l73rypk4',
        'client_secret' => 'FCLJGYlsLVUGQ2n5',
        'redirect'      => 'http://127.0.0.1:8000/senior/callback/linkedin'
    ],

];
