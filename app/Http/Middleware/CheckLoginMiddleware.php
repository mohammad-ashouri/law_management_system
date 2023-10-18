<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckLoginMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() and session('username')) {
            return $next($request);
        }

        return redirect()->route('login');
    }
}
