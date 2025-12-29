-- =============================================
-- MozCommodities Database - MySQL/phpMyAdmin
-- Generated: 2025-12-29
-- =============================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS = 0;

-- =============================================
-- TABLE: users
-- =============================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `email_verified_at` TIMESTAMP NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` VARCHAR(100) NULL DEFAULT NULL,
    `role` ENUM('admin', 'supplier', 'customer') NOT NULL DEFAULT 'customer',
    `phone` VARCHAR(255) NULL DEFAULT NULL,
    `address` TEXT NULL DEFAULT NULL,
    `latitude` DECIMAL(10, 8) NULL DEFAULT NULL,
    `longitude` DECIMAL(11, 8) NULL DEFAULT NULL,
    `profile_photo` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: password_reset_tokens
-- =============================================
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(255) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: sessions
-- =============================================
DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
    `id` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `ip_address` VARCHAR(45) NULL DEFAULT NULL,
    `user_agent` TEXT NULL DEFAULT NULL,
    `payload` LONGTEXT NOT NULL,
    `last_activity` INT NOT NULL,
    PRIMARY KEY (`id`),
    KEY `sessions_user_id_index` (`user_id`),
    KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: cache
-- =============================================
DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
    `key` VARCHAR(255) NOT NULL,
    `value` MEDIUMTEXT NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: cache_locks
-- =============================================
DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
    `key` VARCHAR(255) NOT NULL,
    `owner` VARCHAR(255) NOT NULL,
    `expiration` INT NOT NULL,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: jobs
-- =============================================
DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `queue` VARCHAR(255) NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `attempts` TINYINT UNSIGNED NOT NULL,
    `reserved_at` INT UNSIGNED NULL DEFAULT NULL,
    `available_at` INT UNSIGNED NOT NULL,
    `created_at` INT UNSIGNED NOT NULL,
    PRIMARY KEY (`id`),
    KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: job_batches
-- =============================================
DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
    `id` VARCHAR(255) NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `total_jobs` INT NOT NULL,
    `pending_jobs` INT NOT NULL,
    `failed_jobs` INT NOT NULL,
    `failed_job_ids` LONGTEXT NOT NULL,
    `options` MEDIUMTEXT NULL DEFAULT NULL,
    `cancelled_at` INT NULL DEFAULT NULL,
    `created_at` INT NOT NULL,
    `finished_at` INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: failed_jobs
-- =============================================
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(255) NOT NULL,
    `connection` TEXT NOT NULL,
    `queue` TEXT NOT NULL,
    `payload` LONGTEXT NOT NULL,
    `exception` LONGTEXT NOT NULL,
    `failed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: suppliers
-- =============================================
DROP TABLE IF EXISTS `suppliers`;
CREATE TABLE `suppliers` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `company_name` VARCHAR(255) NOT NULL,
    `business_license` VARCHAR(255) NULL DEFAULT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `document_number` VARCHAR(255) NULL DEFAULT NULL,
    `whatsapp` VARCHAR(255) NULL DEFAULT NULL,
    `address` TEXT NULL DEFAULT NULL,
    `latitude` VARCHAR(255) NULL DEFAULT NULL,
    `longitude` VARCHAR(255) NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `suppliers_user_id_foreign` (`user_id`),
    CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: categories
-- =============================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: products
-- =============================================
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `supplier_id` BIGINT UNSIGNED NOT NULL,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) NOT NULL,
    `description` TEXT NULL DEFAULT NULL,
    `price_per_kg` DECIMAL(10, 2) NOT NULL,
    `unit` VARCHAR(255) NOT NULL DEFAULT 'kg',
    `image` VARCHAR(255) NULL DEFAULT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `stock_kg` INT NOT NULL DEFAULT 0,
    `cost_price` DECIMAL(10, 2) NULL DEFAULT NULL,
    `platform_margin` DECIMAL(5, 2) NOT NULL DEFAULT 0,
    `approval_status` ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    `rejection_reason` TEXT NULL DEFAULT NULL,
    `is_company_product` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `products_slug_unique` (`slug`),
    KEY `products_supplier_id_foreign` (`supplier_id`),
    KEY `products_category_id_foreign` (`category_id`),
    CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
    CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: stocks
