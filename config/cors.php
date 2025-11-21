<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | The paths that should be processed by the CORS middleware.
    | Typically you include your API routes and Sanctum CSRF route (if used).
    |
    */

    'paths' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    |
    | HTTP methods that are allowed for cross-origin requests.
    | Using ['*'] means all methods (GET, POST, PUT, PATCH, DELETE, OPTIONS, etc.)
    |
    */

    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    |
    | Origins that are allowed to access your API.
    | Add your frontend URLs here (e.g. Vue dev server on port 3000).
    |
    */

    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost:5173',
        'http://127.0.0.1:5173',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    |
    | You may define patterns that match allowed origins using regular
    | expressions. Usually this can stay empty.
    |
    */

    'allowed_origins_patterns' => [],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    |
    | Request headers that are allowed in cross-origin requests.
    | Using ['*'] means all headers are allowed.
    |
    */

    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    |
    | Response headers that are allowed to be exposed to the browser.
    | If you need the frontend to read specific headers from the response,
    | list them here. Otherwise, you can leave this empty.
    |
    */

    'exposed_headers' => [],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    |
    | The number of seconds the results of a preflight request can be cached.
    | A value of 0 means no caching.
    |
    */

    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    |
    | Indicates whether cross-origin requests can include user credentials
    | such as cookies or authorization headers.
    | Set this to true if you are using cookies (e.g. Sanctum).
    |
    */

    'supports_credentials' => true,
];
