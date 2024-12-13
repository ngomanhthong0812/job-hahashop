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


//kiểm tra người dùng đã đăng nhập hay chk
if (!isset($_SESSION['admin_id'])) {
    $message = "Vui lòng đăng nhập để tiếp tục.";
    header("Location: ./login.php");
    // Gửi thông báo qua POST
    $_SESSION['login_message'] = $message; // Sử dụng session để truyền thông báo an toàn hơn
    exit;
} else {
    unset($_SESSION['login_message']);
}
