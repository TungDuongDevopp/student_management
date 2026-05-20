<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('user.login');
});

// User Login (Sinh viên, Giảng viên)
Route::get('/login', function () {
    if (Auth::check()) {
        $role = Auth::user()->role_id;
        if ($role == 2) return redirect()->route('teacher.home');
        if ($role == 3) return redirect()->route('student.home');
        if ($role == 1) return redirect()->route('admin.dashboard');
    }
    return view('user.login');
})->name('user.login');
Route::post('/login', [AuthController::class, 'userLogin'])->name('user.login.post');

Route::middleware('auth')->group(function () {
    Route::get('/news', function () {
        $target = (Auth::user()->role_id == 2) ? 'teacher' : 'student';
        $news = \App\Models\News::where('is_published', true)
            ->whereIn('target_audience', [$target, 'all'])
            ->orderBy('created_at', 'desc')
            ->get();
        return view('user.news_index', compact('news'));
    })->name('user.news.index');

    Route::get('/news/{id}', function ($id) {
        $article = \App\Models\News::findOrFail($id);
        return view('user.news_show', compact('article'));
    })->name('user.news.show');
});

// Admin Login
Route::get('/admin/login', function () {
    if (Auth::check() && Auth::user()->role_id == 1) {
        return redirect()->route('admin.dashboard');
    }
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

    Route::get('/grades', function () {
        return view('admin.grade_management');
    })->name('admin.grades');

    Route::get('/news', function () {
        $news = \App\Models\News::orderBy('created_at', 'desc')->get();
        return view('admin.news_management', compact('news'));
    })->name('admin.news');
});


use App\Http\Controllers\StudentHomeController;

//route for student
Route::prefix('student')->middleware('role:3')->group(function () {
    Route::get('/home', [StudentHomeController::class, 'index'])->name('student.home');


    Route::get('/info', [StudentHomeController::class, 'info'])->name('student.info');

    Route::get('/schedule', [StudentHomeController::class, 'schedule'])->name('student.schedule');

    Route::get('/grades', [StudentHomeController::class, 'grades'])->name('student.grades');

    Route::get('/enrollment', [StudentHomeController::class, 'enrollment'])->name('student.enrollment');
    Route::post('/enrollment', [StudentHomeController::class, 'submitEnrollment'])->name('student.enrollment.submit');
    Route::get('/enrollment/search', [StudentHomeController::class, 'searchSchedules'])->name('student.enrollment.search');

    Route::get('/attendance', [StudentHomeController::class, 'attendance'])->name('student.attendance');

    Route::get('/tuition', [StudentHomeController::class, 'tuition'])->name('student.tuition');

    Route::get('/payment', [StudentHomeController::class, 'payment'])->name('student.payment');


    Route::get('/feedback', [StudentHomeController::class, 'feedback'])->name('student.feedback');
});

//route for teacher
Route::prefix('teacher')->middleware('role:2')->group(function () {
    Route::get('/home', [\App\Http\Controllers\TeacherHomeController::class, 'index'])->name('teacher.home');

    Route::get('/info', [\App\Http\Controllers\TeacherHomeController::class, 'info'])->name('teacher.info');

    Route::get('/schedule', [\App\Http\Controllers\TeacherHomeController::class, 'schedule'])->name('teacher.schedule');

    Route::get('/classes', [\App\Http\Controllers\TeacherHomeController::class, 'classes'])->name('teacher.classes');

    Route::get('/students', [\App\Http\Controllers\TeacherHomeController::class, 'students'])->name('teacher.students');

    Route::get('/attendances', [\App\Http\Controllers\TeacherHomeController::class, 'attendances'])->name('teacher.attendances');
    Route::post('/attendances/save', [\App\Http\Controllers\TeacherHomeController::class, 'saveAttendances'])->name('teacher.attendances.save');

    Route::get('/grades', [\App\Http\Controllers\TeacherHomeController::class, 'grades'])->name('teacher.grades');
    Route::post('/grades/save', [\App\Http\Controllers\TeacherHomeController::class, 'saveGrades'])->name('teacher.grades.save');

    Route::get('/feedback', function () {
        return view('user.Teacher.feedback');
    })->name('teacher.feedback');
});

// Logout
Route::post('/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect()->route('user.login');
})->name('logout');

Route::post('/admin/logout', function () {
    Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
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
            $tuition = \App\Models\Tuition::where('student_id', $student->id)->first();
            if ($tuition) {
                $tuition->paid_amount = min($tuition->total_amount, $tuition->paid_amount + $amount);
                $tuition->save();

                // Ghi nhận payment
                \App\Models\Payment::create([
                    'tuition_id' => $tuition->id,
                    'amount'     => $amount,
                    'payment_date' => now(),
                ]);
            }
        }
    }
    return response()->json(['success' => true]);
});

// Kiểm tra / Gửi yêu cầu thanh toán của sinh viên (Chờ duyệt)
Route::post('/api/payment/check', function (\Illuminate\Http\Request $request) {
    try {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE payments ADD COLUMN status VARCHAR(20) NOT NULL DEFAULT 'completed'");
    } catch (\Exception $e) {
        // Bỏ qua
    }

    $studentCode = $request->input('student_code');
    $student = \App\Models\Student::where('student_code', $studentCode)->first();

    if ($student) {
        $activeSemester = \App\Models\Semester::where('status', 1)->first() ?? \App\Models\Semester::latest()->first();
        if (!$activeSemester) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy học kỳ hiện tại.']);
        }

        $tuition = \App\Models\Tuition::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->first();

        if ($tuition) {
            $remaining = $tuition->total_amount - $tuition->paid_amount;
            if ($remaining <= 0) {
                return response()->json(['success' => false, 'message' => 'Học phí đã được đóng đủ!']);
            }

            // Kiểm tra xem đã có giao dịch đang chờ duyệt chưa
            $hasPending = \App\Models\Payment::where('tuition_id', $tuition->id)
                ->where('status', 'pending')
                ->exists();

            if ($hasPending) {
                return response()->json(['success' => false, 'message' => 'Bạn đã gửi yêu cầu thanh toán trước đó. Vui lòng chờ hệ thống duyệt.']);
            }

            // Tạo giao dịch ở trạng thái chờ duyệt (pending)
            \App\Models\Payment::create([
                'tuition_id'   => $tuition->id,
                'amount'       => $remaining,
                'payment_date' => now(),
                'status'       => 'pending',
            ]);

            return response()->json(['success' => true, 'message' => 'Gửi yêu cầu thanh toán thành công! Vui lòng chờ hệ thống duyệt.']);
        }
    }
    return response()->json(['success' => false, 'message' => 'Không tìm thấy thông tin học phí sinh viên']);
})->name('payment.check');
