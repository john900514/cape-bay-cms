<?php

namespace App\Http\Middleware;

use Closure;

class CORS
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

        // ALLOW OPTIONS METHOD
        $headers = [
            "Access-Control-Allow-Origin"   => "*",
            'Access-Control-Allow-Methods'  => 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'  => '*'
        ];
        if ($request->getMethod() == "OPTIONS") {
            // The client-side application can set only headers allowed in Access-Control-Allow-Headers
            return response('OK', 200, $headers);
        }

        $path = explode('/', $request->getPathInfo());
        /*
        if (array_get($path,2,false) == 'consumer' && array_get($path,3,false)!='login') {
            // $the_user = \Auth::guard('consumer')->user();
            // if ($the_user) {
            //    $request->headers->set('mzregistration', $the_user->uuid);
            // }
        }
        */

        $response = $next($request);
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
