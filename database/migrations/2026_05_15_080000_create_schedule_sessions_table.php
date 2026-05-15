<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 1. Tạo bảng schedule_sessions (nhiều buổi/tuần cho 1 lịch)
     * 2. Xóa day_of_week, start_time, end_time khỏi schedules
     */
    public function up(): void
    {
        // Xóa bảng nếu đã tồn tại từ lần chạy lỗi trước
        Schema::dropIfExists('schedule_sessions');

        // 1. Tự động lấy kiểu dữ liệu chính xác của schedules.id từ MySQL
        $columnInfo = \Illuminate\Support\Facades\DB::select("
            SELECT COLUMN_TYPE 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'schedules' 
              AND COLUMN_NAME = 'id'
        ")[0]->COLUMN_TYPE;

        // 2. Tạo bảng schedule_sessions
        Schema::create('schedule_sessions', function (Blueprint $table) use ($columnInfo) {
            $table->id();
            
            // Ép kiểu schedule_id khớp chính xác 100% với schedules.id
            if (str_contains(strtolower($columnInfo), 'bigint')) {
                if (str_contains(strtolower($columnInfo), 'unsigned')) {
                    $table->unsignedBigInteger('schedule_id');
                } else {
                    $table->bigInteger('schedule_id');
                }
            } else {
                if (str_contains(strtolower($columnInfo), 'unsigned')) {
                    $table->unsignedInteger('schedule_id');
                } else {
                    $table->integer('schedule_id');
                }
            }

            $table->foreign('schedule_id')
                  ->references('id')->on('schedules')
                  ->cascadeOnDelete();

            $table->tinyInteger('day_of_week')->nullable()->comment('2=Thứ 2 … 8=CN');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->timestamps();
        });

        // 2. Xóa các cột đơn buổi khỏi schedules
        Schema::table('schedules', function (Blueprint $table) {
            $cols = ['day_of_week', 'start_time', 'end_time'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('schedules', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục cột đơn vào schedules
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'day_of_week')) {
                $table->tinyInteger('day_of_week')->nullable()->after('group_code');
            }
            if (!Schema::hasColumn('schedules', 'start_time')) {
                $table->time('start_time')->nullable()->after('day_of_week');
            }
            if (!Schema::hasColumn('schedules', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });

        // Xóa bảng sessions
        Schema::dropIfExists('schedule_sessions');
    }
};