-- =============================================
DROP TABLE IF EXISTS `stocks`;
CREATE TABLE `stocks` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` DECIMAL(10, 2) NOT NULL,
    `unit` VARCHAR(255) NOT NULL DEFAULT 'kg',
    `expiry_date` DATE NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `stocks_product_id_foreign` (`product_id`),
    CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: quote_requests
-- =============================================
DROP TABLE IF EXISTS `quote_requests`;
CREATE TABLE `quote_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL,
    `company_name` VARCHAR(255) NULL DEFAULT NULL,
    `product_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `message` TEXT NOT NULL,
    `quantity` DECIMAL(10, 2) NULL DEFAULT NULL,
    `status` ENUM('pending', 'responded', 'closed') NOT NULL DEFAULT 'pending',
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `quote_requests_product_id_foreign` (`product_id`),
    CONSTRAINT `quote_requests_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: announcements
-- =============================================
DROP TABLE IF EXISTS `announcements`;
CREATE TABLE `announcements` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `message` TEXT NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `order` INT NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: orders
-- =============================================
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_number` VARCHAR(255) NOT NULL,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `customer_name` VARCHAR(255) NOT NULL,
    `customer_email` VARCHAR(255) NOT NULL,
    `customer_phone` VARCHAR(255) NOT NULL,
    `customer_address` TEXT NULL DEFAULT NULL,
    `subtotal` DECIMAL(12, 2) NOT NULL,
    `total` DECIMAL(12, 2) NOT NULL,
    `payment_method` ENUM('mpesa', 'bank_transfer') NOT NULL DEFAULT 'mpesa',
    `payment_status` ENUM('pending', 'paid', 'failed') NOT NULL DEFAULT 'pending',
    `status` ENUM('pending', 'processing', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `payment_proof` TEXT NULL DEFAULT NULL,
    `notes` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `orders_order_number_unique` (`order_number`),
    KEY `orders_user_id_foreign` (`user_id`),
    CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: order_items
-- =============================================
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `price` DECIMAL(10, 2) NOT NULL,
    `quantity` INT NOT NULL,
    `subtotal` DECIMAL(12, 2) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `order_items_order_id_foreign` (`order_id`),
    KEY `order_items_product_id_foreign` (`product_id`),
    CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
    CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: settings
-- =============================================
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `key` VARCHAR(255) NOT NULL,
    `value` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: cart_items
-- =============================================
DROP TABLE IF EXISTS `cart_items`;
CREATE TABLE `cart_items` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` BIGINT UNSIGNED NULL DEFAULT NULL,
    `session_id` VARCHAR(255) NULL DEFAULT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT NOT NULL DEFAULT 1,
    `price_per_kg` DECIMAL(10, 2) NOT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `cart_items_user_id_product_id_index` (`user_id`, `product_id`),
    KEY `cart_items_session_id_index` (`session_id`),
    KEY `cart_items_product_id_foreign` (`product_id`),
    CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: product_requests
