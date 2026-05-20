<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function addDynamicForeign($table, $columnName, $refTable, $refColumn = 'id') {
        $colInfo = DB::select("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?", [$refTable, $refColumn])[0]->COLUMN_TYPE;
        $isBigInt = str_contains(strtolower($colInfo), 'bigint');
        $isUnsigned = str_contains(strtolower($colInfo), 'unsigned');
        
        if ($isBigInt) {
            $isUnsigned ? $table->unsignedBigInteger($columnName)->nullable() : $table->bigInteger($columnName)->nullable();
        } else {
            $isUnsigned ? $table->unsignedInteger($columnName)->nullable() : $table->integer($columnName)->nullable();
        }
        $table->foreign($columnName)->references($refColumn)->on($refTable)->onDelete('cascade');
    }

    public function up(): void
    {
        // Migrate existing score data to grades table
        $enrollments = DB::table('enrollments')->get();
        foreach ($enrollments as $enrollment) {
            if ($enrollment->score_c !== null || $enrollment->score_b !== null || $enrollment->score_a !== null) {
                // Ensure grades table doesn't already have it
                $exists = DB::table('grades')->where('enrollment_id', $enrollment->id)->exists();
                if (!$exists) {
                    DB::table('grades')->insert([
                        'enrollment_id' => $enrollment->id,
                        'score_c' => $enrollment->score_c,
                        'score_b' => $enrollment->score_b,
                        'score_a' => $enrollment->score_a,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        Schema::table('enrollments', function (Blueprint $table) {
            // Drop score columns if they exist
            $colsToDrop = [];
            foreach (['score_c', 'score_b', 'score_a', 'final_score'] as $c) {
                if (Schema::hasColumn('enrollments', $c)) {
                    $colsToDrop[] = $c;
                }
            }
            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
            
            // Thêm foreign key liên kết với Phiếu học phí (Tuition)
            if (!Schema::hasColumn('enrollments', 'tuition_id')) {
                $this->addDynamicForeign($table, 'tuition_id', 'tuitions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->decimal('score_c', 5, 2)->nullable();
            $table->decimal('score_b', 5, 2)->nullable();
            $table->decimal('score_a', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            
            $table->dropForeign(['tuition_id']);
            $table->dropColumn('tuition_id');
        });
    }
};
