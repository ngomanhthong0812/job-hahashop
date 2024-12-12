<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền feedback_id qua GET không
if (isset($_GET['feedback_id']) && $_GET['feedback_id']) {
    $feedback_id = $_GET['feedback_id'];

    // Lấy thông tin của feedback từ cơ sở dữ liệu
    $sql = "DELETE FROM feedback WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);

    if ($stmt->execute()) {
        // Nếu xóa thành công, chuyển hướng về trang index
        header("Location: index.php");
        exit();
    } else {
        // Nếu không thành công, có thể hiển thị thông báo lỗi
        echo "Có lỗi xảy ra khi xóa mục.";
    }
}
