<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền product_id qua GET không
if (isset($_GET['product_id']) && $_GET['product_id']) {
    $product_id = $_GET['product_id'];

    // Lấy thông tin của product từ cơ sở dữ liệu
    $sql = "DELETE FROM product WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Nếu xóa thành công, chuyển hướng về trang index
        header("Location: index.php");
        exit();
    } else {
        // Nếu không thành công, có thể hiển thị thông báo lỗi
        echo "Có lỗi xảy ra khi xóa mục.";
    }
}
