<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Xóa cột semester_id khỏi classrooms.
     * Lớp hành chính tồn tại xuyên suốt 4 năm, không thuộc 1 học kỳ cụ thể.
     * semester_id chỉ phù hợp với bảng schedules (lớp học phần).
     */
    public function up(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn('semester_id');
        });
    }

    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->unsignedBigInteger('semester_id')->nullable()->after('teacher_id');
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('set null');
        });
    }
};
