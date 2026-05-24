<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Account;

class AuthController extends Controller
{

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $account = Auth::user();
            if ($account->role_id == 1) {
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return back()->with('error_popup', 'Vui lòng đăng nhập ở cổng Sinh viên/Giảng viên')->onlyInput('username');
            }
        }

        return back()->withErrors([
            'username' => 'Sai tài khoản hoặc mật khẩu.',
        ])->onlyInput('username');
    }

    public function showChangePassword()
    {
        return view('admin.change_password');
    }

    public function showUserChangePassword()
    {
        return view('user.change_password');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!\Illuminate\Support\Facades\Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'wrong']);
        }

        // Eloquent handles the model
        /** @var \App\Models\Account $user */
        $user->password = \Illuminate\Support\Facades\Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Mật khẩu đã được cập nhật thành công!');
    }

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Kiểm tra tài khoản có bị khóa không
        $account = Account::where('username', $credentials['username'])->first();
        if ($account && $account->is_locked) {
            $msg = 'Tài khoản đã bị khóa. Vui lòng liên hệ admin.';
            return back()->with('error', $msg)->onlyInput('username');
        }
        if ($account && $account->locked_until && now()->lessThan($account->locked_until)) {
            $diff = now()->diffInMinutes($account->locked_until);
            $msg = "Tài khoản tạm khóa trong {$diff} phút.";
            return back()->with('error', $msg)->onlyInput('username');
        }

        if (Auth::attempt($credentials)) {
            // Đăng nhập thành công: reset attempts using Account model
            if ($account) {
                $account->login_attempts = 0;
                $account->is_locked = false;
                $account->locked_until = null;
                $account->save();
                // Load role relationship for redirect logic
                $account->load('role');
                $user = $account; // use the Account instance as the authenticated user
            } else {
                // fallback to Auth::user() if for some reason $account is null
                $user = Auth::user();
                // role relationship is not needed for role_id check
            }

            $request->session()->regenerate();
            if ($user->role_id == 2) {
                return redirect()->route('teacher.home');
            } elseif ($user->role_id == 3) {
                return redirect()->route('student.home');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Tài khoản Admin vui lòng đăng nhập ở cổng Admin.',
                ])->onlyInput('username');
            }
        }

        // Đăng nhập thất bại: tăng số lần thử
        if ($account) {
            $account->login_attempts = $account->login_attempts + 1;
            // Xác định thời gian khóa dựa trên số lần thử
            $attempts = $account->login_attempts;
            $lockMinutes = null;
            if ($attempts >= 5 && $attempts < 10) $lockMinutes = 1;
            elseif ($attempts >= 10 && $attempts < 15) $lockMinutes = 2;
            elseif ($attempts >= 15 && $attempts < 20) $lockMinutes = 5;
            elseif ($attempts >= 20 && $attempts < 25) $lockMinutes = 10;
            elseif ($attempts >= 25 && $attempts < 30) $lockMinutes = 30;
            elseif ($attempts >= 30) {
                $account->is_locked = true;
                $account->locked_until = null;
            }
            if ($lockMinutes) {
                $account->locked_until = now()->addMinutes($lockMinutes);
            }
            $account->save();
        }

        return back()->withErrors([
            'username' => 'Sai tài khoản hoặc mật khẩu.',
        ])->onlyInput('username');
    }
}
