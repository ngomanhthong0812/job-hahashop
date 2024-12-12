<?php
// Kết nối database
include('../config/init.php');

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM user";
$user_list = $conn->query($sql);
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
                                        <th>Tên đăng nhập</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>STT</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Tên người dùng</th>
                                        <th>Email</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $index = 0; ?>
                                    <?php foreach ($user_list as $user) { ?>
                                        <tr>
                                            <td>
                                                <?php echo ++$index; ?>
                                            </td>
                                            <td>
                                                <?php echo $user['username']; ?>
                                            </td>
                                            <td>
                                                <?php echo $user['full_name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $user['email']; ?>
                                            </td>
                                            <td>
                                                <?php echo $user['created_at']; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary text-nowrap"
                                                    href="edit.php?user_id=<?php echo $user['id']; ?>">Cập nhật</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger text-nowrap"
                                                    href="delete.php?user_id=<?php echo $user['id']; ?>">Xoá</a>
                                            </td>
                                            <td>
                                                <?php if ($user['status'] == 'active'): ?>
                                                    <!-- Nếu trạng thái là 'active', hiển thị nút ngừng hoạt động -->
                                                    <a class="btn btn-danger text-nowrap" href="setStatus.php?user_id=<?php echo $user['id']; ?>">Ngừng hoạt động</a>
                                                <?php else: ?>
                                                    <!-- Nếu trạng thái là 'inactive', hiển thị nút kích hoạt lại -->
                                                    <a class="btn btn-success text-nowrap" href="setStatus.php?user_id=<?php echo $user['id']; ?>">Kích hoạt lại</a>
                                                <?php endif; ?>
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