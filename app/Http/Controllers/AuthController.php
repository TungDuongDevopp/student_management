<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function userLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $account = Auth::user();
            $request->session()->regenerate();

            if ($account->role_id == 2) {
                return redirect()->route('teacher.home');
            } elseif ($account->role_id == 3) {
                return redirect()->route('student.home');
            } else {
                Auth::logout();
                return back()->withErrors([
                    'username' => 'Tài khoản Admin vui lòng đăng nhập ở cổng Admin.',
                ])->onlyInput('username');
            }
        }

        return back()->withErrors([
            'username' => 'Sai tài khoản hoặc mật khẩu.',
        ])->onlyInput('username');
    }
}
