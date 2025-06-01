-- phpMyAdmin SQL Dump
-- Cơ sở dữ liệu Mobile Store được cải tiến
-- Phiên bản cải tiến để phù hợp với trang index2.html

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mobie_store_web`
--

-- --------------------------------------------------------

--
-- Bảng danh mục sản phẩm (Categories)
--
CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `category_icon` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dữ liệu cho bảng `categories`
--
INSERT INTO `categories` (`category_id`, `category_name`, `category_icon`, `display_order`, `is_active`) VALUES
(1, 'iPhone', 'assets/images/icon_product/iphone.jpg', 1, 1),
(2, 'iPad', 'assets/images/icon_product/ipad.jpg', 2, 1),
(3, 'Apple Watch', 'assets/images/icon_product/apple_watch.jpg', 3, 1),
(4, 'Samsung', 'assets/images/icon_product/samsung.jpg', 4, 1),
(5, 'Xiaomi', 'assets/images/icon_product/xiaomi.jpg', 5, 1),
(6, 'Realme', 'assets/images/icon_product/realme.jpg', 6, 1),
(7, 'Vivo', 'assets/images/icon_product/vivo.jpg', 7, 1),
(8, 'OPPO', 'assets/images/icon_product/oppo.jpg', 8, 1),
(9, 'Phụ kiện', 'assets/images/icon_product/accessory2.jpg', 9, 1);

-- --------------------------------------------------------

--
-- Bảng sản phẩm được cải tiến
--
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) NOT NULL,
  `model` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `old_price` decimal(12,2) DEFAULT NULL,
  `discount_percent` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image_url` varchar(255) DEFAULT NULL,
  `gallery_images` text DEFAULT NULL,
  `is_hot` tinyint(1) DEFAULT 0,
  `is_new` tinyint(1) DEFAULT 0,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `rating_average` decimal(3,2) DEFAULT 0,
  `rating_count` int(11) DEFAULT 0,
  `view_count` int(11) DEFAULT 0,
  `sold_count` int(11) DEFAULT 0,
  `status` enum('active','inactive','out_of_stock') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dữ liệu mẫu cho bảng `products`
