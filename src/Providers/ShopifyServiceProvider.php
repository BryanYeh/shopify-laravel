<?php

namespace Bryanyeh\Shopify\Providers;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;
use Bryanyeh\Shopify\Shopify;

class ShopifyServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     * https://laravel.com/docs/5.4/providers#deferred-providers
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/shopify.php' => config_path('shopify.php'),
        ],'config');
        $this->app->alias('Shopify', 'Bryanyeh\Shopify\Facades\Shopify');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('shopify',function($app) 
        {
            return new Shopify(new \GuzzleHttp\Client);
        });
    }
}
