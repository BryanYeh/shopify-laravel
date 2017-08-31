<?php

namespace Bryanyeh\Shopify\Middleware;

use Closure;

class VerifyRequest
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
        if(!$this->isShopifyDomain($request->input('shop')) || !$this->validateHmac($request->all())){
            return redirect()->route('re-auth');
        }
            
        return $next($request);
    }

    /**
     * Validate domain
     *
     * @param string $domain
     * @return boolean
     */
    protected function isShopifyDomain(string $domain)
    {
        $shopUrl = parse_url($domain);
        $shopUrl = isset($shopUrl['host']) ? $shopUrl['host'] : $shopUrl['path'];

        $re = '/^[a-z0-9]+(?:-[a-z0-9]+)*(\.myshopify\.com)$/';

        return preg_match($re,$shopUrl);
    }

    /**
     * Validate HMAC
     *
     * @param array $queryParams
     * @return boolean
     */
    protected function validateHmac(array $queryParams)
    {
        $expectedHmac = $queryParams['hmac'] ?? '';
        // First step: remove HMAC and signature keys
        unset($queryParams['hmac']);
        // Second step: keys are sorted lexicographically
        ksort($queryParams);
        $pairs = [];
        foreach ($queryParams as $key => $value) {
            // Third step: "&" and "%" are replaced by "%26" and "%25" in keys and values, and in addition
            // "=" is replaced by "%3D" in keys
            $key   = strtr($key, ['&' => '%26', '%' => '%25', '=' => '%3D']);
            $value = strtr($value, ['&' => '%26', '%' => '%25']);
            $pairs[] = $key . '=' . $value;
        }
        $key = implode('&', $pairs);
        return hash_equals($expectedHmac, hash_hmac('sha256', $key, config('shopify.secret')));
    }
}
