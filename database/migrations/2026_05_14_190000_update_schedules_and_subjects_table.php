<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Xoá cột classroom_id khỏi bảng schedules
        if (Schema::hasColumn('schedules', 'classroom_id')) {

            // Tìm và kiểm tra xem khóa ngoại thực tế có tồn tại trong DB không
            $foreignKeys = DB::select(
                "SELECT CONSTRAINT_NAME 
                 FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                 WHERE TABLE_SCHEMA = DATABASE() 
                   AND TABLE_NAME = 'schedules' 
                   AND COLUMN_NAME = 'classroom_id' 
                   AND REFERENCED_TABLE_NAME IS NOT NULL"
            );

            // Nếu có khóa ngoại, tiến hành xóa khóa ngoại trước
            if (!empty($foreignKeys)) {
                Schema::table('schedules', function (Blueprint $table) use ($foreignKeys) {
                    // Sử dụng tên khóa ngoại thực tế thay vì array convention
                    $table->dropForeign($foreignKeys[0]->CONSTRAINT_NAME);
                });
            }

            // Tiến hành xóa cột sau khi đã giải phóng khóa ngoại an toàn
            Schema::table('schedules', function (Blueprint $table) {
                $table->dropColumn('classroom_id');
            });
        }

        // 2. Thêm cột code vào bảng subjects
        Schema::table('subjects', function (Blueprint $table) {
            if (!Schema::hasColumn('subjects', 'code')) {
                $table->string('code', 20)->nullable()->unique()->after('faculty_id')
                    ->comment('Mã môn học (VD: CS101)');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hoàn tác: thêm lại classroom_id vào schedules
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'classroom_id')) {
                $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete();
            }
        });

        // Hoàn tác: xoá cột code khỏi subjects
        Schema::table('subjects', function (Blueprint $table) {
            if (Schema::hasColumn('subjects', 'code')) {
                $table->dropColumn('code');
            }
        });
    }
};
