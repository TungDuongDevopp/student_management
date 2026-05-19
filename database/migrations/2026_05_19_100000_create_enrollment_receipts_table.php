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
        Schema::dropIfExists('enrollment_receipts');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        Schema::create('enrollment_receipts', function (Blueprint $table) {
            $colInfo = DB::select("SELECT COLUMN_TYPE FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'students' AND COLUMN_NAME = 'id'")[0]->COLUMN_TYPE;
            $isBigInt = str_contains(strtolower($colInfo), 'bigint');
            $isUnsigned = str_contains(strtolower($colInfo), 'unsigned');

            if ($isBigInt) {
                $table->bigIncrements('id');
            } else {
                $table->increments('id');
            }
            
            $this->addDynamicForeign($table, 'student_id', 'students');
            $this->addDynamicForeign($table, 'semester_id', 'semesters');

            $table->integer('total_credits')->default(0);
            $table->decimal('total_fee', 15, 2)->default(0);
            $table->decimal('paid_fee', 15, 2)->default(0);
            $table->string('payment_status', 30)->default('pending')->comment('pending, partial, paid');
            $table->timestamps();
            
            $table->unique(['student_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollment_receipts');
    }
};
