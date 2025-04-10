<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class IsDokter
{
    public function handle($request, Closure $next)
    {
        if(Auth::guard('pegawai')->user()->level == "Dokter" || Auth::guard('pegawai')->user()->level == "Apotek") {
            return $next($request);
        }
        return redirect('/404');
    }
}
