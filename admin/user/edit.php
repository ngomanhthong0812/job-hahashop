<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền user_id qua GET không
if (isset($_GET['user_id']) && $_GET['user_id']) {
    $user_id = $_GET['user_id'];

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
        "id" => $_POST['id'],
        "username" => $_POST['username'],
        "password" => $_POST['password'],
        "full_name" => $_POST['full_name'],
        "email" => $_POST['email'],
        "phone" => $_POST['phone'],
        "address" => $_POST['address'],
    );


    // Nếu có mật khẩu mới, hash mật khẩu trước khi lưu vào cơ sở dữ liệu
    if (!empty($updateUser['password'])) {
        // Hash mật khẩu mới
        $hashed_password = password_hash($updateUser['password'], PASSWORD_DEFAULT);
    } else {
        // Nếu không có mật khẩu mới, giữ nguyên mật khẩu cũ
        $hashed_password = $updateUser['password'];
    }

    // Cập nhật thông tin vào cơ sở dữ liệu
    $sql = "UPDATE user SET username = ?, password = ?, full_name = ?, email = ?,phone = ?,address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssssi",
        $updateUser['username'],
        $hashed_password,
        $updateUser['full_name'],
        $updateUser['email'],
        $updateUser['phone'],
        $updateUser['address'],
        $updateUser['id'],
    );


    // Thực thi câu lệnh
    if ($stmt->execute()) {
        // Chuyển hướng về trang danh sách sau khi cập nhật thành công
        header('Location: index.php');
        exit();
    } else {
        echo "Cập nhật thất bại.";
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
                    <h1 class="mt-4">Cập nhật loại sản phẩm</h1>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DataTable Example
                        </div>
                        <div class="card-body">
                            <form action="edit.php" method="post">
                                <input type="hidden" name="id" value="<?php echo $user['id']; ?>" />
                                <div class="form-group">
                                    <label for="exampleInputUserName">User Name</label>
                                    <input type="text" name="username" value="<?php echo $user['username']; ?>" class="form-control" id="exampleInputUserName" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword">Password</label>
                                    <input type="text" name="password" class="form-control" id="exampleInputPassword" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFullName">Full Name</label>
                                    <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>" class="form-control" id="exampleInputPrice" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail">Email</label>
                                    <input type="text" name="email" value="<?php echo $user['email']; ?>" class="form-control" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPhone">Phone</label>
                                    <input type="text" name="phone" value="<?php echo $user['phone']; ?>" class="form-control" id="exampleInputPhone" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputAddress">Address</label>
                                    <input type="text" name="address" value="<?php echo $user['address']; ?>" class="form-control" id="exampleInputAddress" aria-describedby="emailHelp" placeholder="">
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