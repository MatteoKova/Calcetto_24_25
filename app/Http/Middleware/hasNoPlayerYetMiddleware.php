<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class hasNoPlayerYetMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->role === 'admin' || $user->player) {
            return response()->view('errors.hasPlayer', ['message'=>'Only registered users without a player can access'], 403);
        }
        return $next($request);
    }
}
