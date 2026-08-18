<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsCoordinator
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::guard('web')->user();
        if ($user && \App\Models\Coordinator::where('user_id', $user->id)->exists()) {
            return $next($request);
        }

        Auth::guard('web')->logout();
        return redirect()->route('admin.login')->with('error', 'You do not have coordinator access.');
    }
}
