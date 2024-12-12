<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền user_id qua GET không
if (isset($_GET['user_id']) && $_GET['user_id']) {
    $user_id = $_GET['user_id'];

    // Lấy thông tin của user từ cơ sở dữ liệu
    $sql = "DELETE FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        // Nếu xóa thành công, chuyển hướng về trang index
        header("Location: index.php");
        exit();
    } else {
        // Nếu không thành công, có thể hiển thị thông báo lỗi
        echo "Có lỗi xảy ra khi xóa mục.";
    }
}
