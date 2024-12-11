<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

// Xử lý khi form đăng ký được gửi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $password_confirm = mysqli_real_escape_string($conn, $_POST['password_confirm']);

    // Kiểm tra mật khẩu xác nhận
    if ($password !== $password_confirm) {
        $error = "Mật khẩu không khớp!";
    } else {
        // Mã hóa mật khẩu
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kiểm tra email đã tồn tại trong cơ sở dữ liệu chưa
        $sql_check_email = "SELECT * FROM user WHERE email = '$email'";
        $result = $conn->query($sql_check_email);

        if ($result->num_rows > 0) {
            $error = "Email đã được sử dụng!";
        } else {
            // Lưu thông tin người dùng vào cơ sở dữ liệu
            $sql_insert = "INSERT INTO user (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
            if ($conn->query($sql_insert) === TRUE) {
                $success = "Đăng ký thành công! Vui lòng đăng nhập.";
                $username = '';
                $email = '';
                $password = '';
                $password_confirm = '';
            } else {
                $error = "Có lỗi xảy ra. Vui lòng thử lại!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h2 class="text-center">Đăng Ký Tài Khoản</h2>
        <form action="register.php" method="POST">
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <div class="form-group">
                <label for="username">Tên:</label>
                <input type="text" name="username" class="form-control" id="username" value="<?php if (isset($_POST['username'])) echo $username ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" class="form-control" id="email" value="<?php if (isset($_POST['email'])) echo $email ?>" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" name="password" class="form-control" id="password" value="<?php if (isset($_POST['password'])) echo $password ?>" required>
            </div>

            <div class="form-group">
                <label for="password_confirm">Xác nhận mật khẩu:</label>
                <input type="password" name="password_confirm" class="form-control" id="password_confirm" value="<?php if (isset($_POST['password_confirm'])) echo $password_confirm ?>" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Đăng Ký</button>
        </form>
        <p class="text-center mt-3">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
    </div>
</body>

</html>