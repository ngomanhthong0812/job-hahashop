<?php
// Kết nối cơ sở dữ liệu
include('config/db.php');

// Lấy ID danh mục từ URL
$category_id = isset($_GET['category_id']) ? $_GET['category_id'] : 0;

// Truy vấn lấy danh mục sản phẩm theo category_id
$sql_category = "SELECT * FROM category WHERE id = $category_id";
$category_result = $conn->query($sql_category);

// Kiểm tra xem danh mục có tồn tại không
if ($category_result->num_rows > 0) {
    $category = $category_result->fetch_assoc();
} else {
    echo "<p class='text-center'>Danh mục không tồn tại.</p>";
    exit;
}

// Truy vấn lấy sản phẩm thuộc danh mục
$sql_products = "SELECT * FROM product WHERE category_id = $category_id";
$product_result = $conn->query($sql_products);

// Import Header
include('includes/header.php');
?>

<!-- Liên kết file style.css -->
<link rel="stylesheet" href="assets/css/style.css">

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
                // Kiểm tra nếu row id trùng với category_id trong session
                $isActive = ($row['id'] == $category_id) ? 'active' : '';

                echo '<li class="nav-item category-item ' . $isActive . '">
                <a class="nav-link" href="category.php?category_id=' . $row['id'] . '">' . $row['name'] . '</a>
              </li>';
            }
        }
        ?>

        <!-- Mục Liên Hệ -->
        <li class="nav-item category-item">
            <a class="nav-link" href="contact.php">Liên Hệ</a>
        </li>
    </ul>
</div>
<div class="container my-5">
    <!-- Tiêu đề danh mục -->
    <h2 class="text-center mb-4">Sản Phẩm Giày Cho <?php echo $category['name']; ?></h2>

    <!-- Hiển thị sản phẩm -->
    <div class="row">
        <?php
        if ($product_result->num_rows > 0) {
            while ($row = $product_result->fetch_assoc()) {
                echo '
                <div class="col-sm-6 col-md-4 col-lg-3 mb-4">
                    <div class="card shadow border-0">
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
            echo "<p class='text-center'>Chưa có sản phẩm nào trong danh mục này.</p>";
        }
        ?>
    </div>
</div>

<?php
// Import Footer
include('includes/footer.php');
?>