--
INSERT INTO `products` (`product_id`, `name`, `brand`, `model`, `category_id`, `price`, `old_price`, `discount_percent`, `description`, `specifications`, `stock_quantity`, `image_url`, `gallery_images`, `is_hot`, `is_new`, `is_bestseller`, `rating_average`, `rating_count`, `view_count`, `sold_count`, `status`) VALUES
(1, 'Samsung Galaxy S24 Ultra 256GB', 'Samsung', 'SM-S928B', 4, 32990000, 42000000, 21, 'Flagship Samsung với camera 200MP và S Pen tích hợp', '256GB, 5G, Camera 200MP, S Pen, Snapdragon 8 Gen 3', 50, 'assets/images/product/samsung.jpg', NULL, 1, 0, 0, 4.8, 128, 1250, 89, 'active'),
(2, 'iPhone 15 Pro Max 256GB', 'Apple', 'A3108', 1, 29990000, 34990000, 14, 'iPhone cao cấp với chip A17 Pro và khung Titanium', '256GB, Titanium, A17 Pro, Camera 48MP, 5G', 30, 'assets/images/product/iphone.png', NULL, 0, 1, 0, 4.9, 89, 980, 67, 'active'),
(3, 'Xiaomi 14 Ultra 512GB', 'Xiaomi', '23127PN0CC', 5, 24990000, 29990000, 17, 'Camera Leica và chip Snapdragon 8 Gen 3', '512GB, Leica Camera, Snapdragon 8 Gen 3, AMOLED 120Hz', 25, 'assets/images/product/xiaomi.jpg', NULL, 0, 0, 0, 4.5, 45, 670, 34, 'active'),
(4, 'OPPO Find X7 Pro 256GB', 'OPPO', 'CPH2609', 8, 18990000, 22990000, 17, 'Camera Hasselblad và màn hình 120Hz', '256GB, Hasselblad Camera, 120Hz AMOLED, MediaTek 9300', 40, 'assets/images/product/oppo.jpg', NULL, 0, 0, 0, 4.3, 67, 450, 28, 'active'),
(5, 'iPad Pro M4 11inch 256GB', 'Apple', 'MVVD3ZA/A', 2, 26990000, 29990000, 10, 'iPad Pro với chip M4 và màn hình OLED', 'M4 Chip, 11inch OLED, Apple Pencil Pro, 256GB', 20, 'assets/images/product/ipad.jpg', NULL, 0, 1, 0, 4.7, 156, 890, 78, 'active'),
(6, 'Vivo X100 Pro 512GB', 'Vivo', 'V2309A', 7, 21990000, 25990000, 15, 'Camera ZEISS và pin siêu trâu với sạc nhanh 100W', '512GB, ZEISS Camera, MediaTek 9300, 100W Fast Charge', 35, 'assets/images/product/vivo.jpg', NULL, 1, 0, 0, 4.4, 34, 560, 23, 'active'),
(7, 'Apple AirPods Pro 2nd Gen USB-C', 'Apple', 'MTJV3ZA/A', 9, 5990000, 6990000, 14, 'Tai nghe không dây cao cấp với ANC và chip H2', 'ANC, USB-C, H2 Chip, Spatial Audio, MagSafe', 100, 'assets/images/product/airpods.jpg', NULL, 0, 0, 1, 4.8, 234, 1890, 156, 'active'),
(8, 'Sạc MacBook Pro 140W USB-C', 'Apple', 'MLYU3ZA/A', 9, 2290000, 2590000, 12, 'Sạc nhanh chính hãng Apple cho MacBook Pro 16inch', '140W, USB-C, Fast Charge, Tương thích MacBook Pro', 75, 'assets/images/product/charger.jpg', NULL, 0, 0, 0, 4.2, 89, 340, 67, 'active'),
(9, 'Ốp lưng iPhone 15 Pro Max MagSafe', 'Apple', 'MT233ZM/A', 9, 890000, 1190000, 25, 'Ốp lưng da chính hãng với công nghệ MagSafe', 'MagSafe, Leather, Drop Protection, Chính hãng Apple', 200, 'assets/images/product/case.jpg', NULL, 0, 0, 0, 4.1, 156, 680, 234, 'active'),
(10, 'Cáp sạc nhanh USB-C to Lightning 2m', 'Apple', 'MQGH2ZM/A', 9, 490000, 690000, 29, 'Cáp sạc nhanh chính hãng Apple độ dài 2m', '2m, PD 30W, Braided Cable, USB-C to Lightning', 150, 'assets/images/product/cable.jpg', NULL, 0, 1, 0, 4.0, 78, 290, 123, 'active');

-- --------------------------------------------------------

