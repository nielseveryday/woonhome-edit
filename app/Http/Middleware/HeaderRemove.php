<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Symfony\Component\HttpFoundation\Response;

class HeaderRemove
{
    /**
     * Remove possible double Access-Control-Allow-Origin in header
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     *
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        //$request->headers->remove('Access-Control-Allow-Origin');
        //return $next($request);

        return $next($request)->header('Access-Control-Allow-Origin', '*');
    }
}
