<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Gỡ bỏ cột khỏi semesters (nếu có do lần migrate trước)
        if (Schema::hasColumns('semesters', ['start_date', 'end_date'])) {
            Schema::table('semesters', function (Blueprint $table) {
                $table->dropColumn(['start_date', 'end_date']);
            });
        }

        // 2. Gỡ bỏ cột khỏi schedule_sessions (nếu vẫn còn)
        if (Schema::hasColumns('schedule_sessions', ['start_date', 'end_date'])) {
            Schema::table('schedule_sessions', function (Blueprint $table) {
                $table->dropColumn(['start_date', 'end_date']);
            });
        }

        // 3. Thêm cột vào schedules
        if (!Schema::hasColumns('schedules', ['start_date', 'end_date'])) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->date('start_date')->nullable()->after('current_capacity');
                $table->date('end_date')->nullable()->after('start_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumns('schedules', ['start_date', 'end_date'])) {
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn(['start_date', 'end_date']);
            });
        }
    }
};
