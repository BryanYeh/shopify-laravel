# Laravel Shopify
This package is only for Laravel integration with Shopify's API to make a public app

[![Latest Stable Version](https://poser.pugx.org/bryanyeh/shopify-laravel/v/stable)](https://packagist.org/packages/bryanyeh/shopify-laravel) [![Total Downloads](https://poser.pugx.org/bryanyeh/shopify-laravel/downloads)](https://packagist.org/packages/bryanyeh/shopify-laravel) [![License](https://poser.pugx.org/bryanyeh/shopify-laravel/license)](https://packagist.org/packages/bryanyeh/shopify-laravel)

## Requirements
- Laravel 5.4
- PHP 7+

## Installation
Using command line
```
composer require bryanyeh/shopify-laravel 1.0
```


Edit your ```.env``` file add and replace with no ```< >```
```
SHOPIFY_API_KEY=<replace with api key>
SHOPIFY_API_SECRET=<replace with api secret>
SHOIFY_API_SCOPES=<replace with scopes seperated by commas like: read_products,write_products,read_draft_orders>
```


Add to providers arrray in ```config/app.php```
```
Bryanyeh\Shopify\Providers\ShopifyServiceProvider::class,
```


Add to aliases array ```in config/app.php```
```
'Shopify' => Bryanyeh\Shopify\Facades\Shopify::class,
```


Add to routeMiddleware array in ```app/http/Kernel.php```
```
'shopifyrequest' => \Bryanyeh\Shopify\Middleware\VerifyRequest::class,
'shopifynonce' => \Bryanyeh\Shopify\Middleware\VerifyNonce::class,
'shopifywebhook' => \Bryanyeh\Shopify\Middleware\VerifyWebHook::class,
'shopifyproxy' => \Bryanyeh\Shopify\Middleware\VerifyProxy::class,
```


In the command line
```
php artisan vendor:publish --provider="Bryanyeh\Shopify\Providers\ShopifyServiceProvider" 
```


## Usage:
Make sure to use ```use Bryanyeh\Shopify\Facades\Shopify;``` wherever you are planning to use this package.


### Asking for permission to install the app
Make sure to use the middleware ```shopifyrequest```
```php
use Bryanyeh\Shopify\Facades\Shopify;

Route::get('install', function(Request $request){
    return redirect(Shopify::init($request->input('shop'))->install('https://example.com/confirm'));
})->middleware('shopifyrequest');
```


### Retreiving the access token
To do this you will need to use the middlewares ```shopifynonce``` and ```shopifyrequest```
```php
use Bryanyeh\Shopify\Facades\Shopify;

Route::get('confirm', function(Request $request){
    $access_token = Shopify::init($request->input('shop'))->getAccessToken($request->input('code'))['access_token'];

    //save the access_token for later use

    //redirect to success or billing page

})->middleware('shopifynonce', 'shopifyrequest');
```


### Verifying webhooks
Simply use the middleware ```shopifywebhook```


### Verifying proxy requests
Simply use the middleware ```shopifyproxy```


### Accessing API resource
```php
use Bryanyeh\Shopify\Facades\Shopify;

$geturi = '/admin/products/count.json' ;
$posturi = '/admin/products.json';
$puturi = '/admin/products/632910392.json';
$deleteuri = '/admin/products/632910392.json';

$shop = Shopify::init($my_shopify_store,$access_token);
$shop->get($geturi, [if any]);
$shop->post($posturi, [data]);
$shop->put($puturi, [data]);
$shop->delete($deleteuri);
```


Everything that gets will be in a form of an array containing:
```
statusCode   :  200 or 400 or status code
reasonPhrase :  just a phrase that goes with the status code
callLimit    :  HTTP_X_SHOPIFY_SHOP_API_CALL_LIMIT
????         :  expected response data shopify returns
```

### Middlewares
- ```shopifywebhook``` returns 401 error when hmac is invalid
- ```shopifyproxy``` returns 401 error when signature or domain are invalid
- ```shopifynonce``` and ```shopifyrequest```, will redirect to a named route ```re-auth```, with this you can decide what to do if either the nonce or request is invalid



## Errors
This package throws only 1 exception.
- ```InvalidMethodRequestException```
    - only ```get/post/put/delete``` is allowed, look at [Acessing API Resource](#accessing-api-resource)


## Notes:
- Nonce is automatically taken care for you
- Make sure to add your incoming webhook routes to the ```app/Http/Middleware/VerifyCsrfToken.php``` in the ```except``` array


## License
Laravel Shopify is licensed under [MIT License (MIT)](LICENSE.md).