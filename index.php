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

            <?php if (isset($_SESSION['user_id'])) : ?>
                <!-- Mục tài khoản -->
                <li class="nav-item category-item">
                    <a class="nav-link" href="my_account.php">Tài khoản</a>
                </li>
            <?php endif ?>
        </ul>
    </div>

    <div class="container my-5">


        <!-- Banner Tự Động Cuộn -->
        <div id="bannerCarousel" class="carousel slide mb-5" data-ride="carousel">
            <!-- Dấu chấm điều hướng -->
            <ol class="carousel-indicators">
                <li data-target="#bannerCarousel" data-slide-to="0" class="active"></li>
                <li data-target="#bannerCarousel" data-slide-to="1"></li>
                <li data-target="#bannerCarousel" data-slide-to="2"></li>
                <li data-target="#bannerCarousel" data-slide-to="3"></li>
            </ol>

            <!-- Các slide -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/images/banner/banner1.jpg" class="d-block w-100" alt="Banner 1">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/banner/banner2.jpg" class="d-block w-100" alt="Banner 2">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/banner/banner3.jpg" class="d-block w-100" alt="Banner 3">
                </div>
                <div class="carousel-item">
                    <img src="assets/images/banner/banner4.jpg" class="d-block w-100" alt="Banner 4">
                </div>
            </div>

            <!-- Nút điều hướng trái/phải -->
            <a class="carousel-control-prev" href="#bannerCarousel" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#bannerCarousel" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>



        <!-- Sản Phẩm Nổi Bật -->

        <h2 class="text-center mb-4">Sản Phẩm Nổi Bật</h2>
        <div class="row">
            <?php
            $sql = "SELECT * FROM product ORDER BY RAND() LIMIT 8";
            $result = $conn->query($sql);

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
                echo "<p class='text-center w-100'>Chưa có sản phẩm nào.</p>";
            }
            ?>
        </div>
    </div>

</body>
<?php
// Import Footer
include('includes/footer.php');
?>