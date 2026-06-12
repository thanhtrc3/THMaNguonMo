-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for my_store
CREATE DATABASE IF NOT EXISTS `my_store` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `my_store`;

-- Dumping structure for table my_store.account
CREATE TABLE IF NOT EXISTS `account` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text,
  `remember_token` varchar(255) DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expire` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table my_store.account: ~4 rows (approximately)
INSERT INTO `account` (`id`, `username`, `fullname`, `password`, `role`, `phone`, `email`, `address`, `remember_token`, `reset_token`, `reset_token_expire`) VALUES
	(1, 'admin', 'admin', '$2y$10$4OxbdDUDFjtbbXHmZ13sZeG680EGjenh.QA//KvhVCEVdVTUsrKsi', 'admin', '0962836417', 'thanhtrc3@gmail.com', '{"province":"Tỉnh Hà Nam","district":"Huyện Kim Bảng","ward":"Xã Tượng Lĩnh","address_detail":"255"}', NULL, NULL, NULL),
	(2, 'user', 'user', '$2y$10$lIZo9w9omRaMHf0k55I0y.RqDDVZsO8RmflOsGCBJWb/wXebm18Oq', 'user', '0962836417', 'thanhtrc3@gmail.com', '{"province":"Tỉnh Hà Nam","district":"Huyện Bình Lục","ward":"Xã Đồn Xá","address_detail":"136"}', NULL, 'fa2a19037abbffa82a083efcba9647c8', '2026-06-05 10:34:53'),
	(3, 'thanhhien', 'thanh hien', '$2y$10$/SxL9hiNO1/Y8KYsnvUTRuPSZwkGAesMSVYUvQoY.dmPHBMDiOJ66', 'user', '', '', '', NULL, NULL, NULL),
	(4, 'asdf', 'dd', '$2y$10$Z/bmW32gLDSnCcvCEEty6eps6vVj2TGySgUrpLB2FBgHGwdLMN5rW', 'user', '0962836417', 'thanhtrc3@gmail.com', '', NULL, 'fa2a19037abbffa82a083efcba9647c8', '2026-06-05 10:34:53');

-- Dumping structure for table my_store.category
CREATE TABLE IF NOT EXISTS `category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table my_store.category: ~8 rows (approximately)
INSERT INTO `category` (`id`, `name`, `description`) VALUES
	(1, 'Điện thoại và Máy tính bảng', ''),
	(2, 'Laptop và Máy tính bộ', ''),
	(3, 'Linh kiện máy tính', ''),
	(4, 'Màn hình máy tính', ''),
	(5, 'Thiết bị ngoại vi và  Phụ kiện', ''),
	(6, 'Thiết bị mạng và  An ninh', ''),
	(7, 'Thiết bị văn phòng', ''),
	(8, 'Âm thanh và Giải trí', '');

