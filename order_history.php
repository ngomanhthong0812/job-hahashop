<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng đến trang login
    $message = "Vui lòng đăng nhập để tiếp tục.";
    header("Location: login.php");
    // Gửi thông báo qua POST
    $_SESSION['login_message'] = $message;
    exit();
}

$user_id = $_SESSION['user_id'];

// Lấy danh sách đơn hàng của người dùng
$sql_orders = "SELECT * FROM `order` WHERE user_id = ? ORDER BY created_at DESC";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();
?>

<?php include('includes/header.php'); ?>

<body>
    <div class="container py-5">
        <h2 class="text-center mb-4">Lịch sử đơn hàng</h2>

        <!-- Nếu không có đơn hàng nào -->
        <?php if ($result_orders->num_rows === 0): ?>
            <div class="alert alert-info text-center" role="alert">
                Bạn chưa có đơn hàng nào.
            </div>
        <?php else: ?>

            <!-- Bảng hiển thị lịch sử đơn hàng -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($order = $result_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo date("d/m/Y H:i", strtotime($order['created_at'])); ?></td>
                            <td><?php echo number_format($order['total'], 0, ',', '.') . " VNĐ"; ?></td>
                            <td>
                                <?php
                                // Lấy trạng thái đơn hàng
                                $status = $order['status'];

                                // Xác định màu sắc dựa trên trạng thái
                                if ($status == 'pending') {
                                    $color = 'warning'; // Màu vàng cho trạng thái "pending"
                                } elseif ($status == 'completed') {
                                    $color = 'success'; // Màu xanh cho trạng thái "completed"
                                } elseif ($status == 'cancelled') {
                                    $color = 'danger'; // Màu đỏ cho trạng thái "cancelled"
                                } else {
                                    $color = 'secondary'; // Màu mặc định nếu không có trạng thái hợp lệ
                                }
                                ?>
                                <span class="text-<?php echo $color; ?>">
                                    <?php echo ucfirst($status); ?> <!-- hiển thị trạng thái đầu tiên in hoa -->
                                </span>
                            </td>
                            <td>
                                <a href="order_detail.php?order_id=<?php echo $order['id']; ?>" class="btn btn-info btn-sm">Xem chi tiết</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

    </div>

    <?php include('includes/footer.php'); ?>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>


</html>