-- =============================================
DROP TABLE IF EXISTS `product_requests`;
CREATE TABLE `product_requests` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `phone` VARCHAR(255) NOT NULL,
    `product_name` VARCHAR(255) NOT NULL,
    `description` TEXT NOT NULL,
    `quantity_kg` INT NULL DEFAULT NULL,
    `status` ENUM('pending', 'processing', 'quoted', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    `admin_notes` TEXT NULL DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- TABLE: migrations
-- =============================================
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `migration` VARCHAR(255) NOT NULL,
    `batch` INT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =============================================
-- CLEAR EXISTING DATA
-- =============================================
DELETE FROM `suppliers`;
DELETE FROM `users`;
DELETE FROM `categories`;
DELETE FROM `settings`;
DELETE FROM `migrations`;

-- Reset auto increment
ALTER TABLE `users` AUTO_INCREMENT = 1;
ALTER TABLE `suppliers` AUTO_INCREMENT = 1;
ALTER TABLE `categories` AUTO_INCREMENT = 1;
ALTER TABLE `settings` AUTO_INCREMENT = 1;
ALTER TABLE `migrations` AUTO_INCREMENT = 1;

-- =============================================
-- DEFAULT DATA
-- =============================================

-- Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Cereais', 'cereais', 'Milho, trigo, arroz e outros cereais', 1, NOW(), NOW()),
(2, 'Leguminosas', 'leguminosas', 'Feijao, lentilhas, grao-de-bico e outras leguminosas', 1, NOW(), NOW()),
(3, 'Oleaginosas', 'oleaginosas', 'Soja, girassol, amendoim e outras oleaginosas', 1, NOW(), NOW()),
(4, 'Tuberculos', 'tuberculos', 'Mandioca, batata, batata-doce e outros tuberculos', 1, NOW(), NOW()),
(5, 'Frutas', 'frutas', 'Frutas frescas e secas', 1, NOW(), NOW()),
(6, 'Horticolas', 'horticolas', 'Legumes e verduras frescas', 1, NOW(), NOW());

-- Users (password: password)
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NOW(), NOW(), NOW()),
(2, 'Cliente Teste', 'cliente@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'customer', NOW(), NOW(), NOW()),
(3, 'Fornecedor Teste', 'fornecedor@gmail.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'supplier', NOW(), NOW(), NOW());

-- Supplier profile for fornecedor@gmail.com
INSERT INTO `suppliers` (`id`, `user_id`, `company_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'Fornecedor Teste Lda', 'Fornecedor de produtos agricolas de qualidade', 'approved', NOW(), NOW());

-- Settings
INSERT INTO `settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('site_name', 'MozCommodities', NOW(), NOW()),
('site_description', 'Marketplace de produtos agricolas de Mocambique', NOW(), NOW()),
('contact_email', 'info@mozcommodities.co.mz', NOW(), NOW()),
('contact_phone', '+258 84 123 4567', NOW(), NOW()),
('mpesa_number', '84 123 4567', NOW(), NOW()),
('bank_name', 'Millennium BIM', NOW(), NOW()),
('bank_account', '000123456789', NOW(), NOW()),
('bank_nib', '0001 0000 0012 3456 7891 0', NOW(), NOW()),
('about_hero_title', 'Conectando o Campo a Mesa', NOW(), NOW()),
('about_hero_subtitle', 'Somos uma plataforma inovadora que conecta produtores agricolas mocambicanos a compradores em todo o pais.', NOW(), NOW()),
('about_stats_farmers', '500+', NOW(), NOW()),
('about_stats_products', '1000+', NOW(), NOW()),
('about_stats_deliveries', '10000+', NOW(), NOW()),
('about_mission_title', 'Nossa Missao', NOW(), NOW()),
('about_mission_text', 'Facilitar o acesso a produtos agricolas de qualidade, promovendo o desenvolvimento sustentavel do sector agricola mocambicano.', NOW(), NOW()),
('about_vision_title', 'Nossa Visao', NOW(), NOW()),
('about_vision_text', 'Ser a principal plataforma de comercializacao de produtos agricolas em Mocambique, reconhecida pela qualidade e confiabilidade.', NOW(), NOW()),
('about_values_title', 'Nossos Valores', NOW(), NOW()),
('about_values_list', 'Qualidade,Transparencia,Sustentabilidade,Inovacao,Compromisso', NOW(), NOW()),
('about_team_title', 'Nossa Equipa', NOW(), NOW()),
('about_team_text', 'Uma equipa dedicada de profissionais comprometidos com o sucesso dos nossos parceiros e clientes.', NOW(), NOW());

-- Migrations
INSERT INTO `migrations` (`migration`, `batch`) VALUES
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2025_11_05_111935_add_role_to_users_table', 1),
('2025_11_05_111939_create_suppliers_table', 1),
('2025_11_05_111942_create_categories_table', 1),
('2025_11_05_111942_create_products_table', 1),
('2025_11_05_111942_create_stocks_table', 1),
('2025_11_05_112100_create_quote_requests_table', 1),
('2025_11_05_195439_create_announcements_table', 2),
('2025_11_05_195853_create_orders_table', 3),
('2025_11_05_195854_create_order_items_table', 3),
('2025_11_05_201144_create_settings_table', 4),
('2025_11_05_202250_create_cart_items_table', 5),
('2025_11_05_203056_add_fields_to_products_and_suppliers_tables', 6),
('2025_11_05_205849_create_product_requests_table', 7),
('2025_11_05_214500_add_location_fields_to_users_table', 8),
('2025_12_29_202024_add_profile_photo_to_users_table', 8),
('2025_12_29_212827_add_about_page_settings', 9);

COMMIT;
