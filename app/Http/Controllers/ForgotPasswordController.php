<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Account;
use App\Models\Student;
use App\Models\Teacher;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('user.forgot_password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $email = $request->email;

        // Rate limit: Max 5 times per day (86400 seconds)
        $key = 'forgot-password:'.$email;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->withErrors(['email' => 'Bạn đã gửi yêu cầu quá nhiều lần trong ngày. Vui lòng thử lại vào ngày mai.'])->withInput();
        }

        // Kiểm tra email tồn tại trong bảng students hoặc teachers
        $student = Student::where('email', $email)->first();
        $teacher = Teacher::where('email', $email)->first();
        $accountId = null;

        if ($student) {
            $accountId = $student->account_id;
        } elseif ($teacher) {
            $accountId = $teacher->account_id;
        }

        if (!$accountId) {
            return back()->withErrors(['email' => 'Email không tồn tại trong hệ thống.'])->withInput();
        }

        // Tăng đếm Rate Limiter (thời gian sống 1 ngày)
        RateLimiter::hit($key, 86400);

        // Tạo token
        $token = Str::random(60);

        // Lưu vào DB (Upsert)
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => Hash::make($token), 'created_at' => Carbon::now()]
        );

        // Gửi email
        $resetUrl = route('password.reset', ['token' => $token, 'email' => $email]);

        // Lấy logo từ cấu hình hệ thống
        $siteLogo = \App\Models\SystemConfig::where('key', 'site_logo_base64')->value('value');
        if (!$siteLogo) {
            $siteLogo = 'https://lic.humg.edu.vn/App_Themes/humg/images/humg-logo.png';
        }

        $htmlContent = "
        <div style='font-family: Arial, sans-serif; background-color: #f4f7f6; padding: 40px 20px;'>
            <div style='max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 40px 30px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.05);'>
                <div style='text-align: center; margin-bottom: 30px;'>
                    <img src='{$siteLogo}' alt='Logo' style='height: 70px; max-width: 100%; object-fit: contain;'>
                </div>
                <h2 style='color: #1e293b; text-align: center; font-size: 24px; font-weight: 700; margin-bottom: 20px;'>Khôi phục mật khẩu</h2>
                <p style='color: #475569; font-size: 16px; line-height: 1.6; margin-bottom: 30px; text-align: center;'>
                    Chào bạn,<br><br>
                    Hệ thống nhận được yêu cầu khôi phục mật khẩu cho tài khoản của bạn. Vui lòng nhấp vào nút bên dưới để tiến hành đặt lại mật khẩu.
                </p>
                <div style='text-align: center; margin: 35px 0;'>
                    <a href='{$resetUrl}' style='background-color: #2563eb; color: #ffffff; padding: 14px 32px; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px; display: inline-block; transition: background-color 0.3s;'>
                        Nhấn vào đây để đổi mật khẩu
                    </a>
                </div>
                <p style='color: #ef4444; font-size: 14px; text-align: center; margin-bottom: 30px; font-weight: 500;'>
                    * Lưu ý: Đường link này chỉ có hiệu lực trong vòng 15 phút.
                </p>
                <hr style='border: none; border-top: 1px dashed #cbd5e1; margin: 20px 0;'>
                <p style='color: #94a3b8; font-size: 13px; text-align: center; line-height: 1.5;'>
                    Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua email này.<br>
                    Đây là email tự động, vui lòng không phản hồi.
                </p>
            </div>
        </div>
        ";
        
        try {
            Mail::html($htmlContent, function ($message) use ($email) {
                $message->to($email)
                        ->subject('Khôi phục mật khẩu - Hệ thống Quản lý Đào tạo');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Lỗi máy chủ gửi email. Vui lòng liên hệ Admin để cấu hình SMTP (Cài đặt chung).'])->withInput();
        }

        return back()->with('status', 'Chúng tôi đã gửi đường link khôi phục mật khẩu vào email của bạn!');
    }

    public function showResetForm(Request $request, $token)
    {
        return view('user.reset_password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record) {
            return back()->withErrors(['email' => 'Token không hợp lệ.']);
        }

        // Kiểm tra token (Tồn tại trong 15 phút)
        $expiresAt = Carbon::parse($record->created_at)->addMinutes(15);
        if (Carbon::now()->greaterThan($expiresAt)) {
            return back()->withErrors(['email' => 'Đường link khôi phục đã hết hạn (quá 15 phút). Vui lòng yêu cầu lại.']);
        }

        if (!Hash::check($request->token, $record->token)) {
            return back()->withErrors(['email' => 'Token không chính xác.']);
        }

        // Tìm account_id
        $student = Student::where('email', $request->email)->first();
        $teacher = Teacher::where('email', $request->email)->first();
        $accountId = $student ? $student->account_id : ($teacher ? $teacher->account_id : null);

        if (!$accountId) {
            return back()->withErrors(['email' => 'Không tìm thấy tài khoản.']);
        }

        // Đổi mật khẩu
        $account = Account::find($accountId);
        if ($account) {
            $account->password = Hash::make($request->password);
            $account->save();
        }

        // Xóa token
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('user.login')->with('success_popup', 'Mật khẩu đã được đặt lại thành công. Vui lòng đăng nhập!');
    }
}
