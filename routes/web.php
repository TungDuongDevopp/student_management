<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('user.login');
});

// User Login (Sinh viên, Giảng viên)
Route::get('/login', function () {
    return view('user.login');
})->name('user.login');
Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.post');

// Admin Login
Route::get('/admin/login', function () {
    return view('admin.login');
})->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.post');

//route for admin
Route::prefix('admin')->middleware('role:1')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/info', function () {
        return view('admin.info');
    })->name('admin.info');

    Route::get('/students', function () {
        return view('admin.student_management');
    })->name('admin.students');

    Route::get('/teachers', function () {
        return view('admin.teacher_management');
    })->name('admin.teachers');

    Route::get('/accounts', function () {
        return view('admin.account_management');
    })->name('admin.accounts');

    Route::get('/classes', function () {
        return view('admin.class_management');
    })->name('admin.classes');

    Route::get('/subjects', function () {
        return view('admin.subject_management');
    })->name('admin.subjects');

    Route::get('/enrollment', function () {
        return view('admin.enrollment_management');
    })->name('admin.enrollments');

    Route::get('/fees', function () {
        return view('admin.fee_management');
    })->name('admin.fees');

    Route::get('/schedules', function () {
        return view('admin.schedule_management');
    })->name('admin.schedules');

    Route::get('/feedbacks', function () {
        return view('admin.feedback_management');
    })->name('admin.feedbacks');

    Route::get('/rooms', function () {
        return view('admin.room_management');
    })->name('admin.rooms');

    Route::get('/faculties', function () {
        return view('admin.faculty_management');
    })->name('admin.faculties');

    Route::get('/semesters', function () {
        return view('admin.semester_management');
    })->name('admin.semesters');

    Route::get('/faculty-generals', function () {
        return view('admin.faculty_generals');
    })->name('admin.faculty-generals');

    Route::get('/system-configs', function () {
        return view('admin.system_configs');
    })->name('admin.system-configs');
});


use App\Http\Controllers\StudentHomeController;

//route for student
Route::prefix('student')->middleware('role:3')->group(function () {
    Route::get('/home', [StudentHomeController::class, 'index'])->name('student.home');


    Route::get('/info', [StudentHomeController::class, 'info'])->name('student.info');

    Route::get('/schedule', [StudentHomeController::class, 'schedule'])->name('student.schedule');

    Route::get('/grades', [StudentHomeController::class, 'grades'])->name('student.grades');

    Route::get('/enrollment', [StudentHomeController::class, 'enrollment'])->name('student.enrollment');

    Route::get('/attendance', [StudentHomeController::class, 'attendance'])->name('student.attendance');

    Route::get('/tuition', [StudentHomeController::class, 'tuition'])->name('student.tuition');

    Route::get('/payment', function () {
        return view('user.Student.payment');
    })->name('student.payment');


    Route::get('/feedback', [StudentHomeController::class, 'feedback'])->name('student.feedback');
});

//route for teacher
Route::prefix('teacher')->middleware('role:2')->group(function () {
    Route::get('/home', [\App\Http\Controllers\TeacherHomeController::class, 'index'])->name('teacher.home');

    Route::get('/info', [\App\Http\Controllers\TeacherHomeController::class, 'info'])->name('teacher.info');

    Route::get('/schedule', [\App\Http\Controllers\TeacherHomeController::class, 'schedule'])->name('teacher.schedule');

    Route::get('/classes', function () {
        return view('user.Teacher.class_list');
    })->name('teacher.classes');

    Route::get('/students', function () {
        return view('user.Teacher.student_list');
    })->name('teacher.students');

    Route::get('/attendances', function () {
        return view('user.Teacher.student_list');
    })->name('teacher.attendances');

    Route::get('/grades', function () {
        return view('user.Teacher.grade_list');
    })->name('teacher.grades');

    Route::get('/feedback', function () {
        return view('user.Teacher.feedback');
    })->name('teacher.feedback');
    Route::get('/grades', function () {
        return view('user.Teacher.student_list');
    })->name('teacher.grades');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('user.login');
})->name('logout');

Route::post('/admin/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('admin.login');
})->name('admin.logout');

// ===== PAYMENT API =====
// SePay Webhook — nhận callback khi có giao dịch thành công
Route::post('/api/payment/webhook', function (\Illuminate\Http\Request $request) {
    $content = $request->input('content') ?? '';
    $amount  = $request->input('transferAmount') ?? 0;

    // Tách mã sinh viên từ nội dung: "HOCPHI SV001 HK2-2025"
    preg_match('/HOCPHI\s+(\S+)/i', $content, $matches);
    $studentCode = $matches[1] ?? null;

    if ($studentCode) {
        $student = \App\Models\Student::where('student_code', $studentCode)->first();
        if ($student) {
            // Ghi nhận payment
            \App\Models\Payment::create([
                'student_id' => $student->id,
                'amount'     => $amount,
                'method'     => 'bank_transfer',
                'transaction_code' => $request->input('referenceCode') ?? uniqid('TX'),
                'status'     => 'paid',
                'note'       => $content,
            ]);
        }
    }
    return response()->json(['success' => true]);
})->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

// Kiểm tra trạng thái thanh toán của sinh viên
Route::post('/api/payment/check', function (\Illuminate\Http\Request $request) {
    // Placeholder — kết nối DB thật sau
    return response()->json(['success' => false, 'message' => 'Chưa nhận được thanh toán']);
})->name('payment.check');
