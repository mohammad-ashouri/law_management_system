<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NTCPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $NTCPFieldValue = User::where('username', session('username'))->value('NTCP');

        if ($NTCPFieldValue === 1) {
            return redirect()->route('Profile');
        }

        return $next($request);
    }
}
