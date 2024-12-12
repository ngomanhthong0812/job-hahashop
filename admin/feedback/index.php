<?php
// Kết nối database
include('../config/init.php');

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM feedback";
$feedback_list = $conn->query($sql);
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
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
                                        <th>Khách hàng</th>
                                        <th>Sản phẩm</th>
                                        <th>Đánh giá</th>
                                        <th>Nội dung</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>STT</th>
                                        <th>Khách hàng</th>
                                        <th>Sản phẩm</th>
                                        <th>Đánh giá</th>
                                        <th>Nội dung</th>
                                        <th>Ngày tạo</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $index = 0; ?>
                                    <?php foreach ($feedback_list as $feedback) { ?>
                                        <tr>
                                            <td>
                                                <?php echo ++$index; ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Lấy user_id từ feedback
                                                $user_id = $feedback['user_id'];

                                                // Truy vấn lấy tên sản phẩm và ảnh của sản phẩm
                                                $sql = "SELECT full_name FROM user WHERE id = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $user_id);
                                                $stmt->execute();
                                                $stmt->bind_result($user_name);

                                                // Hiển thị tên sản phẩm và ảnh
                                                if ($stmt->fetch()) {
                                                    echo $user_name;
                                                } else {
                                                    echo "Không tìm thấy tên khách hàng";
                                                }

                                                $stmt->close();
                                                ?>
                                            </td>
                                            <td class="d-flex">
                                                <?php
                                                // Lấy product_id từ feedback
                                                $product_id = $feedback['product_id'];

                                                // Truy vấn lấy tên sản phẩm và ảnh của sản phẩm
                                                $sql = "SELECT name, image FROM product WHERE id = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $product_id);
                                                $stmt->execute();
                                                $stmt->bind_result($product_name, $product_image);

                                                // Hiển thị tên sản phẩm và ảnh
                                                if ($stmt->fetch()) {
                                                    if ($product_image) {
                                                        echo '<br><img src="../../assets/images/products/' . $product_image . '" width="60" height="60">';
                                                    }
                                                    echo $product_name;
                                                } else {
                                                    echo "Không tìm thấy tên sản phẩm";
                                                }

                                                $stmt->close();
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                $rating = $feedback['rating'];
                                                $maxRating = 5; // Số sao tối đa

                                                // Giới hạn rating tối đa là 5
                                                $rating = ($rating > $maxRating) ? $maxRating : $rating;

                                                // Hiển thị sao theo rating
                                                for ($i = 0; $i < $rating; $i++) {
                                                    echo "<i class='bx bxs-star' style='color:#edff0a'  ></i>"; // Sao màu vàng
                                                }

                                                // Nếu rating < 5, hiển thị các sao trống
                                                for ($i = $rating; $i < $maxRating; $i++) {
                                                    echo "<i class='bx bx-star' ></i>"; // Sao màu xám
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $feedback['comment']; ?>
                                            </td>
                                            <td>
                                                <?php echo $feedback['created_at']; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger text-nowrap"
                                                    href="delete.php?feedback_id=<?php echo $feedback['id']; ?>">Xoá</a>
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