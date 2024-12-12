<?php
// Kết nối database
include('../config/init.php');

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM `order`";
$order_list = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard - SB Admin</title>
    <link href="../css/style.css" rel="stylesheet" />
    <script src="../js/all.js" crossorigin="anonymous"></script>
</head>

<body class="sb-nav-fixed">
    <?php include_once '../inc/navbar.php' ?>
    <div id="layoutSidenav">
        <?php include_once '../inc/sideleft.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Loại sản phẩm</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên khách hàng</th>
                                        <th>Tổng tiền</th>
                                        <th>Trạng thái</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $index = 0; ?>
                                    <?php foreach ($order_list as $order) { ?>
                                        <tr>
                                            <td>
                                                <?php echo ++$index; ?>
                                            <td>
                                                <?php
                                                // Lấy tên khách hàng từ bảng user dựa trên user_id của đơn hàng
                                                $user_id = $order['user_id'];
                                                $sql_user = "SELECT full_name FROM user WHERE id = ?";
                                                $stmt_user = $conn->prepare($sql_user);
                                                $stmt_user->bind_param("i", $user_id);
                                                $stmt_user->execute();
                                                $result_user = $stmt_user->get_result();
                                                $user = $result_user->fetch_assoc();
                                                echo isset($user['full_name']) ? $user['full_name'] : 'Không tìm thấy';
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $order['total']; ?>
                                            </td>
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
                                                <?php echo $order['created_at']; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary text-nowrap"
                                                    href="order_details.php?order_id=<?php echo $order['id']; ?>&status=<?php echo $order['status'] ?>">Chi tiết</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger text-nowrap"
                                                    href="delete.php?order_id=<?php echo $order['id']; ?>">Xoá</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Your Website 2022</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="../js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="../js/scripts.js"></script>
    <script src="../js/Chart.min.js" crossorigin="anonymous"></script>
    <script src="../js/simple-datatables@latest.js" crossorigin="anonymous"></script>
    <script src="../js/datatables-simple-demo.js"></script>
</body>

</html>