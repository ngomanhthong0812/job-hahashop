<?php
// Kết nối database
include('../config/init.php');

// Kiểm tra có truyền category_id qua GET không
if (isset($_GET['category_id']) && $_GET['category_id']) {
    $category_id = $_GET['category_id'];

    // Lấy thông tin của category từ cơ sở dữ liệu
    $sql = "SELECT * FROM category WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $category_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $category = $result->fetch_assoc();
}

// Xử lý form khi người dùng gửi thông tin cập nhật
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy thông tin từ form
    $updateCategory = array(
        "id" => $_POST['id'],
        "name" => $_POST['name'],
        "description" => $_POST['description'],
    );

    // Cập nhật thông tin vào cơ sở dữ liệu
    $sql = "UPDATE category SET name = ?, description = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $updateCategory['name'], $updateCategory['description'], $updateCategory['id']);

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
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>" />
                                <div class="form-group">
                                    <label for="exampleInputName">Name</label>
                                    <input type="text" name="name" value="<?php echo $category['name']; ?>" class="form-control" id="exampleInputName" aria-describedby="emailHelp" placeholder="">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputDescription">Description</label>
                                    <textarea id="myTextarea" type="text" name="description" class="form-control" id="exampleInputDescription" placeholder=""><?php echo $category['description']; ?></textarea>
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