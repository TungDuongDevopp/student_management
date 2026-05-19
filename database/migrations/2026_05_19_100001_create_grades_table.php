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
            $isUnsigned ? $table->unsignedBigInteger($columnName) : $table->bigInteger($columnName);
        } else {
            $isUnsigned ? $table->unsignedInteger($columnName) : $table->integer($columnName);
        }
        $table->foreign($columnName)->references($refColumn)->on($refTable)->onDelete('cascade');
    }

    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('grades');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $this->addDynamicForeign($table, 'enrollment_id', 'enrollments');
            $table->decimal('score_c', 5, 2)->nullable()->comment('Điểm chuyên cần 10%');
            $table->decimal('score_b', 5, 2)->nullable()->comment('Điểm giữa kỳ 30%');
            $table->decimal('score_a', 5, 2)->nullable()->comment('Điểm cuối kỳ 60%');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
