<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

// Lấy thông tin đơn hàng từ cơ sở dữ liệu
$order_id = $_GET['order_id']; // Lấy order_id từ URL
$sql = "SELECT * FROM `order` WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();

if (!$order) {
    // Nếu không tìm thấy đơn hàng, chuyển hướng về trang lịch sử đơn hàng
    header("Location: order_history.php");
    exit();
}

// Lấy chi tiết đơn hàng từ cơ sở dữ liệu
$sql_details = "SELECT od.*, p.name, p.price FROM order_detail od 
                JOIN product p ON od.product_id = p.id 
                WHERE od.order_id = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $order_id);
$stmt_details->execute();
$order_details_result = $stmt_details->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết đơn hàng</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container py-5">
        <h2 class="mb-4 text-center">Chi tiết đơn hàng #<?php echo $order['id']; ?></h2>

        <!-- Thông tin đơn hàng -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Thông tin đơn hàng</h5>
            </div>
            <div class="card-body">
                <p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>
                <p><strong>Tình trạng:</strong> <?php echo $order['status']; ?></p>
                <p><strong>Tổng tiền:</strong> <?php echo number_format($order['total'], 0, ',', '.') . " VNĐ"; ?></p>
            </div>
        </div>

        <!-- Chi tiết sản phẩm trong đơn hàng -->
        <div class="card">
            <div class="card-header">
                <h5>Danh sách sản phẩm</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>STT</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá</th>
                            <th>Tổng</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $index = 1;
                        $total_price = 0; ?>
                        <?php while ($item = $order_details_result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td><?php echo $item['name']; ?></td>
                                <td><?php echo $item['quantity']; ?></td>
                                <td><?php echo number_format($item['price'], 0, ',', '.') . " VNĐ"; ?></td>
                                <td><?php echo number_format($item['quantity'] * $item['price'], 0, ',', '.') . " VNĐ"; ?></td>
                            </tr>
                            <?php $total_price += $item['quantity'] * $item['price']; ?>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <h5 class="mt-4">Tổng số tiền: <?php echo number_format($total_price, 0, ',', '.') . " VNĐ"; ?></h5>
            </div>
        </div>

        <div class="mt-4">
            <a href="order_history.php" class="btn btn-secondary">Trở lại</a>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>