<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột created_at và updated_at cho tất cả các bảng chưa có
     */
    public function up(): void
    {
        $tables = [
            'roles',
            'accounts',
            'faculties',
            'classrooms',
            'students',
            'teachers',
            'subjects',
            'rooms',
            'semesters',
            'schedules',
            'enrollments',
            'attendances',
            'tuitions',
            'payments',
            'feedbacks',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $bp) use ($table) {
                    if (!Schema::hasColumn($table, 'created_at')) {
                        $bp->timestamp('created_at')->nullable();
                    }
                    if (!Schema::hasColumn($table, 'updated_at')) {
                        $bp->timestamp('updated_at')->nullable();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'roles',
            'accounts',
            'faculties',
            'classrooms',
            'students',
            'teachers',
            'subjects',
            'rooms',
            'semesters',
            'schedules',
            'enrollments',
            'attendances',
            'tuitions',
            'payments',
            'feedbacks',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $bp) use ($table) {
                    $columns = [];
                    if (Schema::hasColumn($table, 'created_at')) $columns[] = 'created_at';
                    if (Schema::hasColumn($table, 'updated_at')) $columns[] = 'updated_at';
                    if (!empty($columns)) {
                        $bp->dropColumn($columns);
                    }
                });
            }
        }
    }
};
