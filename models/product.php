<?php
class Product {
    private $conn;
    private $table_name = "products";

    public function __construct($db) {
        $this->conn = $db;
    }

    // Lấy tất cả sản phẩm
    public function getAllProducts() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Lấy sản phẩm theo brand
    public function getProductsByBrand($brand) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE brand = :brand ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":brand", $brand);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo category
    public function getProductsByCategory($category) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE category = :category ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":category", $category);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm theo ID
    public function getProductById($id) {
        $query = "SELECT * FROM " . $this->table_name . " WHERE product_id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Tìm kiếm sản phẩm theo tên
    public function searchProducts($keyword) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE name LIKE :keyword OR brand LIKE :keyword OR model LIKE :keyword 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $searchTerm = "%{$keyword}%";
        $stmt->bindParam(":keyword", $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tạo sản phẩm mới
    public function createProduct($data) {
        $query = "INSERT INTO " . $this->table_name . " 
                  (name, brand, model, price, old_price, discount_percent, description, 
                   stock_quantity, image_url, badge, category, features, rating, review_count)
                  VALUES (:name, :brand, :model, :price, :old_price, :discount_percent, :description, 
                          :stock_quantity, :image_url, :badge, :category, :features, :rating, :review_count)";
        
        $stmt = $this->conn->prepare($query);

        // Xử lý dữ liệu trước khi insert
        $name = trim($data['name']);
        $brand = trim($data['brand']);
        $model = isset($data['model']) ? trim($data['model']) : '';
        $price = isset($data['price']) && $data['price'] !== '' ? floatval($data['price']) : null;
        $old_price = isset($data['old_price']) && $data['old_price'] !== '' ? floatval($data['old_price']) : null;
        $discount_percent = isset($data['discount_percent']) && $data['discount_percent'] !== '' ? intval($data['discount_percent']) : 0;
        $description = isset($data['description']) ? trim($data['description']) : '';
        $stock_quantity = isset($data['stock_quantity']) ? intval($data['stock_quantity']) : 0;
        $image_url = isset($data['image_url']) ? trim($data['image_url']) : '';
        $badge = isset($data['badge']) ? trim($data['badge']) : '';
        $category = isset($data['category']) ? trim($data['category']) : 'phone';
        
        // Xử lý features từ string thành JSON
        $features = '';
        if (isset($data['features']) && !empty(trim($data['features']))) {
            $featuresArray = array_map('trim', explode(',', $data['features']));
            $features = json_encode($featuresArray, JSON_UNESCAPED_UNICODE);
        }
        
        $rating = isset($data['rating']) ? trim($data['rating']) : '★★★★★';
        $review_count = isset($data['review_count']) ? intval($data['review_count']) : 0;

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":brand", $brand);
        $stmt->bindParam(":model", $model);
        $stmt->bindParam(":price", $price, PDO::PARAM_STR);
        $stmt->bindParam(":old_price", $old_price, PDO::PARAM_STR);
        $stmt->bindParam(":discount_percent", $discount_percent, PDO::PARAM_INT);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":stock_quantity", $stock_quantity, PDO::PARAM_INT);
        $stmt->bindParam(":image_url", $image_url);
        $stmt->bindParam(":badge", $badge);
        $stmt->bindParam(":category", $category);
        $stmt->bindParam(":features", $features);
        $stmt->bindParam(":rating", $rating);
        $stmt->bindParam(":review_count", $review_count, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true, 'product_id' => $this->conn->lastInsertId()];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi tạo sản phẩm: ' . implode(', ', $stmt->errorInfo())];
        }
    }

    // Cập nhật sản phẩm
    public function updateProduct($id, $data) {
        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, brand = :brand, model = :model, price = :price, 
                      old_price = :old_price, discount_percent = :discount_percent,
                      description = :description, stock_quantity = :stock_quantity, 
                      image_url = :image_url, badge = :badge, category = :category,
                      features = :features, rating = :rating, review_count = :review_count,
                      updated_at = CURRENT_TIMESTAMP
                  WHERE product_id = :product_id";

        $stmt = $this->conn->prepare($query);

        // Xử lý dữ liệu trước khi update
        $name = trim($data['name']);
        $brand = trim($data['brand']);
        $model = isset($data['model']) ? trim($data['model']) : '';
        $price = isset($data['price']) && $data['price'] !== '' ? floatval($data['price']) : null;
        $old_price = isset($data['old_price']) && $data['old_price'] !== '' ? floatval($data['old_price']) : null;
        $discount_percent = isset($data['discount_percent']) && $data['discount_percent'] !== '' ? intval($data['discount_percent']) : 0;
        $description = isset($data['description']) ? trim($data['description']) : '';
        $stock_quantity = isset($data['stock_quantity']) ? intval($data['stock_quantity']) : 0;
        $image_url = isset($data['image_url']) ? trim($data['image_url']) : '';
        $badge = isset($data['badge']) ? trim($data['badge']) : '';
        $category = isset($data['category']) ? trim($data['category']) : 'phone';
        
        // Xử lý features từ string thành JSON
        $features = '';
        if (isset($data['features']) && !empty(trim($data['features']))) {
            if (is_string($data['features'])) {
                $featuresArray = array_map('trim', explode(',', $data['features']));
            } else {
                $featuresArray = $data['features'];
            }
            $features = json_encode($featuresArray, JSON_UNESCAPED_UNICODE);
        }
        
        $rating = isset($data['rating']) ? trim($data['rating']) : '★★★★★';
        $review_count = isset($data['review_count']) ? intval($data['review_count']) : 0;

        $stmt->bindParam(":name", $name);
        $stmt->bindParam(":brand", $brand);
        $stmt->bindParam(":model", $model);
        $stmt->bindParam(":price", $price, PDO::PARAM_STR);
        $stmt->bindParam(":old_price", $old_price, PDO::PARAM_STR);
        $stmt->bindParam(":discount_percent", $discount_percent, PDO::PARAM_INT);
        $stmt->bindParam(":description", $description);
        $stmt->bindParam(":stock_quantity", $stock_quantity, PDO::PARAM_INT);
        $stmt->bindParam(":image_url", $image_url);
        $stmt->bindParam(":badge", $badge);
        $stmt->bindParam(":category", $category);
        $stmt->bindParam(":features", $features);
        $stmt->bindParam(":rating", $rating);
        $stmt->bindParam(":review_count", $review_count, PDO::PARAM_INT);
        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => 'Lỗi khi cập nhật sản phẩm: ' . implode(', ', $stmt->errorInfo())];
        }
    }

    // Xóa sản phẩm
    public function deleteProduct($id) {
        // Kiểm tra xem sản phẩm có tồn tại không
        $checkQuery = "SELECT product_id FROM " . $this->table_name . " WHERE product_id = :product_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":product_id", $id, PDO::PARAM_INT);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() === 0) {
            return ['success' => false, 'message' => 'Không tìm thấy sản phẩm với ID: ' . $id];
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['success' => true, 'deleted' => $stmt->rowCount()];
        }
        return ['success' => false, 'message' => 'Không thể xóa sản phẩm'];
    }

    // Cập nhật số lượng tồn kho
    public function updateStock($id, $quantity) {
        $query = "UPDATE " . $this->table_name . " 
                  SET stock_quantity = :stock_quantity, updated_at = CURRENT_TIMESTAMP 
                  WHERE product_id = :product_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":stock_quantity", $quantity, PDO::PARAM_INT);
        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT);
        
        if ($stmt->execute()) {
            return ['success' => true];
        }
        return ['success' => false, 'message' => 'Không thể cập nhật tồn kho'];
    }

    // Lấy sản phẩm hot (có badge hoặc discount cao)
    public function getHotProducts($limit = 10) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE badge IN ('Hot', 'New', 'Sale', 'Best Seller') OR discount_percent > 10
                  ORDER BY discount_percent DESC, created_at DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy thống kê cơ bản
    public function getBasicStats() {
        $stats = [];
        
        // Tổng số sản phẩm
        $query = "SELECT COUNT(*) as total FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_products'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Sản phẩm theo thương hiệu
        $query = "SELECT brand, COUNT(*) as count FROM " . $this->table_name . " GROUP BY brand";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['by_brand'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Sản phẩm hết hàng
        $query = "SELECT COUNT(*) as out_of_stock FROM " . $this->table_name . " WHERE stock_quantity = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['out_of_stock'] = $stmt->fetch(PDO::FETCH_ASSOC)['out_of_stock'];
        
        return $stats;
    }

    // Lấy sản phẩm với phân trang
    public function getProductsPaginated($page = 1, $limit = 10, $brand = null, $category = null) {
        $offset = ($page - 1) * $limit;
        
        // Xây dựng WHERE clause
        $whereClause = "1=1";
        $params = [];
        
        if ($brand) {
            $whereClause .= " AND brand = :brand";
            $params[':brand'] = $brand;
        }
        
        if ($category) {
            $whereClause .= " AND category = :category";
            $params[':category'] = $category;
        }
        
        // Query lấy dữ liệu
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE " . $whereClause . " 
                  ORDER BY created_at DESC 
                  LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Query đếm tổng số
        $countQuery = "SELECT COUNT(*) as total FROM " . $this->table_name . " WHERE " . $whereClause;
        $countStmt = $this->conn->prepare($countQuery);
        
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        
        $countStmt->execute();
        $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return [
            'products' => $products,
            'total' => $totalCount,
            'page' => $page,
            'limit' => $limit,
            'total_pages' => ceil($totalCount / $limit)
        ];
    }

    // Lấy sản phẩm có giá trong khoảng
    public function getProductsByPriceRange($minPrice, $maxPrice) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE price BETWEEN :min_price AND :max_price 
                  ORDER BY price ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":min_price", $minPrice, PDO::PARAM_STR);
        $stmt->bindParam(":max_price", $maxPrice, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm liên quan (cùng brand hoặc category)
    public function getRelatedProducts($productId, $brand = null, $category = null, $limit = 5) {
        $whereClause = "product_id != :product_id";
        $params = [':product_id' => $productId];
        
        if ($brand) {
            $whereClause .= " AND brand = :brand";
            $params[':brand'] = $brand;
        }
        
        if ($category) {
            $whereClause .= " AND category = :category";
            $params[':category'] = $category;
        }
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE " . $whereClause . " 
                  ORDER BY RAND() 
                  LIMIT :limit";
        
        $stmt = $this->conn->prepare($query);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm mới nhất
    public function getLatestProducts($limit = 10) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  ORDER BY created_at DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm giảm giá
    public function getDiscountProducts($limit = 10) {
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE discount_percent > 0 
                  ORDER BY discount_percent DESC, created_at DESC 
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy tất cả thương hiệu
    public function getAllBrands() {
        $query = "SELECT DISTINCT brand FROM " . $this->table_name . " 
                  WHERE brand IS NOT NULL AND brand != '' 
                  ORDER BY brand ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Lấy tất cả categories
    public function getAllCategories() {
        $query = "SELECT DISTINCT category FROM " . $this->table_name . " 
                  WHERE category IS NOT NULL AND category != '' 
                  ORDER BY category ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Kiểm tra sản phẩm có tồn tại không
    public function productExists($id) {
        $query = "SELECT product_id FROM " . $this->table_name . " WHERE product_id = :product_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":product_id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    // Lấy sản phẩm theo nhiều điều kiện
    public function getProductsWithFilters($filters = []) {
        $whereConditions = ["1=1"];
        $params = [];
        
        // Brand filter
        if (!empty($filters['brand'])) {
            $whereConditions[] = "brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        
        // Category filter
        if (!empty($filters['category'])) {
            $whereConditions[] = "category = :category";
            $params[':category'] = $filters['category'];
        }
        
        // Price range filter
        if (!empty($filters['min_price'])) {
            $whereConditions[] = "price >= :min_price";
            $params[':min_price'] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $whereConditions[] = "price <= :max_price";
            $params[':max_price'] = $filters['max_price'];
        }
        
        // Stock filter
        if (isset($filters['in_stock']) && $filters['in_stock']) {
            $whereConditions[] = "stock_quantity > 0";
        }
        
        // Discount filter
        if (isset($filters['on_sale']) && $filters['on_sale']) {
            $whereConditions[] = "discount_percent > 0";
        }
        
        // Search keyword
        if (!empty($filters['search'])) {
            $whereConditions[] = "(name LIKE :search OR brand LIKE :search OR model LIKE :search OR description LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Build ORDER BY
        $orderBy = "created_at DESC";
        if (!empty($filters['sort'])) {
            switch ($filters['sort']) {
                case 'price_asc':
                    $orderBy = "price ASC";
                    break;
                case 'price_desc':
                    $orderBy = "price DESC";
                    break;
                case 'name_asc':
                    $orderBy = "name ASC";
                    break;
                case 'name_desc':
                    $orderBy = "name DESC";
                    break;
                case 'newest':
                    $orderBy = "created_at DESC";
                    break;
                case 'oldest':
                    $orderBy = "created_at ASC";
                    break;
                case 'discount':
                    $orderBy = "discount_percent DESC";
                    break;
            }
        }
        
        $whereClause = implode(' AND ', $whereConditions);
        
        $query = "SELECT * FROM " . $this->table_name . " 
                  WHERE " . $whereClause . " 
                  ORDER BY " . $orderBy;
        
        // Add LIMIT if provided
        if (!empty($filters['limit'])) {
            $query .= " LIMIT :limit";
        }
        
        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        if (!empty($filters['limit'])) {
            $stmt->bindValue(':limit', (int)$filters['limit'], PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Xử lý upload ảnh và trả về URL
    public function handleImageUpload($file, $uploadDir = '../../assets/images/products/') {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Không có file được upload hoặc có lỗi xảy ra');
        }
        
        // Kiểm tra loại file
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('Chỉ chấp nhận file ảnh (JPEG, PNG, GIF, WebP)');
        }
        
        // Kiểm tra kích thước file (max 5MB)
        $maxSize = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $maxSize) {
            throw new Exception('File ảnh không được vượt quá 5MB');
        }
        
        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('product_', true) . '.' . $extension;
        $filePath = $uploadDir . $fileName;
        
        // Tạo thư mục nếu chưa tồn tại
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Di chuyển file
        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new Exception('Không thể lưu file ảnh');
        }
        
        // Trả về URL tương đối
        return str_replace('../../', '../', $filePath);
    }

    // Xóa file ảnh cũ
    public function deleteOldImage($imageUrl) {
        if (empty($imageUrl)) return;
        
        // Chuyển đổi URL thành đường dẫn file
        $filePath = str_replace('../', '../../', $imageUrl);
        
        if (file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }
    }
}
?>