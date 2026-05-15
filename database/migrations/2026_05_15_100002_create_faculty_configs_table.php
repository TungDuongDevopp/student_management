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
        Schema::dropIfExists('faculty_configs');

        // Lấy kiểu dữ liệu thực tế của faculties.id trong CSDL
        $columnInfo = \Illuminate\Support\Facades\DB::select("
            SELECT COLUMN_TYPE 
            FROM information_schema.COLUMNS 
            WHERE TABLE_SCHEMA = DATABASE() 
              AND TABLE_NAME = 'faculties' 
              AND COLUMN_NAME = 'id'
        ")[0]->COLUMN_TYPE;

        Schema::create('faculty_configs', function (Blueprint $table) use ($columnInfo) {
            $table->id();

            // Ép kiểu faculty_id khớp 100% với faculties.id
            if (str_contains(strtolower($columnInfo), 'bigint')) {
                if (str_contains(strtolower($columnInfo), 'unsigned')) {
                    $table->unsignedBigInteger('faculty_id')->unique();
                } else {
                    $table->bigInteger('faculty_id')->unique();
                }
            } else {
                if (str_contains(strtolower($columnInfo), 'unsigned')) {
                    $table->unsignedInteger('faculty_id')->unique();
                } else {
                    $table->integer('faculty_id')->unique();
                }
            }

            $table->foreign('faculty_id')->references('id')->on('faculties')->cascadeOnDelete();
            
            // Cấu hình đào tạo và tài chính
            $table->integer('max_credits')->default(150)->comment('Tín chỉ tích lũy tối đa');
            $table->integer('tuition_fee_per_credit')->default(500000)->comment('Học phí 1 tín chỉ');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_configs');
    }
};
