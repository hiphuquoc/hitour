<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class RedirectWwwToNonWww {

    public function handle(Request $request, Closure $next){
        if (substr($request->header('host'), 0, 4) == 'www.') {
            $domainName     = str_replace('https://', '', env('APP_URL'));
            $request->headers->set('host', $domainName);
            return Redirect::to($request->path(), 301);
        }
        return $next($request);
    }

}