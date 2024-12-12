<?php
include('../config/init.php');

// Kiểm tra xem có truyền `order_id` qua GET không
if (isset($_GET['order_id']) && $_GET['order_id']) {
    $order_id = $_GET['order_id'];

    // Truy vấn thông tin đơn hàng
    $sql_order = "SELECT o.id, o.user_id, o.total, o.status, o.created_at, u.username 
                  FROM `order` o
                  JOIN user u ON o.user_id = u.id
                  WHERE o.id = ?";
    $stmt_order = $conn->prepare($sql_order);
    $stmt_order->bind_param("i", $order_id);
    $stmt_order->execute();
    $result_order = $stmt_order->get_result();

    if ($result_order->num_rows > 0) {
        $order = $result_order->fetch_assoc();

        // Truy vấn thông tin chi tiết đơn hàng và lấy tên sản phẩm từ bảng `product`
        $sql_details = "SELECT od.quantity, od.price, p.name AS product_name
                        FROM order_detail od
                        JOIN product p ON od.product_id = p.id
                        WHERE od.order_id = ?";
        $stmt_details = $conn->prepare($sql_details);
        $stmt_details->bind_param("i", $order_id);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();
    } else {
        echo "Không tìm thấy đơn hàng này.";
        exit;
    }
} else {
    echo "Không có mã đơn hàng.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Chi tiết đơn hàng</title>
    <link href="../css/style.css" rel="stylesheet" />
    <script src="../js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <!-- Header -->
    <?php include_once '../inc/navbar.php'; ?>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <?php include_once '../inc/sideleft.php'; ?>

        <!-- Main Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Chi tiết đơn hàng</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            Thông tin chi tiết đơn hàng
                        </div>
                        <div class="card-body">
                            <!-- Thông tin đơn hàng -->
                            <h2>Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>
                            <p><strong>Khách hàng:</strong> <?php echo $order['username']; ?></p>
                            <p><strong>Ngày tạo:</strong> <?php echo $order['created_at']; ?></p>
                            <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total'], 0, ',', '.'); ?> VNĐ</p>

                            <!-- Form thay đổi trạng thái đơn hàng -->
                            <form action="" method="POST">
                                <p><strong>Trạng thái:</strong>
                                    <select name="status">
                                        <option value="pending" <?php echo (isset($_POST['status']) && $_POST['status'] == 'pending' ? 'selected' : ''); ?>>Chờ xử lý</option>
                                        <option value="completed" <?php echo (isset($_POST['status']) && $_POST['status'] == 'completed' ? 'selected' : ''); ?>>Hoàn thành</option>
                                        <option value="cancelled" <?php echo (isset($_POST['status']) && $_POST['status'] == 'cancelled' ? 'selected' :  ''); ?>>Đã hủy</option>
                                    </select>
                                </p>
                                <input type="submit" value="Cập nhật trạng thái">
                            </form>

                            <!-- Hiển thị bảng chi tiết các sản phẩm trong đơn hàng -->
                            <h3>Chi tiết các sản phẩm trong đơn hàng:</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Số lượng</th>
                                        <th>Giá</th>
                                        <th>Tổng giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result_details->fetch_assoc()) {
                                        $total_price = $row['quantity'] * $row['price']; ?>
                                        <tr>
                                            <td><?php echo $row['product_name']; ?></td>
                                            <td><?php echo $row['quantity']; ?></td>
                                            <td><?php echo number_format($row['price'], 0, ',', '.'); ?> VNĐ</td>
                                            <td><?php echo number_format($total_price, 0, ',', '.'); ?> VNĐ</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <!-- Nếu form đã được gửi, cập nhật trạng thái -->
                            <?php
                            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                                $new_status = $_POST['status'];

                                // Cập nhật trạng thái mới vào cơ sở dữ liệu
                                $sql_update = "UPDATE `order` SET status = ? WHERE id = ?";
                                $stmt_update = $conn->prepare($sql_update);
                                $stmt_update->bind_param("si", $new_status, $order_id);

                                if ($stmt_update->execute()) {
                                    echo "<p class='text-success'>Trạng thái đơn hàng đã được cập nhật.</p>";
                                } else {
                                    echo "<p class='text-danger'>Cập nhật trạng thái thất bại. Vui lòng thử lại.</p>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2022</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms & Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
</body>

</html>