<?php
// Kết nối cơ sở dữ liệu
include('../config/init.php');

// Kiểm tra nếu có user_id trong URL
if (isset($_GET['user_id'])) {
    // Lấy user_id từ URL
    $user_id = $_GET['user_id'];

    // Truy vấn để lấy trạng thái hiện tại của người dùng
    $sql = "SELECT status FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Lấy trạng thái hiện tại
        $row = $result->fetch_assoc();
        $current_status = $row['status'];

        // Đảo ngược trạng thái
        $new_status = ($current_status == 'active') ? 'inactive' : 'active';

        // Cập nhật lại trạng thái
        $update_sql = "UPDATE user SET status = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("si", $new_status, $user_id);

        if ($update_stmt->execute()) {
            // Quay lại trang trước sau khi cập nhật
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            echo "Lỗi khi cập nhật trạng thái người dùng.";
        }
    } else {
        echo "Không tìm thấy người dùng.";
    }
} else {
    echo "Thiếu user_id.";
}

// Đóng kết nối
$conn->close();
