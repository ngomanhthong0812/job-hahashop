<?php
// Kết nối database
include('./config/init.php');

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
// Xử lý form khi người dùng gửi thông tin cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $updateUser = array(
        "id" => $user_id,
        "username" => $_POST['username'],
        "full_name" => $_POST['full_name'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone'],
        "address" => $_POST['address'],
    );

    // Cập nhật thông tin vào cơ sở dữ liệu
    $sql = "UPDATE user SET username = ?, full_name = ?, email = ?,phone = ?,address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "sssssi",
        $updateUser['username'],
        $updateUser['full_name'],
        $updateUser['email'],
        $updateUser['phone'],
        $updateUser['address'],
        $updateUser['id'],
    );


    // Thực thi câu lệnh
    if ($stmt->execute()) {
        // Chuyển hướng về trang danh sách sau khi cập nhật thành công
        $_SESSION['update_info_message'] = "Cập nhật thông tin người dùng thành công";
        header('Location: my_account.php');
        exit();
    } else {
        echo "Cập nhật thất bại.";
    }
}

include('includes/header.php');
?>

<!-- Liên kết file style.css -->
<link rel="stylesheet" href="assets/css/style.css">


<body>
    <!-- Danh mục sản phẩm -->
    <div class="category-menu">
        <ul class="container nav justify-content-center">
            <!-- Mục Trang Chủ -->
            <li class="nav-item category-item">
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
                <li class="nav-item category-item active">
                    <a class="nav-link" href="my_account.php">Tài khoản</a>
                </li>
            <?php endif ?>
        </ul>
    </div>

    <div class="container my-5">
        <h1 class="text-center mb-4">My Account</h1>

        <?php if (isset($_SESSION['update_info_message'])): ?>
            <div class="alert alert-success">
                <?php echo $_SESSION['update_info_message'] ?>
                <?php unset($_SESSION['update_info_message']) ?>
            </div>
        <?php endif; ?>

        <!-- Thông tin -->
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="card w-100 h-100">
                    <div class="card-header">
                        <h5 class="card-title">Thông tin cá nhân</h5>
                    </div>
                    <div class="card-body">
                        <form action="my_account.php" method="post">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Tên đầy đủ</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo $user['full_name'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo $user['username'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="<?php echo $user['phone'] ?>">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ</label>
                                <input type="tel" class="form-control" id="address" name="address" value="<?php echo $user['address'] ?>">
                            </div>
                            <button type="submit" class="mb-3">
                                <span class="btn btn-primary">Cập nhật thông tin</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Các tuỳ chọn tài khoản -->
        <div class="mt-4">
            <h5>Các tùy chọn tài khoản</h5>
            <ul class="list-group">
                <li class="list-group-item">
                    <a href="#">Thay đổi mật khẩu</a>
                </li>
                <li class="list-group-item">
                    <a href="order_history.php">Xem lịch sử đơn hàng</a>
                </li>
                <li class="list-group-item">
                    <a href="logout.php">Đăng xuất</a>
                </li>
            </ul>
        </div>
    </div>

</body>
<?php
// Import Footer
include('includes/footer.php');
?>