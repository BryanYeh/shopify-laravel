<?php

namespace Bryanyeh\Shopify\Middleware;

use Closure;
use Bryanyeh\Shopify\Exceptions\InvalidNonceException;

class VerifyNonce
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
        if($request->session()->has('nonce') && session('nonce') != $request->input('state')){
            
            $request->session()->forget('nonce');

            throw new InvalidNonceException();
        }
        return $next($request);
    }

}