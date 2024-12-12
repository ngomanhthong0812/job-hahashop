<?php
include('../config/init.php');

// Truy vấn dữ liệu
$sql = "
    SELECT 
        DATE(o.created_at) AS order_date,
        SUM(od.quantity * od.price) AS total_revenue,
        SUM(od.quantity) AS total_quantity
    FROM 
        order_detail od
    JOIN 
        `order` o ON od.order_id = o.id
    WHERE 
        o.status != 'cancelled'
    GROUP BY 
        DATE(o.created_at)
    ORDER BY 
        order_date;
";

$result = $conn->query($sql);

$data = [];

if ($result->num_rows > 0) {
    // Lấy dữ liệu từng hàng
    while ($row = $result->fetch_assoc()) {
        $data[] = [
            'order_date' => $row['order_date'],
            'total_revenue' => $row['total_revenue'],
            'total_quantity' => $row['total_quantity']
        ];
    }
}

// Trả về dữ liệu dạng JSON
header('Content-Type: application/json');
echo json_encode($data);

// Đóng kết nối
$conn->close();