-- Dumping structure for table my_store.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` text NOT NULL,
  `notes` text,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `payment_method` varchar(50) NOT NULL DEFAULT 'COD',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `shipping_method` varchar(50) NOT NULL DEFAULT 'standard',
  `shipping_fee` int NOT NULL DEFAULT '0',
  `discount_code` varchar(50) DEFAULT NULL,
  `discount_amount` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table my_store.orders: ~17 rows (approximately)
INSERT INTO `orders` (`id`, `name`, `phone`, `email`, `address`, `notes`, `total_amount`, `payment_method`, `status`, `shipping_method`, `shipping_fee`, `discount_code`, `discount_amount`, `created_at`) VALUES
	(1, 'nguyen thanh hien', '123456798', NULL, 'ádasda', NULL, 0.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 18:47:19'),
	(2, 'nguyen thanh hien', '1241234', NULL, '12312', NULL, 0.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 19:05:15'),
	(3, 'nguyen thanh hien', 'ád', NULL, 'á', NULL, 0.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 19:13:49'),
	(4, 'á', 'á', NULL, 'ád', NULL, 0.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 19:21:17'),
	(5, 'ád', 'á', NULL, 'ád', NULL, 0.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 19:21:53'),
	(6, 'nguyen thanh hien', '1241234', NULL, 'ghf', NULL, 0.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 20:00:56'),
	(7, 'nguyen thanh hien', '0962836417', NULL, 'qưe', NULL, 470211274.00, 'BANK', 'pending', 'standard', 0, NULL, 0, '2026-05-28 20:23:26'),
	(8, 'nguyen thanh hien', '0962836417', NULL, 'ád', NULL, 34990000.00, 'BANK', 'pending', 'standard', 0, NULL, 0, '2026-05-28 20:23:54'),
	(9, 'nguyen thanh hien', '0962836417', NULL, 'f', NULL, 34990000.00, 'BANK', 'pending', 'standard', 0, NULL, 0, '2026-05-28 20:25:51'),
	(10, 'nguyen thanh hien', '0962836417', NULL, '423', NULL, 29990000.00, 'COD', 'pending', 'standard', 0, NULL, 0, '2026-05-28 20:30:08'),
	(11, 'nguyen thanh hien', '0962836417', 'thanhtrc@gmail.com', '231, Xã Đồng Trung, Huyện Thanh Thuỷ, Tỉnh Phú Thọ', '312', 60015000.00, 'BANK', 'pending', 'express', 35000, '', 0, '2026-05-28 20:36:06'),
	(12, 'nguyen thanh hien', '0962836417', 'thanhtrc@gmail.com', '231, Xã Đồng Trung, Huyện Thanh Thuỷ, Tỉnh Phú Thọ', 'ádádas', 35025000.00, 'BANK', 'pending', 'express', 35000, '', 0, '2026-05-28 20:41:19'),
	(13, 'nguyen thanh hien', '0962836417', 'thanhtrc@gmail.com', '231, Xã Đồng Trung, Huyện Thanh Thuỷ, Tỉnh Phú Thọ', '', 34975000.00, 'BANK', 'pending', 'express', 35000, 'SALE50K', 50000, '2026-05-28 20:42:04'),
	(14, 'user', '0962836417', 'thanhtrc3@gmail.com', '136, Xã Tượng Lĩnh, Huyện Kim Bảng, Tỉnh Hà Nam', 'ád', 29975000.00, 'BANK', 'pending', 'express', 35000, 'SALE50K', 50000, '2026-06-04 18:58:32'),
	(15, 'user', '0962836417', 'thanhtrc3@gmail.com', '136, Xã Tượng Lĩnh, Huyện Kim Bảng, Tỉnh Hà Nam', 'fdg', 80036600.00, 'BANK', 'pending', 'express', 35000, '', 0, '2026-06-04 18:59:20'),
	(16, 'admin', '0962836417', 'thanhtrc3@gmail.com', '255, Xã Tượng Lĩnh, Huyện Kim Bảng, Tỉnh Hà Nam', '', 1635032.00, 'COD', 'pending', 'express', 35000, '', 0, '2026-06-04 19:02:33'),
	(17, 'user', '0962836417', 'thanhtrc3@gmail.com', '136, Xã Đồn Xá, Huyện Bình Lục, Tỉnh Hà Nam', '', 29990000.00, 'COD', 'pending', 'standard', 0, '', 0, '2026-06-04 19:17:58');

-- Dumping structure for table my_store.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table my_store.order_details: ~34 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 3, 9, 34990000.00),
	(2, 1, 1, 12, 29990000.00),
	(3, 2, 1, 57, 29990000.00),
	(4, 2, 2, 1, 1600032.00),
	(5, 2, 3, 1, 34990000.00),
	(6, 2, 4, 1, 27490000.00),
	(7, 2, 5, 1, 11500000.00),
	(8, 2, 6, 1, 8200000.00),
	(9, 3, 3, 16, 34990000.00),
	(10, 4, 1, 16, 29990000.00),
	(11, 5, 3, 1, 34990000.00),
	(12, 6, 3, 73, 34990000.00),
	(13, 6, 1, 39, 29990000.00),
	(14, 6, 4, 1, 27490000.00),
	(15, 6, 16, 1, 21341242.00),
	(16, 7, 3, 6, 34990000.00),
	(17, 7, 1, 5, 29990000.00),
	(18, 7, 4, 2, 27490000.00),
	(19, 7, 16, 1, 21341242.00),
	(20, 7, 2, 1, 1600032.00),
	(21, 7, 5, 1, 11500000.00),
	(22, 7, 6, 1, 8200000.00),
	(23, 7, 7, 1, 6800000.00),
	(24, 7, 8, 1, 5900000.00),
	(25, 8, 3, 1, 34990000.00),
	(26, 9, 3, 1, 34990000.00),
	(27, 10, 1, 1, 29990000.00),
	(28, 11, 1, 2, 29990000.00),
	(29, 12, 3, 1, 34990000.00),
	(30, 13, 3, 1, 34990000.00),
	(31, 14, 1, 1, 29990000.00),
	(32, 15, 2, 50, 1600032.00),
	(33, 16, 2, 1, 1600032.00),
	(34, 17, 1, 1, 29990000.00);

-- Dumping structure for table my_store.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `category_id` (`category_id`),
  CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table my_store.product: ~16 rows (approximately)
INSERT INTO `product` (`id`, `name`, `description`, `price`, `image`, `category_id`) VALUES
	(1, 'iPhone 15 Pro Max 256GBgg', 'cái gì đó\r\ng', 29990000.00, 'https://encrypted-tbn1.gstatic.com/shopping?q=tbn:ANd9GcTMF0XJkdooDLxG1PH5w2AttiJwud0CyKlEUycuZ89pHNFmxTFkl5vwM6hbzoRJ6HDZPGS9kvwcOIyRFHrpIddqdDyMEVQcq0oHLivAuLc7m9ItK_ixYEduTPIv7to7mKGEy7cpdQ&amp;amp;amp;amp;amp;usqp=CAc', 1),
	(2, 'iPad Air M2 11-inch Wi-Fi', 'iPad Air M2 11-inch Wi-Fi', 16000320.00, 'https://cdn11.dienmaycholon.vn/filewebdmclnew/DMCL21/Picture//Apro/Apro_product_36223/ipad-air-m3-11-inch-wifi-128gb-main-36223.png', 1),
	(3, 'Laptop Gaming ASUS ROG Strix G16', '', 34990000.00, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_1__1_96_1_1_1_1.png', 2),
	(4, 'MacBook Air M3 13-inch 8GB/256GB', '', 27490000.00, 'uploads/1780023309_Capture.PNG', 2),
	(5, 'Card màn hình ASUS Dual RTX 4060 Ti', '', 11500000.00, NULL, 3),
	(6, 'CPU Intel Core i5-14600K', '', 8200000.00, NULL, 3),
	(7, 'Màn hình Gaming LG UltraGear 27GR75Q 2K 165Hz', '', 6800000.00, NULL, 4),
	(8, 'Màn hình văn phòng Dell UltraSharp U2424H', '', 5900000.00, NULL, 4),
	(9, 'Bàn phím cơ Logitech G Pro X TKL', '', 3800000.00, NULL, 5),
	(10, 'Chuột Gaming Razer DeathAdder V3 Pro', '', 3200000.00, NULL, 5),
	(11, 'Router Wi-Fi 6 ASUS RT-AX53U', '', 1350000.00, NULL, 6),
	(12, 'Camera IP Wifi TP-Link Tapo C211 2K', '', 650000.00, NULL, 6),
	(13, 'Máy in Laser Canon LBP241d (In 2 mặt)', '', 4500000.00, NULL, 7),
	(14, 'Ghế công thái học Sihoo M57', '', 3200000.00, NULL, 7),
	(15, 'Tai nghe True Wireless Sony WF-1000XM5', '', 5490000.00, NULL, 8),
	(16, 'ádasda124232asda', 'ádasádas', 21341242.00, '', 8);

-- Dumping structure for table my_store.product_images
CREATE TABLE IF NOT EXISTS `product_images` (
  `id` int NOT NULL AUTO_INCREMENT,
  `product_id` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb3;

-- Dumping data for table my_store.product_images: ~11 rows (approximately)
INSERT INTO `product_images` (`id`, `product_id`, `image_path`) VALUES
	(1, 1, 'https://24hstore.vn/images/products/2025/09/10/large/iphone-17-pro-max-1.jpg'),
	(2, 1, 'https://24hstore.vn/images/products/2025/09/10/large/iphone-17-pro-max-2.jpg'),
	(3, 1, 'https://24hstore.vn/images/products/2025/09/10/large/iphone-17-pro-max-3.jpg'),
	(4, 1, 'https://24hstore.vn/images/products/2025/09/10/large/iphone-17-pro-max-4.jpg'),
	(5, 2, 'https://cdn2.fptshop.com.vn/unsafe/384x0/filters:format(webp):quality(75)/2024_5_10_638509455969100682_iPad%20Air%20M2%2011%20Wi-Fi%20Space%20Gray-2.jpg'),
	(6, 2, 'https://cdn2.fptshop.com.vn/unsafe/384x0/filters:format(webp):quality(75)/2024_5_10_638509455963769880_iPad%20Air%20M2%20Wi-Fi-3.jpg'),
	(7, 2, 'https://cdn2.fptshop.com.vn/unsafe/384x0/filters:format(webp):quality(75)/2024_5_10_638509455966741095_iPad%20Air%20M2-4.jpg'),
	(8, 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop_asus_rog_strix_g16_g614ju-n3135w_-_1.png'),
	(9, 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop_asus_rog_strix_g16_g614ju-n3135w_-_3.png'),
	(10, 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/l/a/laptop_asus_rog_strix_g16_g614ju-n3135w_-_2.png'),
	(11, 3, 'https://cdn2.cellphones.com.vn/insecure/rs:fill:0:358/q:90/plain/https://cellphones.com.vn/media/catalog/product/t/e/text_ng_n_3_53_1_1_1_1.png');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
