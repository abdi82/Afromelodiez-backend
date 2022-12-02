<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdmin
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
        if (Auth::user() &&  Auth::user()->user_role == 'superAdmin' || Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'manager'|| Auth::user()->user_role == 'artist') {
             //$request['role']=Auth::user()->user_role;
             return $next($request);
        }

        return redirect('/')->with('error','You have not admin access');
    }
}