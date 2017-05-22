<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Protected Environment
    |--------------------------------------------------------------------------
    |
    | This value determines the "environment" which should be protected.
    |
    */

    'protected_env' => env('STAUTH_PROTECTED_ENV', 'staging'),

    /*
    |--------------------------------------------------------------------------
    | Stauth Access token
    |--------------------------------------------------------------------------
    |
    | Access token is required to make authorized API requests.
    | Generate access token for your domain at www.stauth.io
    |
    */
    'access_token' => env('STAUTH_ACCESS_TOKEN'),

];
