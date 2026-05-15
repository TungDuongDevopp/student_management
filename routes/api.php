<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\FacultyController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\SemesterController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\TuitionController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\FeedbackController;
use App\Http\Controllers\Api\SystemConfigController;
use App\Http\Controllers\Api\FacultyConfigController;

Route::get('system-configs', [SystemConfigController::class, 'index']);
Route::put('system-configs', [SystemConfigController::class, 'updateConfigs']);

Route::get('faculty-configs', [FacultyConfigController::class, 'index']);
Route::put('faculty-configs', [FacultyConfigController::class, 'updateConfigs']);

Route::apiResource('roles', RoleController::class);
Route::apiResource('accounts', AccountController::class);
Route::apiResource('faculties', FacultyController::class);
Route::apiResource('classrooms', ClassroomController::class);
Route::apiResource('students', StudentController::class);
Route::apiResource('teachers', TeacherController::class);
Route::apiResource('subjects', SubjectController::class);
Route::apiResource('rooms', RoomController::class);
Route::apiResource('semesters', SemesterController::class);
Route::apiResource('schedules', ScheduleController::class);
Route::apiResource('enrollments', EnrollmentController::class);
Route::apiResource('attendances', AttendanceController::class);
Route::apiResource('tuitions', TuitionController::class);
Route::apiResource('payments', PaymentController::class);

// Feedback: custom actions trước resource routes
Route::post('feedbacks/{id}/reply', [FeedbackController::class, 'reply']);
Route::patch('feedbacks/{id}/seen',  [FeedbackController::class, 'markAsSeen']);
// HS/GV dùng POST để tạo feedback; admin chỉ xem & phản hồi qua giao diện
Route::apiResource('feedbacks', FeedbackController::class);
