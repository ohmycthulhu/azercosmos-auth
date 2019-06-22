<?php


namespace App\Http\Middleware;


use Closure;

class CheckAuthentication
{
    public function handle($request, Closure $next){
        $user_id = app()->id;
        if (!$user_id) {
            return response('User is not authenticated', 466);
        } else {
            return $next($request);
        }
    }
}