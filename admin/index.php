<?php
// Kết nối với cơ sở dữ liệu
include('./config/init.php');

// Truy vấn lấy số lượng người dùng mới trong ngày
$sql_new_user = "SELECT COUNT(*) AS new_user_count FROM user WHERE DATE(created_at) = CURDATE()";
$result_new_user = $conn->query($sql_new_user);
$new_user_count = 0;  // Khởi tạo giá trị mặc định là 0

// Nếu có kết quả từ truy vấn, lấy số lượng người dùng mới
if ($result_new_user->num_rows > 0) {
    $row_new_user = $result_new_user->fetch_assoc();
    $new_user_count = $row_new_user['new_user_count'];
}

// Truy vấn lấy số lượng đơn hàng mới trong ngày
$sql_new_order = "SELECT COUNT(*) AS new_order_count FROM `order` WHERE DATE(created_at) = CURDATE()";
$result_new_order = $conn->query($sql_new_order);
$new_order_count = 0;  // Khởi tạo giá trị mặc định là 0

// Nếu có kết quả từ truy vấn, lấy số lượng đơn hàng mới
if ($result_new_order->num_rows > 0) {
    $row_new_order = $result_new_order->fetch_assoc();
    $new_order_count = $row_new_order['new_order_count'];
}

// Truy vấn lấy tổng số lượng sản phẩm trong kho theo size
$sql_inventory_by_size = "
    SELECT size_id, SUM(quantity) AS total_inventory
    FROM product_sizes
    GROUP BY size_id
";
$result_inventory_by_size = $conn->query($sql_inventory_by_size);

// Mảng lưu số lượng sản phẩm theo kích cỡ
$inventory_by_size = [];

// Khởi tạo giá trị mặc định cho tổng số lượng tồn kho
$total_inventory = 0;

// Nếu có kết quả từ truy vấn, lưu vào mảng và tính tổng tồn kho
if ($result_inventory_by_size->num_rows > 0) {
    while ($row_inventory = $result_inventory_by_size->fetch_assoc()) {
        $inventory_by_size[] = [
            'size_id' => $row_inventory['size_id'],
            'total_inventory' => $row_inventory['total_inventory']
        ];
        $total_inventory += $row_inventory['total_inventory'];  // Cộng dồn số lượng vào tổng tồn kho
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
    <link href="./css/style.css" rel="stylesheet" />
    <link href='https://fonts.googleapis.com/css?family=Jost' rel='stylesheet'>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="./js/all.js" crossorigin="anonymous"></script>
    <script src="./js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="./js/scripts.js"></script>
    <script src="./js/Chart.min.js" crossorigin="anonymous"></script>
    <script src="./js/simple-datatables@latest.js" crossorigin="anonymous"></script>
    <script src="./js/datatables-simple-demo.js"></script>
</head>

<body class="sb-nav-fixed">
    <?php include_once './inc/navbar.php' ?>
    <div id="layoutSidenav">
        <?php include_once './inc/sideleft.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Thống kê</h1>
                    <div class="row mt-4">
                        <!-- New User -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="bg-success text-white p-3 rounded shadow-sm d-flex flex-column align-items-center justify-content-center">
                                <i class="bx bxs-user fs-2 text-white mb-2"></i>
                                <div class="fs-3 fw-bolder mb-1">
                                    <?php echo $new_user_count; ?>
                                </div>
                                <div class="fs-6 text-white opacity-75">New User</div>
                            </div>
                        </div>

                        <!-- New Order -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="bg-primary text-white p-3 rounded shadow-sm d-flex flex-column align-items-center justify-content-center">
                                <i class="bx bxs-cart fs-2 text-white mb-2"></i>
                                <div class="fs-3 fw-bolder mb-1">
                                    <?php echo $new_order_count; ?>
                                </div>
                                <div class="fs-6 text-white opacity-75">New Order</div>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="bg-warning text-white p-3 rounded shadow-sm d-flex flex-column align-items-center justify-content-center">
                                <i class="bx bxs-cube fs-2 text-white mb-2"></i>
                                <div class="fs-3 fw-bolder mb-1">
                                    <?php echo $total_inventory; ?>
                                </div>
                                <div class="fs-6 text-white opacity-75">Inventory</div>
                            </div>
                        </div>
                    </div>

                    <!-- chart -->
                    <div class="row mt-3">
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-area me-1"></i>
                                    Area Chart
                                </div>
                                <div class="card-body"><canvas id="myAreaChart" width="100%" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <i class="fas fa-chart-bar me-1"></i>
                                    Bar Chart
                                </div>
                                <div class="card-body"><canvas id="myBarChart" width="100%" height="200"></canvas></div>
                            </div>
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

    <script>
        // Fetch dữ liệu từ API PHP
        fetch('http://localhost/hahashop/admin/core/get_revenue_data.php')
            .then(response => response.json())
            .then(data => {
                // Lấy dữ liệu từ API
                const labels = data.map(item => item.order_date); // Ngày
                const revenues = data.map(item => parseFloat(item.total_revenue)); // Doanh thu
                const quantities = data.map(item => parseInt(item.total_quantity)); // Số lượng bán

                // Cấu hình và vẽ biểu đồ "Sell Number"
                const salesData = {
                    labels: labels,
                    datasets: [{
                        label: 'Sell Number',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        data: quantities,
                    }]
                };

                const salesOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'category',
                            labels: salesData.labels,
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                };

                const ctxSales = document.getElementById('myAreaChart').getContext('2d');
                new Chart(ctxSales, {
                    type: 'line',
                    data: salesData,
                    options: salesOptions
                });

                // Cấu hình và vẽ biểu đồ "Revenue"
                const revenueData = {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        data: revenues,
                    }]
                };

                const revenueOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            type: 'category',
                            labels: revenueData.labels,
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                };

                const ctxRevenue = document.getElementById('myBarChart').getContext('2d');
                new Chart(ctxRevenue, {
                    type: 'bar',
                    data: revenueData,
                    options: revenueOptions
                });
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
</body>

</html>