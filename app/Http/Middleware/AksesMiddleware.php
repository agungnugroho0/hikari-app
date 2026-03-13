<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AksesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,... $akses)
    {
        $user = Auth::user();
        // belum login
        if (!$user):
            return redirect()->route('login');
        endif;

        if (!in_array($user->akses,$akses)){
            abort(403,'Silahkan hubungi admin/developer');
        }
        return $next($request);
    }
}