--
-- Bảng thuộc tính sản phẩm
--
CREATE TABLE `product_attributes` (
  `attribute_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `attribute_name` varchar(100) NOT NULL,
  `attribute_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dữ liệu mẫu cho bảng `product_attributes`
--
INSERT INTO `product_attributes` (`attribute_id`, `product_id`, `attribute_name`, `attribute_value`) VALUES
(1, 1, 'Dung lượng', '256GB'),
(2, 1, 'Mạng', '5G'),
(3, 1, 'Camera chính', '200MP'),
(4, 2, 'Dung lượng', '256GB'),
(5, 2, 'Chất liệu', 'Titanium'),
(6, 2, 'Chip', 'A17 Pro'),
(7, 7, 'Tính năng', 'ANC'),
(8, 7, 'Cổng kết nối', 'USB-C'),
(9, 7, 'Chip âm thanh', 'H2 Chip');

-- --------------------------------------------------------

--
-- Bảng banner/slider
--
CREATE TABLE `banners` (
  `banner_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) NOT NULL,
  `link_url` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dữ liệu cho bảng `banners`
--
INSERT INTO `banners` (`banner_id`, `title`, `description`, `image_url`, `link_url`, `display_order`, `is_active`) VALUES
(1, 'Khuyến mãi lớn', 'Giảm giá tới 50% cho tất cả sản phẩm', 'assets/images/banner/slider1.jpg', '#', 1, 1),
(2, 'Sản phẩm mới', 'iPhone 15 series chính thức ra mắt', 'assets/images/banner/slider2.jpg', '#', 2, 1),
(3, 'Giá tốt nhất', 'Cam kết giá tốt nhất thị trường', 'assets/images/banner/slider3.jpg', '#', 3, 1),
(4, 'Ưu đãi đặc biệt', 'Mua 1 tặng 1 phụ kiện', 'assets/images/banner/slider4.jpg', '#', 4, 1);

-- --------------------------------------------------------

--
-- Bảng giỏ hàng
--
CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Bảng yêu thích
--
CREATE TABLE `wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Bảng cấu hình website
--
CREATE TABLE `site_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dữ liệu cho bảng `site_settings`
--
INSERT INTO `site_settings` (`setting_id`, `setting_key`, `setting_value`, `description`) VALUES
(1, 'site_name', 'Mobile Store', 'Tên website'),
(2, 'hotline_sales', '0123456789', 'Hotline bán hàng'),
(3, 'hotline_support', '0123456789', 'Hotline hỗ trợ kỹ thuật'),
(4, 'company_address', '278 đường Lam Sơn, Thành phố Vĩnh Yên, Vĩnh Phúc', 'Địa chỉ công ty'),
(5, 'company_email', 'lich04d3@gmail.com', 'Email liên hệ'),
(6, 'company_phone', '+(888) 123456789', 'Số điện thoại công ty');

-- --------------------------------------------------------

--
-- Bảng users (giữ nguyên từ database cũ)
--
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('customer','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dữ liệu cho bảng `users`
--
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone_number`, `address`, `role`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@mobilestore.com', '$2y$10$n4BOrl1wSZgudzowuhKpieb1h1hjtVYapj5Q6kIj7QxXioSq1Q/FS', 'Administrator', NULL, NULL, 'admin', '2025-05-31 14:37:23', '2025-05-31 14:37:23'),
(2, 'Dlich', 'dl2@gmail.com', '$2y$10$AfwHA/DhZKnmx3Zh7Xri9uxRHvY61zVzWmQXQOn.GHJZxyxKtx.aG', 'Lưu Đức Lịch', NULL, NULL, 'customer', '2025-05-31 15:06:09', '2025-05-31 15:06:09');

-- --------------------------------------------------------

--
-- Bảng orders (cải tiến)
--
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','confirmed','shipped','delivered','cancelled') NOT NULL DEFAULT 'pending',
  `total_price` decimal(12,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Bảng order_details (giữ nguyên)
--
CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Bảng payments (cải tiến)
--
CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `payment_method` enum('credit_card','bank_transfer','paypal','cod','momo','zalopay') NOT NULL,
  `payment_status` enum('pending','completed','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_date` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Bảng reviews (cải tiến)
--
CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `is_verified_purchase` tinyint(1) DEFAULT 0,
  `is_approved` tinyint(1) DEFAULT 1,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Bảng admin_logs (giữ nguyên)
--
CREATE TABLE `admin_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Các chỉ mục cho các bảng
--

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `brand` (`brand`),
  ADD KEY `status` (`status`),
  ADD KEY `is_hot` (`is_hot`),
  ADD KEY `is_new` (`is_new`),
  ADD KEY `is_bestseller` (`is_bestseller`);

--
-- Chỉ mục cho bảng `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`attribute_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`banner_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `site_settings`
--
ALTER TABLE `site_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- Chỉ mục cho các bảng khác (giữ nguyên)
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `order_id` (`order_id`);

ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- AUTO_INCREMENT cho các bảng
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

ALTER TABLE `product_attributes`
  MODIFY `attribute_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `banners`
  MODIFY `banner_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `site_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `admin_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE;

ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;