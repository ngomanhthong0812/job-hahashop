<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Định nghĩa BASE_URL
define('BASE_URL', 'http://localhost/hahashop/');

// Kiểm tra và bao gồm file db.php từ đường dẫn chính
$dbConfigPath = '../../config/db.php';

// Kiểm tra xem file có tồn tại không
if (!file_exists($dbConfigPath)) {
    // Nếu không tìm thấy, thử với đường dẫn khác
    $dbConfigPath = '../config/db.php';
}

// Bao gồm file cấu hình database
include($dbConfigPath);
