<?php

use Illuminate\Support\Facades\Route;

// ==========================================
// LOGIN
// ==========================================
Route::get('/', function () {
    return view('login');
});
Route::get('/setting', function () {
    return view('setting');
});
Route::get('/login', function () {
    return view('login');
})->name('login');

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::get('/students', function () {
        return view('admin.student_management');
    })->name('admin.students');

    Route::get('/accounts', function () {
        return view('admin.account_management');
    })->name('admin.accounts');

    Route::get('/classes', function () {
        return view('admin.class_management');
    })->name('admin.classes');

    Route::get('/subjects', function () {
        return view('admin.subject_management');
    })->name('admin.subjects');

    Route::get('/grades', function () {
        return view('admin.grade_management');
    })->name('admin.grades');

    Route::get('/fees', function () {
        return view('admin.fee_management');
    })->name('admin.fees');

    Route::get('/schedules', function () {
        return view('admin.schedule_management');
    })->name('admin.schedules');

    Route::get('/announcements', function () {
        return view('admin.anouncement');
    })->name('admin.announcements');

    Route::get('/feedbacks', function () {
        return view('admin.feedback_management');
    })->name('admin.feedbacks');

    Route::get('/config', function () {
        return view('admin.config');
    })->name('admin.config');
});

// ==========================================
// STUDENT ROUTES
// ==========================================
Route::prefix('student')->group(function () {
    Route::get('/home', function () {
        return view('user.Student.home');
    })->name('student.home');

    Route::get('/info', function () {
        return view('user.Student.info');
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

    Route::get('/announcements', function () {
        return view('user.Student.anouncement');
    })->name('student.announcements');

    Route::get('/feedback', function () {
        return view('user.Student.feedback');
    })->name('student.feedback');
});

// ==========================================
// TEACHER ROUTES
// ==========================================
Route::prefix('teacher')->group(function () {
    Route::get('/home', function () {
        return view('user.Teacher.home');
    })->name('teacher.home');

    Route::get('/info', function () {
        return view('user.Teacher.info');
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

    Route::get('/grades', function () {
        return view('user.Teacher.grade_list');
    })->name('teacher.grades');

    Route::get('/announcements', function () {
        return view('user.Teacher.announcement');
    })->name('teacher.announcements');

    Route::get('/feedback', function () {
        return view('user.Teacher.feedback');
    })->name('teacher.feedback');
});
// Logout
Route::get('/logout', function () {
    return view('login');
})->name('logout');
