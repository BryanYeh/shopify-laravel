<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SHOPIFY API KEY
    |--------------------------------------------------------------------------
    |
    | Retrieve SHOPIFY_API_KEY from .env
    |
    */

    'key' => env('SHOPIFY_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | SHOPIFY API SECRET
    |--------------------------------------------------------------------------
    |
    | Retrieve SHOPIFY_API_SECRET from .env
    |
    */

    'secret' => env('SHOPIFY_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | SHOPIFY API SCOPES
    |--------------------------------------------------------------------------
    |
    | Retrieve SHOPIFY_API_SCOPES from .env
    |
    | Read up on scopes: https://help.shopify.com/api/getting-started/authentication/oauth#scopes
    |
    | Default: read_products,write_products
    |
    */

    'scopes' => array_map('trim', explode(',', env('SHOIFY_API_SCOPES', 'read_products,write_products')))
];