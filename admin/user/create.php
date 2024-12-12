<?php
// Kết nối database
include('../config/init.php');

// Xử lý form khi người dùng gửi thông tin cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form và thoát các ký tự đặc biệt
    $createUser = array(
        "id" => isset($_POST['id']) ? $_POST['id'] : null,
        "username" => isset($_POST['username']) ? $_POST['username'] : '',
        "password" => isset($_POST['password']) ? $_POST['password'] : '',
        "full_name" => isset($_POST['full_name']) ? $_POST['full_name'] : '',
        "email" => isset($_POST['email']) ? $_POST['email'] : '',
        "phone" => isset($_POST['phone']) ? $_POST['phone'] : '',
        "address" => isset($_POST['address']) ? $_POST['address'] : '',
    );

    // Kiểm tra các trường bắt buộc
    if (empty($createUser['username']) || empty($createUser['email']) || empty($createUser['password'])) {
        $error = "Vui lòng điền đầy đủ các trường: Tên đăng nhập, Email và Mật khẩu.";
    } else {
        // Kiểm tra email đã tồn tại trong cơ sở dữ liệu
        $email = $conn->real_escape_string($createUser['email']);
        $sql_check_email = "SELECT * FROM user WHERE email = ?";
        $stmt_check_email = $conn->prepare($sql_check_email);

        // Kiểm tra câu lệnh prepare có thành công không
        if ($stmt_check_email) {
            $stmt_check_email->bind_param("s", $email);
            $stmt_check_email->execute();
            $result = $stmt_check_email->get_result();

            if ($result->num_rows > 0) {
                $error = "Email đã được sử dụng!";
            } else {
                // Hash mật khẩu nếu có
                if (!empty($createUser['password'])) {
                    $hashed_password = password_hash($createUser['password'], PASSWORD_DEFAULT);
                }

                // Câu lệnh SQL để chèn dữ liệu vào cơ sở dữ liệu
                $sql = "INSERT INTO user (username, password, full_name, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);

                // Kiểm tra xem câu lệnh prepare có thành công không
                if ($stmt) {
                    $stmt->bind_param(
                        "ssssss",
                        $createUser['username'],
                        $hashed_password,
                        $createUser['full_name'],
                        $createUser['email'],
                        $createUser['phone'],
                        $createUser['address']
                    );

                    // Thực thi câu lệnh
                    if ($stmt->execute()) {
                        // Chuyển hướng về trang danh sách sau khi cập nhật thành công
                        header('Location: index.php');
                        exit();
                    } else {
                        $error = "Có lỗi xảy ra khi thêm người dùng.";
                    }
                } else {
                    $error = "Có lỗi xảy ra khi chuẩn bị câu lệnh thêm người dùng.";
                }
            }
        } else {
            $error = "Có lỗi xảy ra khi kiểm tra email.";
        }
    }
}
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
    <link rel="shortcut icon" href="../../public/img/logo/123.png" type="image/x-icon">
    <link href="../css/style.css" rel="stylesheet" />
    <script src="../js/all.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/autosize.js/4.0.2/autosize.min.js"></script>
    <script>
        // Chọn trường nhập và áp dụng autosize
        autosize(document.getElementById('myTextarea'));
    </script>
</head>

<body class="sb-nav-fixed">
    <?php include_once '../inc/navbar.php' ?>
    <div id="layoutSidenav">
        <?php include_once '../inc/sideleft.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Thêm loại sản phẩm</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <div class="card-body">
                            <form action="create.php" method="post">
                                <input type="hidden" name="id" value="<?php echo isset($createUser['id']) ? $createUser['id'] : ''; ?>" />
                                <div class="form-group">
                                    <label for="exampleInputUserName">User Name</label>
                                    <input type="text" name="username" class="form-control" id="exampleInputUserName" value="<?php echo isset($createUser['username']) ? $createUser['username'] : ''; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword">Password</label>
                                    <input type="text" name="password" class="form-control" id="exampleInputPassword" value="<?php echo isset($createUser['password']) ? $createUser['password'] : ''; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFullName">Full Name</label>
                                    <input type="text" name="full_name" class="form-control" id="exampleInputFullName" value="<?php echo isset($createUser['full_name']) ? $createUser['full_name'] : ''; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email</label>
                                    <input type="text" name="email" class="form-control" id="exampleInputEmail" value="<?php echo isset($createUser['email']) ? $createUser['email'] : ''; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPhone">Phone</label>
                                    <input type="text" name="phone" class="form-control" id="exampleInputPhone" value="<?php echo isset($createUser['phone']) ? $createUser['phone'] : ''; ?>" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputAddress">Address</label>
                                    <input type="text" name="address" class="form-control" id="exampleInputAddress" value="<?php echo isset($createUser['address']) ? $createUser['address'] : ''; ?>" />
                                </div>
                                <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Submit</button>
                            </form>
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