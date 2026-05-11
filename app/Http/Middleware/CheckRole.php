<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Kiểm tra đã đăng nhập và đúng role.
     *
     * Cách dùng trong route:
     *   middleware('role:1')       → chỉ Admin
     *   middleware('role:2')       → chỉ Giảng viên
     *   middleware('role:3')       → chỉ Sinh viên
     *   middleware('role:2,3')     → GV hoặc SV
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // 1. Chưa đăng nhập
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            // Redirect về đúng trang login theo URL prefix
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
            }

            return redirect()->route('user.login')->with('error', 'Vui lòng đăng nhập để tiếp tục.');
        }

        // 2. Đã đăng nhập nhưng sai role
        if (!empty($roles) && !in_array((string) Auth::user()->role_id, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }

            // Redirect về trang chủ đúng với role hiện tại
            return match ((int) Auth::user()->role_id) {
                1 => redirect()->route('admin.dashboard'),
                2 => redirect()->route('teacher.home'),
                3 => redirect()->route('student.home'),
                default => redirect()->route('user.login'),
            };
        }

        return $next($request);
    }
}
