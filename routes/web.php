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
});


//route for student
Route::prefix('student')->middleware('role:3')->group(function () {
    Route::get('/home', function () {
        return view('user.Student.home');
    })->name('student.home');


    Route::get('/info', function () {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('student.classroom.faculty');
        $student = $account->student;
        return view('user.Student.info', compact('student'));
    })->name('student.info');

    Route::get('/schedule', function () {
        return view('user.Student.schedule');
    })->name('student.schedule');

    Route::get('/grades', function () {
        return view('user.Student.grade');
    })->name('student.grades');

    Route::get('/enrollment', function () {
        return view('user.Student.enrollment');
    })->name('student.enrollment');

    Route::get('/attendance', function () {
        return view('user.Student.attendance_list');
    })->name('student.attendance');

    Route::get('/tuition', function () {
        return view('user.Student.tuition_fee');
    })->name('student.tuition');

    Route::get('/payment', function () {
        return view('user.Student.payment');
    })->name('student.payment');


    Route::get('/feedback', function () {
        return view('user.Student.feedback');
    })->name('student.feedback');
});

//route for teacher
Route::prefix('teacher')->middleware('role:2')->group(function () {
    Route::get('/home', function () {
        return view('user.Teacher.home');
    })->name('teacher.home');

    Route::get('/info', function () {
        /** @var \App\Models\Account $account */
        $account = Auth::user();
        $account->load('teacher.faculty');
        $teacher = $account->teacher;
        return view('user.Teacher.info', compact('teacher'));
    })->name('teacher.info');

    Route::get('/schedule', function () {
        return view('user.Teacher.schedule');
    })->name('teacher.schedule');

    Route::get('/classes', function () {
        return view('user.Teacher.class_list');
    })->name('teacher.classes');

    Route::get('/students', function () {
        return view('user.Teacher.student_list');
    })->name('teacher.students');

    Route::get('/attendances', function () {
        return view('user.Teacher.student_list');
    })->name('teacher.attendances');

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
