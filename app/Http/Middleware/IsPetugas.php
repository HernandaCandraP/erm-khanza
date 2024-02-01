<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsPetugas
{
    public function handle($request, Closure $next)
    {
        if (Auth::guard('pegawai')->user()->level == "Petugas") {
            return $next($request);
        }
        return redirect('/404');
    }
}
