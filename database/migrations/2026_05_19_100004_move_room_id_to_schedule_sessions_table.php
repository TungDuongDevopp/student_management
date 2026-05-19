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
        // 1. Add room_id to schedule_sessions table first
        Schema::table('schedule_sessions', function (Blueprint $table) {
            if (!Schema::hasColumn('schedule_sessions', 'room_id')) {
                $this->addDynamicForeign($table, 'room_id', 'rooms');
            }
        });

        // 2. Transfer room_id data from schedules to schedule_sessions
        DB::statement("
            UPDATE schedule_sessions ss
            JOIN schedules s ON ss.schedule_id = s.id
            SET ss.room_id = s.room_id
            WHERE s.room_id IS NOT NULL AND ss.room_id IS NULL
        ");

        // 3. Drop foreign key and column from schedules table
        try {
            DB::statement('ALTER TABLE schedules DROP FOREIGN KEY schedules_ibfk_3');
        } catch (\Exception $e) {
            try {
                DB::statement('ALTER TABLE schedules DROP FOREIGN KEY schedules_room_id_foreign');
            } catch (\Exception $e2) {
                // ignore if doesn't exist
            }
        }

        Schema::table('schedules', function (Blueprint $table) {
            if (Schema::hasColumn('schedules', 'room_id')) {
                $table->dropColumn('room_id');
            }
        });
    }

    public function down(): void
    {
        // 1. Add room_id back to schedules table
        Schema::table('schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('schedules', 'room_id')) {
                $this->addDynamicForeign($table, 'room_id', 'rooms');
            }
        });

        // 2. Transfer room_id back from schedule_sessions (first session room_id)
        DB::statement("
            UPDATE schedules s
            JOIN (
                SELECT schedule_id, MIN(room_id) as room_id 
                FROM schedule_sessions 
                WHERE room_id IS NOT NULL 
                GROUP BY schedule_id
            ) ss ON s.id = ss.schedule_id
            SET s.room_id = ss.room_id
            WHERE s.room_id IS NULL
        ");

        // 3. Drop room_id from schedule_sessions
        try {
            DB::statement('ALTER TABLE schedule_sessions DROP FOREIGN KEY schedule_sessions_room_id_foreign');
        } catch (\Exception $e) {
            // ignore
        }

        Schema::table('schedule_sessions', function (Blueprint $table) {
            if (Schema::hasColumn('schedule_sessions', 'room_id')) {
                $table->dropColumn('room_id');
            }
        });
    }
};
