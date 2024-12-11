<?php
// Kết nối cơ sở dữ liệu
include('config/init.php');

// Xử lý xóa sản phẩm khỏi giỏ hàng
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['key'])) {
    $key = intval($_GET['key']);
    if (isset($_SESSION['cart'][$key])) {
        unset($_SESSION['cart'][$key]); // Xóa sản phẩm theo key
        $_SESSION['cart'] = array_values($_SESSION['cart']); // Sắp xếp lại key
    }
    header("Location: cart.php");
    exit;
}

// Xử lý cập nhật giỏ hàng
if (isset($_POST['update_cart'])) {
    foreach ($_POST['cart'] as $key => $item) {
        $size = intval($item['size']);
        $quantity = intval($item['quantity']);

        if ($size > 0 && $quantity > 0) {
            $_SESSION['cart'][$key]['size'] = $size;
            $_SESSION['cart'][$key]['quantity'] = $quantity;
        }
    }
    header("Location: cart.php");
    exit;
}

// Lấy thông tin giỏ hàng từ cơ sở dữ liệu
$cart_items = [];
$total_price = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $cart_item) {
        $product_id = intval($cart_item['product_id']);
        $size_id = intval($cart_item['size']);

        // Lấy thông tin sản phẩm
        $sql_product = "SELECT * FROM product WHERE id = $product_id";
        $product_result = $conn->query($sql_product);
        $product = $product_result ? $product_result->fetch_assoc() : null;

        // Lấy thông tin size
        $sql_size = "SELECT * FROM sizes WHERE id = $size_id";
        $size_result = $conn->query($sql_size);
        $size = $size_result ? $size_result->fetch_assoc() : null;

        if ($product && $size) {
            $cart_items[] = [
                'key' => $key,
                'product' => $product,
                'size' => $size,
                'quantity' => $cart_item['quantity']
            ];
            $total_price += $product['price'] * $cart_item['quantity'];
        }
    }
}
?>

<?php include('includes/header.php'); ?>

<div class="container my-5">
    <h1 class="text-center mb-4">Giỏ Hàng Của Bạn</h1>

    <?php if (empty($cart_items)) : ?>
        <div class="alert alert-warning text-center">
            <strong>Giỏ hàng của bạn đang trống.</strong>
        </div>
        <div class="text-center">
            <a href="index.php" class="btn btn-primary btn-lg">Quay lại mua sắm</a>
        </div>
    <?php else : ?>
        <form method="POST">
            <!-- Bảng giỏ hàng -->
            <div class="table-responsive shadow-lg rounded-4 bg-white">
                <table class="table table-borderless align-middle mb-0">
                    <thead class="table-warning text-center">
                        <tr>
                            <th>Hình Ảnh</th>
                            <th>Sản Phẩm</th>
                            <th>Size</th>
                            <th>Số Lượng</th>
                            <th>Đơn Giá</th>
                            <th>Thành Tiền</th>
                            <th>Thao Tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $index => $item) : ?>
                            <tr class="<?php echo $index % 2 == 0 ? 'bg-light' : ''; ?>">
                                <td class="text-center">
                                    <img src="assets/images/products/<?php echo htmlspecialchars($item['product']['image']); ?>" alt="<?php echo htmlspecialchars($item['product']['name']); ?>" class="img-thumbnail rounded" style="width: 80px; height: auto;">
                                </td>
                                <td>
                                    <strong><?php echo htmlspecialchars($item['product']['name']); ?></strong>
                                </td>
                                <td>
                                    <select name="cart[<?php echo $item['key']; ?>][size]" class="form-select form-select-sm">
                                        <?php
                                        $product_id = $item['product']['id'];
                                        $sql_sizes = "SELECT sizes.id, sizes.name FROM sizes
                                                      JOIN product_sizes ON sizes.id = product_sizes.size_id
                                                      WHERE product_sizes.product_id = $product_id";
                                        $sizes_result = $conn->query($sql_sizes);
                                        if ($sizes_result->num_rows > 0) {
                                            while ($size_option = $sizes_result->fetch_assoc()) {
                                                $selected = $size_option['id'] == $item['size']['id'] ? 'selected' : '';
                                                echo "<option value='{$size_option['id']}' $selected>{$size_option['name']}</option>";
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td class="text-center">
                                    <input type="number" name="cart[<?php echo $item['key']; ?>][quantity]" class="form-control text-center" value="<?php echo $item['quantity']; ?>" min="1" max="10">
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($item['product']['price'], 0, ',', '.') . " VNĐ"; ?>
                                </td>
                                <td class="text-center">
                                    <?php echo number_format($item['product']['price'] * $item['quantity'], 0, ',', '.') . " VNĐ"; ?>
                                </td>
                                <td class="text-center">
                                    <a href="cart.php?action=remove&key=<?php echo $item['key']; ?>" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Tổng tiền -->
            <div class="text-end my-4">
                <h4>Tổng Tiền: <strong class="text-danger"><?php echo number_format($total_price, 0, ',', '.') . " VNĐ"; ?></strong></h4>
            </div>

            <!-- Nút cập nhật và thanh toán -->
            <div class="text-end">
                <button type="submit" name="update_cart" class="btn btn-success btn-lg me-2">
                    <i class="fas fa-sync-alt"></i> Cập Nhật Giỏ Hàng
                </button>
                <a href="checkout.php" class="btn btn-primary btn-lg">
                    <i class="fas fa-credit-card"></i> Thanh Toán
                </a>
            </div>
        </form>
    <?php endif; ?>
</div>




<style>
    /* Thay đổi màu nền cho phần header của bảng */
    .table-warning {
        background-color: #f5a35b;
        color: white;
    }


    /* Thêm bóng cho bảng */
    .shadow-lg {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 1.5);
    }

    /* Màu nền xen kẽ giữa các hàng */
    .bg-light {
        background-color: #fcefe3 !important;
    }
</style>


<?php include('includes/footer.php'); ?>