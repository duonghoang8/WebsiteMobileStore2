<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

include_once '../../config/database.php';
include_once '../../models/product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

$method = $_SERVER['REQUEST_METHOD'];
if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    $method = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
}

function formatImageUrl($imageName) {
    if (!$imageName || $imageName === '') {
        // Đường dẫn đến ảnh mặc định. Giả định trang web chạy ở thư mục gốc.
        // Nếu trang web của bạn nằm trong thư mục con (ví dụ: /WebsiteMobileStore2/), 
        // bạn có thể cần thêm tên thư mục con vào trước: '/WebsiteMobileStore2/assets/...'
        return 'assets/images/no-image.png';
    }
    // Giữ nguyên các đường dẫn đã hoàn chỉnh
    if (strpos($imageName, 'http') === 0 || strpos($imageName, 'assets/') === 0) {
        return $imageName;
    }
    // Xử lý các đường dẫn tương đối từ thư mục uploads
    if (strpos($imageName, 'uploads/') === 0) {
        return 'uploads/' . basename($imageName);
    }
    return $imageName;
}

switch ($method) {
    case 'GET':
        // Lấy sản phẩm theo ID từ query string "?id="
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $item = $product->getProductById($_GET['id']);
            if ($item) {
                $item['image_url'] = formatImageUrl($item['image_url']);
                http_response_code(200);
                echo json_encode($item);
            } else {
                http_response_code(404);
                echo json_encode(["message" => "Product not found"]);
            }
        }
        // Lấy sản phẩm theo brand từ query string "?brand="
        elseif (isset($_GET['brand'])) {
            $products = $product->getProductsByBrand($_GET['brand']);
            foreach ($products as &$p) {
                $p['image_url'] = formatImageUrl($p['image_url']);
            }
            http_response_code(200);
            echo json_encode($products);
        }
        // Mặc định: Lấy tất cả sản phẩm
        else {
            $products = $product->getAllProducts();
            foreach ($products as &$p) {
                $p['image_url'] = formatImageUrl($p['image_url']);
            }
            http_response_code(200);
            echo json_encode($products);
        }
        break;

    // Các case POST, PUT, DELETE giữ nguyên như cũ
    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input data"]);
            break;
        }
        $result = $product->createProduct($data);
        if ($result['success']) {
            http_response_code(201);
            echo json_encode(["message" => "Product created successfully", "product_id" => $result['product_id']]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => $result['message'] ?? "Could not create product"]);
        }
        break;

    case 'PUT':
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["message" => "Product ID is required for update"]);
            break;
        }
        $data = json_decode(file_get_contents("php://input"), true);
        $result = $product->updateProduct($id, $data);
        if ($result['success']) {
            echo json_encode(["message" => "Product updated successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => $result['message'] ?? "Could not update product"]);
        }
        break;

    case 'DELETE':
        $id = $_GET['id'] ?? null;
        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(["message" => "Product ID is required for deletion"]);
            break;
        }
        $result = $product->deleteProduct($id);
        if ($result['success']) {
            echo json_encode(["message" => "Product deleted successfully"]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => $result['message'] ?? "Could not delete product"]);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Method not allowed"]);
        break;
}
?>