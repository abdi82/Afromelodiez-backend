<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && Auth::user()->user_role == 'superAdmin') {
                return redirect('admin/dashboard')->with('message','Entered Super dashboard');
            }
            elseif(Auth::guard($guard)->check() && Auth::user()->user_role == 'admin'){
                  return redirect('admin/artistDashboard');
            }
        } 
            return $next($request);
        
    }
}
