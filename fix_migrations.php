<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'student_management';

$conn = new mysqli($host, $user, $pass, $dbName);
if ($conn->connect_error) {
    die("❌ Kết nối MySQL thất bại: " . $conn->connect_error . "\n");
}

echo "=== DONG BO MIGRATIONS ===\n";

// 1. Tạo bảng migrations nếu chưa có
$conn->query("CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;");

echo "✅ Bảng 'migrations' đã sẵn sàng.\n";

// 2. Danh sách các migration đã có sẵn cấu trúc trong QLSV.sql
$existingMigrations = [
    '0001_01_01_000000_create_users_table' => 1,
    '2026_05_04_003114_add_timestamps_to_all_tables' => 1,
    '2026_05_06_102550_add_block_to_rooms_table' => 1,
    '2026_05_06_103016_add_description_to_rooms_table' => 1,
    '2026_05_07_113529_add_status_to_semesters_table' => 1,
    '2026_05_09_090000_add_reply_status_to_feedbacks_table' => 1,
    '2026_05_09_150000_remove_semester_id_from_classrooms_table' => 1
];

foreach ($existingMigrations as $migration => $batch) {
    // Kiểm tra xem đã tồn tại bản ghi chưa
    $check = $conn->query("SELECT id FROM migrations WHERE migration = '$migration'");
    if ($check->num_rows == 0) {
        $conn->query("INSERT INTO migrations (migration, batch) VALUES ('$migration', $batch)");
        echo "🔹 Đã đánh dấu: $migration (Batch $batch)\n";
    }
}

$conn->close();
echo "✅ Đồng bộ thành công! Chuẩn bị chạy php artisan migrate...\n";
