<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

// Truy vấn lấy danh mục sản phẩm từ cơ sở dữ liệu
$sql = "SELECT * FROM category";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HaHa Shop</title>

    <!-- Thêm Google Font Pacifico -->
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">

    <!-- Thêm Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Thêm Font Awesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <!-- Thêm Boxicons CSS -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <link rel="stylesheet" href="assets/css/header.css">
</head>

<body>
    <!-- Navbar (Header) -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <!-- Logo "HaHa Shop" ở góc trái -->
            <a class="navbar-brand" href="index.php">
                HaHa Shop
            </a>

            <!-- Các mục ở bên phải (Giỏ hàng, Đăng nhập, Đăng ký) -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <!-- Thanh tìm kiếm -->
                <div class="search-container">
                    <div class="input-group">
                        <!-- Thanh tìm kiếm -->
                        <input type="text" class="form-control" placeholder="Tìm kiếm sản phẩm...">
                        <div class="input-group-append">
                            <span class="input-group-text search-icon">
                                <i class="fas fa-search"></i> <!-- Icon kính lúp -->
                            </span>
                        </div>
                    </div>
                </div>

                <ul class="navbar-nav ml-auto">
                    <!-- Giỏ hàng -->
                    <li class="nav-item">
                        <a class="nav-link" href="cart.php">
                            <i class="fas fa-shopping-cart"></i> Giỏ hàng
                        </a>
                    </li>
                    <?php if (!isset($_SESSION['user_id'])) { ?>
                        <!-- Đăng nhập -->
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">
                                <i class="fas fa-sign-in-alt"></i> Đăng nhập
                            </a>
                        </li>
                        <!-- Đăng ký -->
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">
                                <i class="fas fa-user-plus"></i> Đăng ký
                            </a>
                        </li>
                    <?php } else { ?>
                        <!-- Đăng xuất -->
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">
                                <i class='bx bx-log-out'></i>Đăng xuất
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

    </nav>


    <!-- Thêm Bootstrap JS (nếu cần) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.5/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>