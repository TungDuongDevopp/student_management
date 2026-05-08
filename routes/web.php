<?php

use Illuminate\Support\Facades\Route;
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


Route::prefix('admin')->group(function () {
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


Route::prefix('student')->group(function () {
    Route::get('/home', function () {
        return view('user.Student.home');
    })->name('student.home');
    
    Route::get('/announcements', function () {
    return view('user.Student.announcement'); 
    })->name('student.announcements');

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


    Route::get('/feedback', function () {
        return view('user.Student.feedback');
    })->name('student.feedback');
});


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
    return redirect()->route('user.login');
})->name('logout');

//Setting
Route::get('/setting', function () {
    return view('setting');
})->name('setting');
