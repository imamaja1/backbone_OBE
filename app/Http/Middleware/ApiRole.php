<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (User::isRole($role)) {
            return $next($request);
        }
        return response()->json([
            'message' =>'access denied'
        ],405);
    }
}
