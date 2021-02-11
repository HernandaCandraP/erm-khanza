<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('guru')->user()->level == "Admin") {
            return $next($request);
        }
        return redirect('/404');
    }
}
