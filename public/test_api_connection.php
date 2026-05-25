<?php
header('Content-Type: application/json');

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// In Laravel 11, we can resolve DB from the app or simply use standard Eloquent if it's booted.
// Let's boot the application kernel so that DB config is loaded.
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

try {
    $teachers = \Illuminate\Support\Facades\DB::table('teachers')->get();
    echo json_encode([
        'status' => 'success',
        'teachers_count' => count($teachers),
        'teachers' => $teachers
    ]);
} catch (\Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
    ]);
}
