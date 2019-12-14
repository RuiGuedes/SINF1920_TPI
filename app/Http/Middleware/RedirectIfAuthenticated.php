<?php

namespace App\Http\Middleware;

use App\PickingWaves;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if(Auth::check() && Auth::id() === 1) return 'manager/salesOrders';
            else if(Auth::check()){
                $unfinished_wave = PickingWaves::getUserPickingWave(Auth::id());
                return ($unfinished_wave == null) ? 'clerk/pickingWaves' : 'clerk/pickingRoute/'. $unfinished_wave->id;
            }
        }

        return $next($request);
    }
}
