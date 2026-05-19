<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            // Drop foreign key if it exists
            if (Schema::hasColumn('enrollments', 'enrollment_receipt_id')) {
                // Laravel's automatic foreign key naming convention is: [table]_[column]_foreign
                // We try dropping it safely
                try {
                    $table->dropForeign('enrollments_enrollment_receipt_id_foreign');
                } catch (\Exception $e) {
                    // Fail silently if foreign key constraint does not exist
                }
                
                $table->dropColumn('enrollment_receipt_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->unsignedBigInteger('enrollment_receipt_id')->nullable();
        });
    }
};
