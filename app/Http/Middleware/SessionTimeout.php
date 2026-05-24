<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $timeout = 30; // minutes of inactivity before auto logout
        if (Auth::check()) {
            $lastActivity = $request->session()->get('last_activity');
            if ($lastActivity) {
                $inactive = Carbon::now()->diffInMinutes(Carbon::parse($lastActivity));
                if ($inactive >= $timeout) {
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return redirect()->route('login')->with('error', 'Phiên làm việc đã hết thời gian, vui lòng đăng nhập lại.');
                }
            }
            $request->session()->put('last_activity', Carbon::now());
        }
        return $next($request);
    }
}
?>
