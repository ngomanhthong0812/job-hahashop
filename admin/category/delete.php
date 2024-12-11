<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền category_id qua GET không
if (isset($_GET['category_id']) && $_GET['category_id']) {
    $category_id = $_GET['category_id'];

    // Lấy thông tin của category từ cơ sở dữ liệu
    $sql = "DELETE FROM category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        // Nếu xóa thành công, chuyển hướng về trang index
        header("Location: index.php");
        exit();
    } else {
        // Nếu không thành công, có thể hiển thị thông báo lỗi
        echo "Có lỗi xảy ra khi xóa mục.";
    }
}
