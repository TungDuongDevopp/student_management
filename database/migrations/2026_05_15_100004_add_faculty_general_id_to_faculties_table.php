<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Lấy kiểu dữ liệu thực tế của faculties.id trong CSDL
        $columnInfo = \Illuminate\Support\Facades\DB::select("
            SELECT COLUMN_TYPE 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'faculty_generals' 
              AND COLUMN_NAME = 'id'
        ")[0]->COLUMN_TYPE;

        Schema::table('faculties', function (Blueprint $table) use ($columnInfo) {
            if (!Schema::hasColumn('faculties', 'faculty_general_id')) {
                if (str_contains(strtolower($columnInfo), 'bigint')) {
                    if (str_contains(strtolower($columnInfo), 'unsigned')) {
                        $table->unsignedBigInteger('faculty_general_id')->nullable()->after('id');
                    } else {
                        $table->bigInteger('faculty_general_id')->nullable()->after('id');
                    }
                } else {
                    if (str_contains(strtolower($columnInfo), 'unsigned')) {
                        $table->unsignedInteger('faculty_general_id')->nullable()->after('id');
                    } else {
                        $table->integer('faculty_general_id')->nullable()->after('id');
                    }
                }

                $table->foreign('faculty_general_id')
                      ->references('id')->on('faculty_generals')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('faculties', function (Blueprint $table) {
            if (Schema::hasColumn('faculties', 'faculty_general_id')) {
                $table->dropForeign(['faculty_general_id']);
                $table->dropColumn('faculty_general_id');
            }
        });
    }
};
