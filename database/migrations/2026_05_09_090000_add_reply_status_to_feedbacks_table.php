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
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->text('reply')->nullable()->after('content')->comment('Phản hồi từ admin');
            $table->tinyInteger('status')->default(0)->after('reply')->comment('0: chưa xem, 1: đã xem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['reply', 'status']);
        });
    }
};
