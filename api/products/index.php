<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../../config/database.php';
include_once '../../models/product.php';

$database = new Database();
$db = $database->getConnection();
$product = new Product($db);

// Chuyển method từ header nếu có override
$actualMethod = $_SERVER['REQUEST_METHOD'];
if (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
    $actualMethod = $_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'];
}

$method = $actualMethod;
$uri = explode('/', trim($_SERVER['REQUEST_URI'], '/'));
$id = isset($uri[3]) ? $uri[3] : null;

// Giả sử URL: /MobileStore/api/products/...
$resource = $uri[2] ?? null;  // products
$action = $uri[3] ?? null;    // brand hoặc id hoặc null
$param = $uri[4] ?? null;     // tham số tiếp theo (ví dụ brand name)

function formatImageUrl($imageName) {
    if (!$imageName || $imageName === '') {
        return "/MobileStore/assets/images/no-image.png";
    }
    if (strpos($imageName, 'uploads/') === 0) {
        return "/MobileStore/" . $imageName;
    }
    return $imageName;
}

switch ($method) {
    case 'GET':
        if ($resource === 'products') {

            // XỬ LÝ TRƯỜNG HỢP QUERY STRING ?brand=xxx
            if (isset($_GET['brand'])) {
                $brand = $_GET['brand'];
                $products = $product->getProductsByBrand($brand);

                foreach ($products as &$p) {
                    $p['image_url'] = formatImageUrl($p['image_url']);
                }

                echo json_encode($products);

            } elseif ($action === 'brand' && $param) {
                // Lấy sản phẩm theo brand từ path param
                $brand = urldecode($param);
                $products = $product->getProductsByBrand($brand);

                foreach ($products as &$p) {
                    $p['image_url'] = formatImageUrl($p['image_url']);
                }

                echo json_encode($products);

            } elseif (is_numeric($action)) {
                // Lấy sản phẩm theo ID
                $item = $product->getProductById($action);

                if ($item) {
                    $item['image_url'] = formatImageUrl($item['image_url']);
                }

                echo json_encode($item);

            } else {
                // Lấy tất cả sản phẩm
                $products = $product->getAllProducts();

                foreach ($products as &$p) {
                    $p['image_url'] = formatImageUrl($p['image_url']);
                }

                echo json_encode($products);
            }
        } else {
            http_response_code(404);
            echo json_encode(["message" => "Resource not found"]);
        }
        break;

    case 'POST':
        if ($resource === 'products') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(["message" => "Dữ liệu đầu vào không hợp lệ"]);
                break;
            }
            $result = $product->createProduct($data);
            if ($result['success']) {
                echo json_encode(["message" => "Thêm sản phẩm thành công", "product_id" => $result['product_id']]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => $result['message'] ?? "Không thể thêm sản phẩm"]);
            }
        }
        break;

    case 'PUT':
        if ($resource === 'products' && is_numeric($action)) {
            $data = json_decode(file_get_contents("php://input"), true);
            if (!$data) {
                http_response_code(400);
                echo json_encode(["message" => "Dữ liệu đầu vào không hợp lệ"]);
                break;
            }
            $result = $product->updateProduct($action, $data);
            if ($result['success']) {
                echo json_encode(["message" => "Cập nhật sản phẩm thành công"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => $result['message'] ?? "Không thể cập nhật sản phẩm"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu ID sản phẩm để cập nhật"]);
        }
        break;

    case 'DELETE':
        if ($resource === 'products' && is_numeric($action)) {
            $result = $product->deleteProduct($action);
            if ($result['success']) {
                echo json_encode(["message" => "Xóa sản phẩm thành công"]);
            } else {
                http_response_code(500);
                echo json_encode(["message" => $result['message'] ?? "Không thể xóa sản phẩm"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Thiếu ID sản phẩm để xóa"]);
        }
        break;

    case 'OPTIONS':
        // Phục vụ preflight request của CORS
        http_response_code(200);
        break;

    default:
        http_response_code(405);
        echo json_encode(["message" => "Phương thức không được hỗ trợ"]);
        break;
}
?>
