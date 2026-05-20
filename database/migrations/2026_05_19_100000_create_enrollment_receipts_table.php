<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop enrollment_receipts table if it was created during previous migration attempts
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Schema::dropIfExists('enrollment_receipts');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    public function down(): void
    {
        // Do nothing
    }
};
