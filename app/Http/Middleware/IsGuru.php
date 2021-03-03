<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsGuru
{
    public function handle($request, Closure $next)
    {
        if(Auth::guard('guru')->user()->level == "Guru") {
            return $next($request);
        }
        return redirect('/404');
    }
}
