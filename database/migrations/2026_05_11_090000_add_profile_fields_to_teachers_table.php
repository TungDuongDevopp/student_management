<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('email');
            $table->tinyInteger('gender')->nullable()->comment('0: Nam, 1: Nữ')->after('date_of_birth');
            $table->string('phone', 20)->nullable()->after('gender');
            $table->text('address')->nullable()->after('phone');
            $table->string('department', 100)->nullable()->comment('Bộ môn')->after('address');
            $table->string('degree', 50)->nullable()->comment('Học vị: CN, ThS, TS, PGS, GS')->after('department');
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'gender', 'phone', 'address', 'department', 'degree']);
        });
    }
};
