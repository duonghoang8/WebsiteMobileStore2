<?php
// Include các thành phần chung của trang
include 'views/partials/header.html';
include 'views/partials/nav.html';
// Tải CSS riêng cho trang chi tiết, có thể cần điều chỉnh đường dẫn nếu CSS ở nơi khác
echo '<link rel="stylesheet" href="assets/css/product_detail.css">'; 

// Include các tệp cần thiết cho việc truy vấn CSDL
require_once 'config/database.php';
require_once 'models/product.php';

// Lấy ID sản phẩm từ tham số trên URL
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($product_id > 0) {
    // Kết nối CSDL
    $database = new Database();
    $db = $database->getConnection();

    // Khởi tạo đối tượng Product
    $product_model = new Product($db);

    // Lấy dữ liệu sản phẩm từ CSDL
    $product = $product_model->getProductById($product_id);

    // Nếu sản phẩm tồn tại, hiển thị nội dung chi tiết
    if ($product) {
        // Tệp view này sẽ sử dụng biến $product để hiển thị thông tin
        include 'views/partials/product_detail_content.html';
    } else {
        // Thông báo nếu không tìm thấy sản phẩm
        echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'><h2>Sản phẩm không tồn tại!</h2><p>Sản phẩm bạn tìm kiếm không có sẵn hoặc đã bị xóa.</p><a href='index.php'>Quay về trang chủ</a></div>";
    }
} else {
    // Thông báo nếu ID không hợp lệ
    echo "<div style='text-align: center; padding: 50px; font-family: sans-serif;'><h2>Yêu cầu không hợp lệ!</h2><p>Vui lòng chọn một sản phẩm để xem chi tiết.</p><a href='index.php'>Quay về trang chủ</a></div>";
}

// Include footer
include 'views/partials/footer.html';
?>