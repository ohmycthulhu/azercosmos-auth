<?php

namespace App\Http\Middleware;

use Closure;

class SynchronizationMiddleware
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
        if (!$request->header('KEY')
            || $request->header('KEY') != env('SYNCHRONIZATION_KEY')) {
            return response('No key', 403);
        }
        return $next($request);
    }
}
