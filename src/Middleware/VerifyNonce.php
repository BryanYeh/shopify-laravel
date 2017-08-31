<?php

namespace Bryanyeh\Shopify\Middleware;

use Closure;

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

            return redirect()->route('re-auth');
        }
        return $next($request);
    }

}