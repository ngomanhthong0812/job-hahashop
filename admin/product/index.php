<?php
// Kết nối database
include('../config/init.php');

// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM product";
$product_list = $conn->query($sql);
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
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Created At</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>STT</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Created At</th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php $index = 0; ?>
                                    <?php foreach ($product_list as $product) { ?>
                                        <tr>
                                            <td>
                                                <?php echo ++$index; ?>
                                            </td>
                                            <td>
                                                <?php echo $product['name']; ?>
                                            </td>
                                            <td>
                                                <?php echo $product['description']; ?>
                                            </td>
                                            <td>
                                                <?php
                                                // Lấy category_id từ sản phẩm
                                                $category_id = $product['category_id'];

                                                // Truy vấn lấy tên category
                                                $sql = "SELECT name FROM category WHERE id = ?";
                                                $stmt = $conn->prepare($sql);
                                                $stmt->bind_param("i", $category_id);
                                                $stmt->execute();
                                                $stmt->bind_result($category_name);

                                                // Hiển thị tên category
                                                if ($stmt->fetch()) {
                                                    echo $category_name;
                                                } else {
                                                    echo "Không tìm thấy tên category";
                                                }

                                                $stmt->close();
                                                ?>
                                            </td>
                                            <td>
                                                <?php echo $product['price']; ?>
                                            </td>
                                            <td>
                                                <?php echo $product['created_at']; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary"
                                                    href="edit.php?product_id=<?php echo $product['id']; ?>">Edit</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-danger"
                                                    href="delete.php?product_id=<?php echo $product['id']; ?>">Delete</a>
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