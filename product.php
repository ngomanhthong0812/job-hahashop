<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

// Lấy ID sản phẩm từ URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Truy vấn lấy thông tin chi tiết sản phẩm
$sql_product = "SELECT * FROM product WHERE id = $product_id";
$product_result = $conn->query($sql_product);

// Kiểm tra sản phẩm có tồn tại hay không
if ($product_result && $product_result->num_rows > 0) {
    $product = $product_result->fetch_assoc();
} else {
    echo "<p class='text-center'>Sản phẩm không tồn tại.</p>";
    exit;
}

// Lấy danh sách sizes của sản phẩm từ bảng product_sizes và sizes
$sql_sizes = "SELECT sizes.id, sizes.name, product_sizes.quantity 
              FROM sizes 
              JOIN product_sizes ON sizes.id = product_sizes.size_id 
              WHERE product_sizes.product_id = $product_id";
$sizes_result = $conn->query($sql_sizes);

// Thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_to_cart'])) {
    // Khởi tạo giỏ hàng nếu chưa tồn tại
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // Lấy dữ liệu từ form
    $size = isset($_POST['size']) ? intval($_POST['size']) : 0;
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;

    // Kiểm tra dữ liệu hợp lệ
    if ($size > 0 && $quantity > 0) {
        $found = false;

        // Duyệt qua giỏ hàng để kiểm tra sản phẩm đã tồn tại chưa
        foreach ($_SESSION['cart'] as &$item) {
            if ($item['product_id'] == $product_id && $item['size'] == $size) {
                $item['quantity'] += $quantity; // Cập nhật số lượng
                $found = true;
                break;
            }
        }

        // Nếu sản phẩm chưa tồn tại, thêm mới
        if (!$found) {
            $_SESSION['cart'][] = [
                'product_id' => $product_id,
                'size' => $size,
                'quantity' => $quantity
            ];
        }

        // Chuyển hướng sang trang giỏ hàng
        header("Location: cart.php");
        exit;
    } else {
        echo "<p class='text-danger text-center'>Vui lòng chọn size và số lượng hợp lệ.</p>";
    }
}
?>

<?php
// Lấy dữ liệu từ cơ sở dữ liệu
$sql = "SELECT * FROM feedback WHERE product_id = $product_id ORDER BY created_at DESC";
$feedback_list = $conn->query($sql);
?>

<?php include('includes/header.php'); ?>

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
            <li class="nav-item category-item">
                <a class="nav-link" href="my_account.php">Tài khoản</a>
            </li>
        <?php endif ?>
    </ul>
</div>
<div class="container my-5">
    <div class="row product-row">
        <!-- Hình ảnh sản phẩm -->
        <div class="col-md-6 product-image">
            <img src="assets/images/products/<?php echo htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?php echo htmlspecialchars($product['name']); ?>">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6 product-info">
            <h2><?php echo htmlspecialchars($product['name']); ?></h2>
            <p class="text-muted"><?php echo number_format($product['price'], 0, ',', '.') . " VNĐ"; ?></p>
            <p style="color: black"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>

            <!-- Form Thêm vào giỏ hàng -->
            <form method="POST">
                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">

                <!-- Chọn Size -->
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="size"><strong>Chọn Size</strong></label>
                            <select name="size" id="size" class="form-control" required>
                                <option value="">Size</option>
                                <?php
                                if ($sizes_result->num_rows > 0) {
                                    while ($size = $sizes_result->fetch_assoc()) {
                                        echo '<option value="' . $size['id'] . '">' . htmlspecialchars($size['name']) . ' - Còn lại: ' . $size['quantity'] . '</option>';
                                    }
                                } else {
                                    echo '<option value="">Không có size</option>';
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Chọn số lượng -->
                        <div class="col-md-3">
                            <label for="quantity"><strong>Số Lượng</strong></label>
                            <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="10" value="1" required>
                        </div>

                        <!-- Nút thêm vào giỏ hàng -->
                        <div class="col-md-3">
                            <button type="submit" name="add_to_cart" class="btn btn-block">Thêm vào giỏ hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h3>Đánh giá</h3>
    <hr>

    <!-- Form Thêm Bình Luận -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Để lại bình luận</h5>
            <form method="POST" action="submit_comment.php">
                <input type="text" class="form-control" id="product_id" name="product_id" value="<?php echo $product_id ?>" hidden>
                <div class="form-group">
                    <label for="comment_rating">Số sao:</label>
                    <input type="number" min='0' max='5' class="form-control" id="comment_rating" name="rating" placeholder="Nhập số sao" required>
                </div>
                <div class="form-group">
                    <label for="comment_comment">Bình luận:</label>
                    <textarea class="form-control" id="comment_comment" name="comment" rows="4" placeholder="Viết bình luận của bạn ở đây" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi bình luận</button>
            </form>
        </div>
    </div>

    <!-- Hiển thị danh sách bình luận -->
    <div class="comments-list">
        <?php foreach ($feedback_list as $index => $item): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <?php
                    // Lấy user_id từ feedback
                    $user_id = $item['user_id'];
                    if (isset($_SESSION['user_id']) && $user_id === $_SESSION['user_id']) {
                        echo '<h5 class="card-title">Tôi</h5>';
                    } else {
                        // Truy vấn lấy tên sản phẩm và ảnh của sản phẩm
                        $sql = "SELECT full_name FROM user WHERE id = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $user_id);
                        $stmt->execute();
                        $stmt->bind_result($user_name);

                        // Hiển thị tên sản phẩm và ảnh
                        if ($stmt->fetch()) {
                            echo '<h5 class="card-title">' . $user_name . '</h5>';
                        } else {
                            echo '<h5 class="card-title">Guest</h5>';
                        }

                        $stmt->close();
                    }
                    ?>
                    <p class="card-text">Đánh giá <?php echo $item['rating'] ?> sao</p>
                    <p class="card-text">Bình luận: <?php echo $item['comment'] ?></p>
                    <small><?php echo $item['created_at'] ?></small>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<style>
    /* CSS đã giữ nguyên không thay đổi */
    .product-row {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        align-items: flex-start;
        gap: 30px;
    }

    .product-image {
        flex: 1 1 50%;
        max-width: 500px;
        min-width: 300px;
    }

    .product-image img {
        max-width: 100%;
        height: auto;
        border-radius: 10px;
    }

    .product-info {
        flex: 1 1 50%;
    }

    h2 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 1rem;
    }

    .text-muted {
        font-size: 2rem !important;
        font-weight: bold;
        color: #ffa14e !important;
        margin-bottom: 1rem;
    }

    .product-info p {
        font-size: 1.1rem;
        color: #555;
    }

    #size {
        border: 2px solid #ffa14e;
        width: 110px;
        margin-bottom: 15px;
    }

    #quantity {
        border: 2px solid #ffa14e;
        width: 100px;
        margin-bottom: 15px;
    }

    .btn-block {
        font-weight: bold;
        width: 200px;
        margin-top: 25px;
        background-color: #ffa14e;
        border-radius: 20px;
        padding: 10px 20px;
        color: white;
    }

    .btn-block:hover {
        border: 2px solid #ffa14e;
        background-color: #fff3e0;
    }
</style>

<?php include('includes/footer.php'); ?>