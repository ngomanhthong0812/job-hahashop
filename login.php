<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

// Xử lý khi form đăng nhập được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Truy vấn kiểm tra email, mật khẩu và kiểm tra trạng thái tài khoản
    $sql = "SELECT * FROM user WHERE email = '$email' AND status = 'active'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kiểm tra mật khẩu
        if (password_verify($password, $user['password'])) {
            // Lưu thông tin người dùng vào session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['username'];
            $_SESSION['user_email'] = $user['email'];

            // Chuyển hướng đến trang chính (hoặc trang nào bạn muốn)
            if (isset($_SESSION['login_message'])) {
                header("Location: cart.php");
                unset($_SESSION['login_message']);
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Email chưa được đăng ký!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Đăng Nhập</h2>
        <form action="login.php" method="POST">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['login_message'])): ?>
                <div class="alert alert-danger"><?php echo $_SESSION['login_message'] ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php if (isset($_POST['email'])) echo $email ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" class="form-control" id="password" value="<?php if (isset($_POST['password'])) echo $password ?>" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng Nhập</button>
        </form>
        <p class="text-center mt-3">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
    </div>
</body>

</html>