<?php

namespace Bryanyeh\Shopify\Middleware;

use Closure;

class VerifyWebHook
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
        $data = $request->getContent();
        $hmacHeader = $request->server('HTTP_X_SHOPIFY_HMAC_SHA256');
        $calculatedHmac = base64_encode(hash_hmac('sha256', $data, config('shopify.secret'), true));

        if($hmacHeader != $calculatedHmac){
            return response(401);
        }
            
        return $next($request);
    }
}
