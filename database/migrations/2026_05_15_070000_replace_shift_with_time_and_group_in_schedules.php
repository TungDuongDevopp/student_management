<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Bảng schedules:
     *  - Xoá cột shift
     *  - Thêm cột start_time, end_time (TIME)
     *  - Thêm cột group_code (mã nhóm, VD: "Nhóm 1", "N01")
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Thêm start_time, end_time sau day_of_week
            $table->time('start_time')->nullable()->after('day_of_week');
            $table->time('end_time')->nullable()->after('start_time');

            // Thêm mã nhóm sau end_time
            $table->string('group_code', 20)->nullable()->after('end_time')
                  ->comment('Mã nhóm học phần (VD: N01, Nhóm 1)');
        });

        // Xoá cột shift (tách riêng để tránh lỗi trên một số DB driver)
        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'shift')) {
                $table->dropColumn('shift');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Thêm lại shift
            if (!Schema::hasColumn('schedules', 'shift')) {
                $table->integer('shift')->nullable()->after('day_of_week');
            }

            // Xoá các cột mới
            $cols = ['start_time', 'end_time', 'group_code'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('schedules', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
