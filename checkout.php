<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng đến trang login
    $message = "Vui lòng đăng nhập để tiếp tục.";
    header("Location: login.php");
    // Gửi thông báo qua POST
    $_SESSION['login_message'] = $message; // Sử dụng session để truyền thông báo an toàn hơn
    exit();
}

if (isset($_SESSION['user_id']) && $_SESSION['user_id']) {
    $user_id = $_SESSION['user_id'];

    // Lấy thông tin của user từ cơ sở dữ liệu
    $sql = "SELECT * FROM user WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();
}


// Lấy thông tin giỏ hàng từ  session
$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        $product_id = intval($cart_item['product_id']);
        $size_id = intval($cart_item['size']);

        // Lấy thông tin sản phẩm
        $sql_product = "SELECT * FROM product WHERE id = $product_id";
        $product_result = $conn->query($sql_product);
        $product = $product_result ? $product_result->fetch_assoc() : null;

        // Lấy thông tin size
        $sql_size = "SELECT * FROM sizes WHERE id = $size_id";
        $size_result = $conn->query($sql_size);
        $size = $size_result ? $size_result->fetch_assoc() : null;

        if ($product && $size) {
            $cart_items[] = [
                'key' => $key,
                'product' => $product,
                'size' => $size,
                'quantity' => $cart_item['quantity']
            ];
            $total_price += $product['price'] * $cart_item['quantity'];
        }
    }
}

// Xử lý khi người dùng nhấn nút "Đặt hàng ngay"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    // Tạo đơn hàng mới
    $sql_order = "INSERT INTO `order` (user_id, total) VALUES (?, ?)";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("ii", $user_id, $total_price);

    if ($stmt_order->execute()) {
        $order_id = $stmt_order->insert_id; // Lấy ID của đơn hàng vừa tạo

        // Lưu thông tin chi tiết đơn hàng vào bảng `order_detail`
        foreach ($cart_items as $item) {
            $product_id = $item['product']['id'];
            $quantity = $item['quantity'];
            $price = $item['product']['price'];

            $sql_order_detail = "INSERT INTO order_detail (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
            $stmt_order_detail = $conn->prepare($sql_order_detail);
            $stmt_order_detail->bind_param("iiid", $order_id, $product_id, $quantity, $price);
            $stmt_order_detail->execute();
        }

        // Xóa giỏ hàng sau khi đặt hàng thành công
        header("Location: order_history.php");
        unset($_SESSION['cart']);
    } else {
        echo "Đặt hàng không thành công.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .header {
            position: relative;
        }

        .back-button {
            position: absolute;
            top: 10px;
            left: 10px;
        }

        .card {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border: none;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <!-- Back Button -->
        <div class="header">
            <a href="cart.php" class="btn btn-secondary back-button">Trở lại</a>
        </div>

        <h2 class="mb-4 text-center">Đặt hàng</h2>
        <div class="row">
            <!-- User Information -->
            <div class="col-md-6 mb-4">
                <div class="card p-4">
                    <h4 class="mb-3">Thông tin người dùng</h4>
                    <p><strong>Tên người dùng:</strong> <?php echo $user['full_name'] ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email'] ?></p>
                    <p><strong>Số điện thoại:</strong> <?php echo $user['phone'] ?></p>
                    <p><strong>Địa chỉ:</strong> <?php echo $user['address'] ?></p>
                    <a href="my_account.php" class="btn btn-outline-primary">Thay đổi thông tin</a>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="col-md-6">
                <div class="card p-4">
                    <h4 class="mb-3">Danh sách sản phẩm</h4>
                    <ul class="list-group mb-3">
                        <?php foreach ($cart_items as $index => $item) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?php echo htmlspecialchars($item['product']['name']); ?>
                                <span> <?php echo number_format($item['product']['price'], 0, ',', '.') . " VNĐ"; ?></span>
                            </li>
                        <?php endforeach; ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Vận chuyển <span>Miễn phí</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Tổng</strong> <strong><?php echo number_format($total_price, 0, ',', '.') . " VNĐ"; ?></strong>
                        </li>
                    </ul>

                    <h4 class="mb-3">Hình thức thanh toán</h4>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                        <label class="form-check-label" for="creditCard">
                            Thanh toán khi nhận hàng
                        </label>
                    </div>

                    <form action="checkout.php" method="POST">
                        <button type="submit" name="place_order" class="btn btn-primary w-100 mt-4">Đặt hàng ngay</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>