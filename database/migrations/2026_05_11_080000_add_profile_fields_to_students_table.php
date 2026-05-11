<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable()->after('email');
            $table->tinyInteger('gender')->nullable()->comment('0: Nam, 1: Nữ')->after('date_of_birth');
            $table->string('phone_number', 20)->nullable()->after('gender');
            $table->string('specialization', 100)->nullable()->comment('Chuyên ngành')->after('phone_number');
            $table->text('address')->nullable()->after('specialization');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn(['date_of_birth', 'gender', 'phone_number', 'specialization', 'address']);
        });
    }
};
