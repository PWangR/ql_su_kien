<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Nếu không có vai trò, deny
        if (!$user->vaiTro) {
            abort(403, 'Không có quyền truy cập');
        }

        $tenVaiTro = $user->vaiTro->ten_vai_tro;

        if (!in_array($tenVaiTro, $roles)) {
            abort(403, 'Bạn không có quyền truy cập trang này');
        }

        return $next($request);
    }
}
