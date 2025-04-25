<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Authentication Secret
    |--------------------------------------------------------------------------
    |
    | This value is the secret used to sign your tokens. Make sure to set this
    | in your .env file, as it will be used to verify the tokens.
    |
    */

    'secret' => env('JWT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | JWT Time To Live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token will be valid for.
    | Defaults to 60 minutes (1 hour).
    |
    */

    'ttl' => env('JWT_TTL', 60), // Changed from default to 60 minutes (1 hour)

    /*
    |--------------------------------------------------------------------------
    | Refresh Time To Live
    |--------------------------------------------------------------------------
    |
    | Specify the length of time (in minutes) that the token can be refreshed
    | after it has expired. Defaults to 20160 minutes (2 weeks).
    |
    */

    'refresh_ttl' => env('JWT_REFRESH_TTL', 20160),

    /*
    |--------------------------------------------------------------------------
    | JWT Hashing Algorithm
    |--------------------------------------------------------------------------
    |
    | Specify the hashing algorithm that will be used to sign the token.
    | Possible values are: HS256, HS512, HS384.
    |
    */

    'algo' => env('JWT_ALGO', 'HS256'),

    /*
    |--------------------------------------------------------------------------
    | Required Claims
    |--------------------------------------------------------------------------
    |
    | Specify the claims that must be present in the token.
    | A default value is provided, but you can add or remove claims as needed.
    |
    */

    'required_claims' => [
        'iss',
        'iat',
        'exp',
        'nbf',
        'sub',
        'jti',
    ],

    /*
    |--------------------------------------------------------------------------
    | Persistent Claims
    |--------------------------------------------------------------------------
    |
    | Specify the claims that should be persisted when refreshing a token.
    | This is useful for keeping track of claims such as 'user_id'.
    |
    */

    'persistent_claims' => [],

    /*
    |--------------------------------------------------------------------------
    | Blacklist Enabled
    |--------------------------------------------------------------------------
    |
    | Specify whether or not the token should be blacklisted when it is
    | invalidated. If set to false, tokens will never be blacklisted.
    |
    */

    'blacklist_enabled' => env('JWT_BLACKLIST_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Blacklist Grace Period
    |--------------------------------------------------------------------------
    |
    | Specify the grace period (in seconds) that a token can still be used
    | after it has been invalidated. Defaults to 0 seconds.
    |
    */

    'blacklist_grace_period' => env('JWT_BLACKLIST_GRACE_PERIOD', 0),

    /*
    |--------------------------------------------------------------------------
    | Token Encryption
    |--------------------------------------------------------------------------
    |
    | Specify whether or not the token should be encrypted.
    | If set to true, the token will be encrypted using OpenSSL.
    |
    */

    'encrypt' => env('JWT_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | Specify the providers that will be used to handle the token.
    | You can customize these providers as needed.
    |
    */

    'providers' => [
        'jwt' => Tymon\JWTAuth\Providers\JWT\Namshi::class,
        'auth' => Tymon\JWTAuth\Providers\Auth\Illuminate::class,
        'storage' => Tymon\JWTAuth\Providers\Storage\Illuminate::class,
    ],
];
