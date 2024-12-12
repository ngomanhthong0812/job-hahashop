<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền order_id qua GET không
if (isset($_GET['order_id']) && $_GET['order_id']) {
    $order_id = $_GET['order_id'];

    // Bắt đầu transaction để đảm bảo tính toàn vẹn dữ liệu
    $conn->begin_transaction();

    try {
        // Xóa các chi tiết đơn hàng trong bảng order_detail
        $sql_detail = "DELETE FROM order_detail WHERE order_id = ?";
        $stmt_detail = $conn->prepare($sql_detail);
        $stmt_detail->bind_param("i", $order_id);
        $stmt_detail->execute();

        // Xóa đơn hàng trong bảng order
        $sql_order = "DELETE FROM `order` WHERE id = ?";
        $stmt_order = $conn->prepare($sql_order);
        $stmt_order->bind_param("i", $order_id);
        $stmt_order->execute();

        // Commit transaction nếu cả hai câu lệnh xóa đều thành công
        $conn->commit();

        // Chuyển hướng về trang index nếu xóa thành công
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        // Rollback transaction nếu có lỗi xảy ra
        $conn->rollback();
        echo "Có lỗi xảy ra khi xóa mục: " . $e->getMessage();
    }
} else {
    echo "Không có mã đơn hàng.";
}
