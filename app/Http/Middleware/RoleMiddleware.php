<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle($request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        if (Auth::user()->vaiTro->ten_vai_tro !== $role) {
            abort(403, 'Không có quyền truy cập');
        }

        return $next($request);
    }
}