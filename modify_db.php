<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('system_configs', function(Blueprint $table) {
    $table->longText('value')->nullable()->change();
});
echo "Done";
