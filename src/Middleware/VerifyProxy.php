<?php

namespace Bryanyeh\Shopify\Middleware;

use Closure;
use Bryanyeh\Shopify\Exceptions\InvalidShopifyDomainException;
use Bryanyeh\Shopify\Exceptions\InvalidSignatureException;

class VerifyProxy
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$this->isShopifyDomain($request->input('shop'))){
            throw new InvalidShopifyDomainException();
        }

        if(!$this->validateSignature($request)){
            throw new InvalidSignatureException();
        }
            
        return $next($request);
    }

    /**
     * Validate domain
     *
     * @param string $domain
     * @return boolean
     */
    private function isShopifyDomain(string $domain)
    {
        $shopUrl = parse_url($domain);
        $shopUrl = isset($shopUrl['host']) ? $shopUrl['host'] : $shopUrl['path'];

        $re = '/^[a-z0-9]+(?:-[a-z0-9]+)*(\.myshopify\.com)$/';

        return preg_match($re,$shopUrl);
    }

    /**
     * Validate Signature
     *
     * @param array $queryParams
     * @return boolean
     */
    private function validateSignature(array $queryParams)
    {
        $expectedSignature = $queryParams['signature'] ?? '';
        // First step: remove HMAC and signature keys
        unset($queryParams['signature']);
        // Second step: keys are sorted lexicographically
        ksort($queryParams);
        $pairs = [];
        foreach ($queryParams as $key => $value) {
            $value   = is_array($value) ? implode(',', $value) : $value;
            $pairs[] = $key . '=' . $value;
        }
        $key = implode('', $pairs);
        return hash_equals($expectedSignature, hash_hmac('sha256', $key, config('shopify.secret')));
    }
}
