<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('faculty_generals', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique()->comment('Tên nhóm khoa (VD: Khối Kỹ thuật)');
            $table->integer('max_credits')->default(150)->comment('Số tín chỉ tối đa');
            $table->integer('tuition_fee_per_credit')->default(500000)->comment('Học phí 1 tín chỉ');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faculty_generals');
    }
};
