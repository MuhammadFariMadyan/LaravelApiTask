<?php

namespace App\Http\Middleware;

use App\Utils\AppConstant;
use Closure;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
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
        $auth = Auth::guard(AppConstant::GUARD_ADMIN);
        if (!$auth->check()) {
            return redirect(AppConstant::ADMIN_URL_PREFIX);
        }
        return $next($request);
    }
}
