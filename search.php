<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');
// Import Header
include('includes/header.php');
?>

<!-- Liên kết file style.css -->
<link rel="stylesheet" href="assets/css/style.css">


<body>
    <!-- Danh mục sản phẩm -->
    <div class="category-menu">
        <ul class="container nav justify-content-center">
            <!-- Mục Trang Chủ -->
            <li class="nav-item category-item active">
                <a class="nav-link" href="index.php">Trang Chủ</a>
            </li>

            <?php
            // Hiển thị các danh mục từ cơ sở dữ liệu
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="nav-item category-item">
                        <a class="nav-link" href="category.php?category_id=' . $row['id'] . '">' . $row['name'] . '</a>
                    </li>';
                }
            }
            ?>

            <!-- Mục Liên Hệ -->
            <li class="nav-item category-item">
                <a class="nav-link" href="contact.php">Liên Hệ</a>
            </li>
            <!-- Mục tài khoản -->
            <li class="nav-item category-item">
                <a class="nav-link" href="my_account.php">Tài khoản</a>
            </li>
        </ul>
    </div>

    <div class="container my-5">
        <!-- Sản Phẩm Tìm kiếm -->
        <?php $search = $_GET['search'] ?>
        <h2 class="text-center mb-4">Tìm kiếm sản phẩm theo: <?php echo $search ?></h2>
        <div class="row">
            <?php
            if (!empty($search)) {
                $sql = "SELECT * FROM product WHERE name LIKE ?";
                $stmt = $conn->prepare($sql);
                $searchTerm = "%" . $search . "%"; // Thêm dấu % để tìm kiếm chứa từ khóa ở mọi vị trí
                $stmt->bind_param("s", $searchTerm);
                $stmt->execute();
                $result = $stmt->get_result();

                // Kiểm tra và hiển thị sản phẩm
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '
                    <div class="col-sm-6 col-md-4 col-lg-3 mb-4 d-flex">
                        <div class="card shadow border-0 d-flex">
                            <img src="assets/images/products/' . $row['image'] . '" class="card-img-top" alt="' . $row['name'] . '">
                            <div class="card-body text-center">
                                <h5 class="card-title">' . $row['name'] . '</h5>
                                <p class="card-text">' . number_format($row['price'], 0, ',', '.') . ' VNĐ</p>
                                <a href="product.php?id=' . $row['id'] . '" class="btn btn-warning btn-block">Xem Chi Tiết</a>
                            </div>
                        </div>
                    </div>';
                    }
                } else {
                    echo "<p class='text-center w-100'>Không tìm thấy sản phẩm nào.</p>";
                }
                $stmt->close();
            } else {
                echo "<p class='text-center w-100'>Vui lòng nhập từ khóa tìm kiếm.</p>";
            }
            ?>
        </div>
    </div>

</body>
<?php
// Import Footer
include('includes/footer.php');
?>