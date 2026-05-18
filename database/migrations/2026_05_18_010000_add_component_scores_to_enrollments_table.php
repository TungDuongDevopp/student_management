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
        Schema::table('enrollments', function (Blueprint $table) {
            $table->decimal('score_c', 4, 2)->nullable()->comment('Điểm C (10%)');
            $table->decimal('score_b', 4, 2)->nullable()->comment('Điểm B (30%)');
            $table->decimal('score_a', 4, 2)->nullable()->comment('Điểm A (60%)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['score_c', 'score_b', 'score_a']);
        });
    }
};
