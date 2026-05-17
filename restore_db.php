<?php
$host = '127.0.0.1';
$user = 'root';
$pass = '';
$dbName = 'student_management';
$structureFile = 'QLSV.sql';
$dataFile = 'database/seed_data.sql';

echo "=== BAT DAU KHOI PHUC DATABASE & DU LIEU ===\n";

// 1. Kết nối MySQL
$conn = new mysqli($host, $user, $pass);
if ($conn->connect_error) {
    die("❌ Kết nối MySQL thất bại: " . $conn->connect_error . "\n");
}

// 2. Tạo Database sạch
$conn->query("DROP DATABASE IF EXISTS $dbName");
$conn->query("CREATE DATABASE $dbName CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
echo "✅ Đã tạo Database '$dbName' sạch tinh!\n";
$conn->select_db($dbName);

// 3. Import Cấu trúc từ QLSV.sql
if (file_exists($structureFile)) {
    echo "⏳ Đang import cấu trúc bảng từ $structureFile...\n";
    $sql = file_get_contents($structureFile);
    
    if (substr($sql, 0, 2) === "\xFF\xFE") {
        $sql = mb_convert_encoding($sql, 'UTF-8', 'UTF-16LE');
    }
    if (strpos($sql, "\xEF\xBB\xBF") === 0) {
        $sql = substr($sql, 3);
    }
    
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "✅ Đã import cấu trúc bảng thành công!\n";
    } else {
        die("❌ Lỗi import cấu trúc SQL: " . $conn->error . "\n");
    }
} else {
    die("❌ Không tìm thấy file cấu trúc $structureFile.\n");
}

// 4. Import Dữ liệu thực tế từ seed_data.sql
if (file_exists($dataFile)) {
    echo "⏳ Đang nạp dữ liệu thực tế từ $dataFile...\n";
    
    // Tắt kiểm tra khóa ngoại để nạp dữ liệu không bị lỗi ràng buộc thứ tự
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    
    $sql = file_get_contents($dataFile);
    if (substr($sql, 0, 2) === "\xFF\xFE") {
        $sql = mb_convert_encoding($sql, 'UTF-8', 'UTF-16LE');
    }
    if (strpos($sql, "\xEF\xBB\xBF") === 0) {
        $sql = substr($sql, 3);
    }
    
    if ($conn->multi_query($sql)) {
        do {
            if ($result = $conn->store_result()) {
                $result->free();
            }
        } while ($conn->more_results() && $conn->next_result());
        echo "✅ Đã nạp thành công toàn bộ dữ liệu mẫu vào các bảng!\n";
    } else {
        echo "❌ Lỗi nạp dữ liệu SQL: " . $conn->error . "\n";
    }
    
    // Bật lại kiểm tra khóa ngoại
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
} else {
    echo "⚠️ Không tìm thấy file dữ liệu mẫu $dataFile.\n";
}

$conn->close();
echo "=== HOAN THANH KHOI PHUC ===\n";
