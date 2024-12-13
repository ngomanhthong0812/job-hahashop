<?php
include('config/init.php');

//kiểm tra người dùng đã đăng nhập hay chk
if (!isset($_SESSION['user_id'])) {
    $message = "Vui lòng đăng nhập để tiếp tục.";
    header("Location: login.php");
    // Gửi thông báo qua POST
    $_SESSION['comment_message'] = $message; // Sử dụng session để truyền thông báo an toàn hơn
    exit;
}

// Kiểm tra dữ liệu gửi từ form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_SESSION['user_id']; // ID người dùng đã đăng nhập
    $product_id = $_POST['product_id']; // ID sản phẩm (phải gửi từ form)
    $rating = $_POST['rating'];
    $comment = htmlspecialchars($_POST['comment']);

    // Kiểm tra dữ liệu hợp lệ
    if (empty($rating) || empty($comment)) {
        exit;
    }

    // Thêm bình luận vào cơ sở dữ liệu
    $stmt = $conn->prepare("INSERT INTO feedback (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $product_id, $user_id, $rating, $comment);

    if ($stmt->execute()) {
        header("Location: product.php?id=" . $product_id);
        exit;
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Có lỗi xảy ra khi gửi bình luận.']);
    }

    $stmt->close();
}
