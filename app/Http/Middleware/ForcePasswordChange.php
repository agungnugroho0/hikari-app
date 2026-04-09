<?php

namespace App\Http\Middleware;

use App\Models\Staff;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if (! $user instanceof Staff || ! Hash::check('123456', $user->password)) {
            return $next($request);
        }

        $allowedRoutes = [
            'logout',
            'setelan',
            'sensei.profil',
        ];

        if ($request->routeIs($allowedRoutes)) {
            return $next($request);
        }

        return match ($user->akses) {
            'admin' => redirect()->route('setelan'),
            'guru' => redirect()->route('sensei.profil'),
            default => redirect()->route('login'),
        };
    }
}
