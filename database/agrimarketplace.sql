-- MySQL dump 10.13  Distrib 9.4.0, for macos15.4 (arm64)
--
-- Host: 127.0.0.1    Database: agrimarketplace
-- ------------------------------------------------------
-- Server version	9.4.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `announcements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `announcements`
--

LOCK TABLES `announcements` WRITE;
/*!40000 ALTER TABLE `announcements` DISABLE KEYS */;
/*!40000 ALTER TABLE `announcements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cart_items`
--

DROP TABLE IF EXISTS `cart_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cart_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price_per_kg` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cart_items_user_id_product_id_index` (`user_id`,`product_id`),
  KEY `cart_items_session_id_index` (`session_id`),
  KEY `cart_items_product_id_foreign` (`product_id`),
  CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cart_items`
--

LOCK TABLES `cart_items` WRITE;
/*!40000 ALTER TABLE `cart_items` DISABLE KEYS */;
INSERT INTO `cart_items` VALUES (19,2,NULL,4,1,40.00,'2026-06-01 20:36:14','2026-06-01 20:36:14');
/*!40000 ALTER TABLE `cart_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Cereais','cereais','Milho, trigo, arroz e outros cereais',1,'2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `categories` VALUES (2,'Leguminosas','leguminosas','Feijao, lentilhas, grao-de-bico e outras leguminosas',1,'2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `categories` VALUES (3,'Oleaginosas','oleaginosas','Soja, girassol, amendoim e outras oleaginosas',1,'2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `categories` VALUES (4,'Tuberculos','tuberculos','Mandioca, batata, batata-doce e outros tuberculos',1,'2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `categories` VALUES (5,'Frutas','frutas','Frutas frescas e secas',1,'2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `categories` VALUES (6,'Horticolas','horticolas','Legumes e verduras frescas',1,'2025-12-29 23:19:27','2025-12-29 23:19:27');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1);
INSERT INTO `migrations` VALUES (2,'0001_01_01_000001_create_cache_table',1);
INSERT INTO `migrations` VALUES (3,'0001_01_01_000002_create_jobs_table',1);
INSERT INTO `migrations` VALUES (4,'2025_11_05_111935_add_role_to_users_table',1);
INSERT INTO `migrations` VALUES (5,'2025_11_05_111939_create_suppliers_table',1);
INSERT INTO `migrations` VALUES (6,'2025_11_05_111942_create_categories_table',1);
INSERT INTO `migrations` VALUES (7,'2025_11_05_111942_create_products_table',1);
INSERT INTO `migrations` VALUES (8,'2025_11_05_111942_create_stocks_table',1);
INSERT INTO `migrations` VALUES (9,'2025_11_05_112100_create_quote_requests_table',1);
INSERT INTO `migrations` VALUES (10,'2025_11_05_195439_create_announcements_table',2);
INSERT INTO `migrations` VALUES (11,'2025_11_05_195853_create_orders_table',3);
INSERT INTO `migrations` VALUES (12,'2025_11_05_195854_create_order_items_table',3);
INSERT INTO `migrations` VALUES (13,'2025_11_05_201144_create_settings_table',4);
INSERT INTO `migrations` VALUES (14,'2025_11_05_202250_create_cart_items_table',5);
INSERT INTO `migrations` VALUES (15,'2025_11_05_203056_add_fields_to_products_and_suppliers_tables',6);
INSERT INTO `migrations` VALUES (16,'2025_11_05_205849_create_product_requests_table',7);
INSERT INTO `migrations` VALUES (17,'2025_11_05_214500_add_location_fields_to_users_table',8);
INSERT INTO `migrations` VALUES (18,'2025_12_29_202024_add_profile_photo_to_users_table',8);
INSERT INTO `migrations` VALUES (19,'2025_12_29_212827_add_about_page_settings',9);
INSERT INTO `migrations` VALUES (20,'2025_12_29_220651_add_payment_transaction_fields_to_orders_table',10);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (1,1,3,'Gergelim',280.00,5,1400.00,'2026-06-01 14:23:46','2026-06-01 14:23:46');
INSERT INTO `order_items` VALUES (2,2,3,'Gergelim',280.00,5,1400.00,'2026-06-01 14:29:00','2026-06-01 14:29:00');
INSERT INTO `order_items` VALUES (3,3,3,'Gergelim',280.00,5,1400.00,'2026-06-01 14:30:12','2026-06-01 14:30:12');
INSERT INTO `order_items` VALUES (4,4,3,'Gergelim',280.00,4,1120.00,'2026-06-01 16:51:11','2026-06-01 16:51:11');
INSERT INTO `order_items` VALUES (5,4,4,'Milho amarelo',40.00,11,440.00,'2026-06-01 16:51:11','2026-06-01 16:51:11');
INSERT INTO `order_items` VALUES (6,5,6,'Feijao manteiga',110.00,5,550.00,'2026-06-01 17:06:35','2026-06-01 17:06:35');
INSERT INTO `order_items` VALUES (7,6,4,'Milho amarelo',40.00,3,120.00,'2026-06-01 18:46:30','2026-06-01 18:46:30');
INSERT INTO `order_items` VALUES (8,6,6,'Feijao manteiga',110.00,5,550.00,'2026-06-01 18:46:30','2026-06-01 18:46:30');
INSERT INTO `order_items` VALUES (9,7,4,'Milho amarelo',40.00,4,160.00,'2026-06-01 18:51:53','2026-06-01 18:51:53');
INSERT INTO `order_items` VALUES (10,8,3,'Gergelim',280.00,6,1680.00,'2026-06-01 19:00:22','2026-06-01 19:00:22');
INSERT INTO `order_items` VALUES (11,9,3,'Gergelim',280.00,6,1680.00,'2026-06-01 19:07:58','2026-06-01 19:07:58');
INSERT INTO `order_items` VALUES (12,10,3,'Gergelim',280.00,6,1680.00,'2026-06-01 19:21:25','2026-06-01 19:21:25');
INSERT INTO `order_items` VALUES (13,11,3,'Gergelim',280.00,6,1680.00,'2026-06-01 19:24:27','2026-06-01 19:24:27');
INSERT INTO `order_items` VALUES (14,12,4,'Milho amarelo',40.00,1,40.00,'2026-06-01 19:31:57','2026-06-01 19:31:57');
INSERT INTO `order_items` VALUES (15,13,4,'Milho amarelo',40.00,1,40.00,'2026-06-01 20:28:31','2026-06-01 20:28:31');
INSERT INTO `order_items` VALUES (16,13,7,'Teste 220',264.00,1,264.00,'2026-06-01 20:28:31','2026-06-01 20:28:31');
INSERT INTO `order_items` VALUES (17,14,4,'Milho amarelo',40.00,1,40.00,'2026-06-01 20:36:43','2026-06-01 20:36:43');
INSERT INTO `order_items` VALUES (18,15,4,'Milho amarelo',40.00,1,40.00,'2026-06-01 20:36:52','2026-06-01 20:36:52');
INSERT INTO `order_items` VALUES (19,16,4,'Milho amarelo',40.00,1,40.00,'2026-06-01 20:36:53','2026-06-01 20:36:53');
INSERT INTO `order_items` VALUES (20,17,4,'Milho amarelo',40.00,1,40.00,'2026-06-01 20:37:01','2026-06-01 20:37:01');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_returns`
--

DROP TABLE IF EXISTS `order_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_returns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `return_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reason` enum('defeito','quantidade_errada','produto_errado','nao_corresponde','outro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','approved','rejected','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_number` (`return_number`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`),
  KEY `status` (`status`),
  CONSTRAINT `fk_or_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_or_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_returns`
--

LOCK TABLES `order_returns` WRITE;
/*!40000 ALTER TABLE `order_returns` DISABLE KEYS */;
INSERT INTO `order_returns` VALUES (1,3,2,'DEV-DC75FE390C4E','defeito','O gergelim chegou com algumas partes danificadas e húmidas, não está em condições de utilização.','approved','Verificado, devolução aprovada para reembolso.','2026-06-01 17:10:21','2026-06-01 17:10:18','2026-06-01 17:10:21');
/*!40000 ALTER TABLE `order_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_address` text COLLATE utf8mb4_unicode_ci,
  `subtotal` decimal(12,2) NOT NULL,
  `total` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'mpesa',
  `payment_status` enum('pending','paid','failed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `status` enum('pending','processing','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `payment_proof` text COLLATE utf8mb4_unicode_ci,
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_gateway` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_response` json DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_user_id_foreign` (`user_id`),
  CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-661832F564B389A0',2,'Cliente Teste','cliente@gmail.com','+258840000000','Av. Eduardo Mondlane 123, Maputo',1400.00,1400.00,'mpesa','pending','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 14:23:46','2026-06-01 14:23:46');
INSERT INTO `orders` VALUES (2,'ORD-1D8EF2CD92076DCB',2,'Cliente Teste','cliente@gmail.com','+258840000000','Av. Eduardo Mondlane 123, Maputo',1400.00,1400.00,'mpesa','pending','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 14:29:00','2026-06-01 14:29:00');
INSERT INTO `orders` VALUES (3,'ORD-772B4617D09291CA',2,'Cliente Teste','cliente@gmail.com','+258840000000','Av. Eduardo Mondlane 123, Maputo',1400.00,1400.00,'mpesa','paid','pending',NULL,'DEMO-ORD31780331412','DEMO-ORD31780331412','mpesa','{\"demo\": true}','2026-06-01 19:09:47','','2026-06-01 14:30:12','2026-06-01 14:30:12');
INSERT INTO `orders` VALUES (4,'ORD-25BA23869D1EDF3C',2,'Cliente Teste','cliente@gmail.com','+258849285587','Bairro Central',1560.00,1560.00,'emola','pending','pending',NULL,'DEMO-EMO41780339871','DEMO-EMO41780339871','emola','{\"demo\": true}',NULL,'','2026-06-01 16:51:11','2026-06-01 16:51:11');
INSERT INTO `orders` VALUES (5,'ORD-0106B14DDE1A77A3',2,'Cliente Teste','cliente@gmail.com','+258869285587','Bairro Central',550.00,550.00,'emola','pending','cancelled',NULL,'DEMO-EMO51780340795','DEMO-EMO51780340795','emola','{\"demo\": true}',NULL,'','2026-06-01 17:06:35','2026-06-01 17:33:57');
INSERT INTO `orders` VALUES (6,'ORD-861827CBAF38A7F3',2,'Cliente Teste','cliente@gmail.com','+258869285587','Zambezia',670.00,670.00,'emola','paid','processing',NULL,'DEMO-EMO61780346790','DEMO-EMO61780346790','emola','{\"demo\": true}','2026-06-01 18:46:34','','2026-06-01 18:46:30','2026-06-01 18:46:34');
INSERT INTO `orders` VALUES (7,'ORD-5B00262C1D496CE2',2,'Cliente Teste','cliente@gmail.com','869285587','tst',160.00,160.00,'emola','paid','processing',NULL,'DEMO-EMO71780347113','DEMO-EMO71780347113','emola','{\"demo\": true}','2026-06-01 18:52:10','','2026-06-01 18:51:53','2026-06-01 18:52:10');
INSERT INTO `orders` VALUES (8,'ORD-AC9C2DA472E4E7E5',2,'Cliente Teste','cliente@gmail.com','869285587','X',1680.00,1680.00,'emola','failed','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 19:00:22','2026-06-01 19:00:22');
INSERT INTO `orders` VALUES (9,'ORD-7D11FC66723CC707',2,'Cliente Teste','cliente@gmail.com','+258869285587','XX',1680.00,1680.00,'emola','failed','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 19:07:58','2026-06-01 19:07:58');
INSERT INTO `orders` VALUES (10,'ORD-1502D0DC63A82743',2,'Cliente Teste','cliente@gmail.com','+258869285587','Teste',1680.00,1680.00,'emola','failed','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 19:21:25','2026-06-01 19:21:25');
INSERT INTO `orders` VALUES (11,'ORD-7EA2F83ABC03DD8E',2,'Cliente Teste','cliente@gmail.com','+258869285587','teste',1680.00,1680.00,'emola','pending','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 19:24:27','2026-06-01 19:24:27');
INSERT INTO `orders` VALUES (12,'ORD-308B800E59365E6B',2,'Cliente Teste','cliente@gmail.com','+258870428888','Nampula ao lado da shoprite',40.00,40.00,'emola','pending','pending',NULL,'202606012332023747463','MZC12','emola','{\"output_ResponseCode\": \"Successfully\", \"output_ResponseDesc\": \"Successfully\", \"output_TransactionID\": \"202606012332023747463\"}',NULL,'','2026-06-01 19:31:57','2026-06-01 19:32:24');
INSERT INTO `orders` VALUES (13,'ORD-C9C03203110B40F8',2,'Cliente Teste','cliente@gmail.com','+258849285587','teste',304.00,304.00,'bank_transfer','pending','pending','payment-proofs/v8yq088yDqAjmTVbynmasSYaxOErLDE1oEFquPos.jpg',NULL,NULL,NULL,NULL,NULL,'','2026-06-01 20:28:31','2026-06-01 20:28:31');
INSERT INTO `orders` VALUES (14,'ORD-4ACBD35A8995523D',2,'Cliente Teste','cliente@gmail.com','+2869285587','Nampula',40.00,40.00,'emola','failed','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 20:36:43','2026-06-01 20:36:43');
INSERT INTO `orders` VALUES (15,'ORD-D18109C50B1758B2',2,'Cliente Teste','cliente@gmail.com','+25869285587','Nampula',40.00,40.00,'emola','failed','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 20:36:52','2026-06-01 20:36:52');
INSERT INTO `orders` VALUES (16,'ORD-2CB18FE8BD67DC6C',2,'Cliente Teste','cliente@gmail.com','+25869285587','Nampula',40.00,40.00,'emola','failed','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 20:36:53','2026-06-01 20:36:53');
INSERT INTO `orders` VALUES (17,'ORD-B34C329EF7C86A25',2,'Cliente Teste','cliente@gmail.com','+258869285587','Nampula',40.00,40.00,'emola','pending','pending',NULL,NULL,NULL,NULL,NULL,NULL,'','2026-06-01 20:37:01','2026-06-01 20:37:01');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_requests`
--

DROP TABLE IF EXISTS `product_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity_kg` int DEFAULT NULL,
  `status` enum('pending','processing','quoted','completed','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_requests`
--

LOCK TABLES `product_requests` WRITE;
/*!40000 ALTER TABLE `product_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint unsigned NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price_per_kg` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `stock_quantity` int DEFAULT '0',
  `min_quantity` int DEFAULT '1',
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `stock_kg` int NOT NULL DEFAULT '0',
  `cost_price` decimal(10,2) DEFAULT NULL,
  `platform_margin` decimal(5,2) NOT NULL DEFAULT '0.00',
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `rejection_reason` text COLLATE utf8mb4_unicode_ci,
  `is_company_product` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `products_supplier_id_foreign` (`supplier_id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,1,3,'Gergelim','gergelim-a','Gergelim seco, qualidade premium',220.00,'kg',800,1,NULL,1,500,NULL,0.00,'approved',NULL,0,'2026-06-01 16:01:28','2026-06-01 19:39:47');
INSERT INTO `products` VALUES (2,2,3,'Gergelim','gergelim-b','Gergelim seco, embalagem 25kg',195.00,'kg',1200,1,NULL,1,1200,NULL,0.00,'approved',NULL,0,'2026-06-01 16:01:28','2026-06-01 16:01:28');
INSERT INTO `products` VALUES (3,1,3,'Gergelim','gergelim-c','Gergelim tipo exportacao',280.00,'kg',710,1,NULL,1,300,NULL,0.00,'approved',NULL,0,'2026-06-01 16:01:28','2026-06-01 19:39:46');
INSERT INTO `products` VALUES (4,1,1,'Milho amarelo','milho-amarelo-a','Milho amarelo seco',40.00,'kg',5000,1,NULL,1,5000,NULL,0.00,'approved',NULL,0,'2026-06-01 16:01:28','2026-06-01 16:01:28');
INSERT INTO `products` VALUES (5,2,1,'Milho amarelo','milho-amarelo-b','Milho amarelo seco, granel',38.00,'kg',8000,1,NULL,1,8000,NULL,0.00,'approved',NULL,0,'2026-06-01 16:01:28','2026-06-01 16:01:28');
INSERT INTO `products` VALUES (6,1,2,'Feijao manteiga','feijao-manteiga-a','Feijao manteiga grado 1',110.00,'kg',800,1,NULL,1,800,NULL,0.00,'approved',NULL,0,'2026-06-01 16:01:28','2026-06-01 16:01:28');
INSERT INTO `products` VALUES (7,1,5,'Teste 220','teste-220-6a1dfcd900a81','',264.00,'kg',10,1,NULL,1,0,220.00,20.00,'approved',NULL,0,'2026-06-01 19:42:49','2026-06-01 19:45:06');
INSERT INTO `products` VALUES (8,1,1,'teste 30','teste-30-6a1e100fa0605','',500.00,'kg',10,1,NULL,1,0,500.00,0.00,'pending',NULL,0,'2026-06-01 21:04:47','2026-06-01 21:04:47');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quote_requests`
--

DROP TABLE IF EXISTS `quote_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quote_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `quantity` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','responded','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `quote_requests_product_id_foreign` (`product_id`),
  CONSTRAINT `quote_requests_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quote_requests`
--

LOCK TABLES `quote_requests` WRITE;
/*!40000 ALTER TABLE `quote_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `quote_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'site_name','MozCommodities','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (2,'site_description','Marketplace de produtos agricolas de Mocambique','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (3,'contact_email','info@mozcommodities.co.mz','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (4,'contact_phone','+258 84 123 4567','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (5,'mpesa_number','84 123 4567','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (6,'bank_name','Millennium BIM','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (7,'bank_account','000123456789','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (8,'bank_nib','0001 0000 0012 3456 7891 0','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (9,'about_hero_title','Conectando o Campo à Mesa','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (10,'about_hero_subtitle','Somos uma plataforma inovadora que conecta produtores agrícolas moçambicanos a compradores em todo o país.','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (11,'about_stats_farmers','500+','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (12,'about_stats_products','1000+','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (13,'about_stats_deliveries','10000+','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (14,'about_mission_title','Nossa Missão','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (15,'about_mission_text','Facilitar o acesso a produtos agrícolas de qualidade, promovendo o desenvolvimento sustentável do sector agrícola moçambicano.','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (16,'about_vision_title','Nossa Visão','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (17,'about_vision_text','Ser a principal plataforma de comercialização de produtos agrícolas em Moçambique, reconhecida pela qualidade e confiabilidade.','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (18,'about_values_title','Nossos Valores','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (19,'about_values_list','Qualidade,Transparência,Sustentabilidade,Inovação,Compromisso','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (20,'about_team_title','Nossa Equipa','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (21,'about_team_text','Uma equipa dedicada de profissionais comprometidos com o sucesso dos nossos parceiros e clientes.','2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `settings` VALUES (22,'about_value_1_title','Qualidade','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (23,'about_value_1_text','Produtos criteriosamente selecionados, com padrões rigorosos de qualidade em cada etapa.','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (24,'about_value_2_title','Transparência','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (25,'about_value_2_text','Preços claros, origem rastreável e comunicação aberta entre produtor e comprador.','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (26,'about_value_3_title','Sustentabilidade','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (27,'about_value_3_text','Apoiamos práticas agrícolas que preservam o solo, a água e as comunidades rurais.','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (28,'about_value_4_title','Inovação','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (29,'about_value_4_text','Tecnologia ao serviço do agronegócio, simplificando a venda e a compra de produtos agrícolas.','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (30,'about_intro_text','Conectamos produtores agrícolas a empresas, restaurantes e consumidores que valorizam produtos locais, frescos e de qualidade. Reduzimos intermediários, garantimos preços justos para o agricultor e produtos rastreáveis para quem compra.','2026-06-01 18:55:52','2026-06-01 18:55:52');
INSERT INTO `settings` VALUES (35,'emola_sandbox','0','2026-06-01 21:09:20','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (36,'payment_emola_enabled','1','2026-06-01 21:09:20','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (37,'payment_mpesa_enabled','0','2026-06-01 21:09:20','2026-06-01 21:09:20');
INSERT INTO `settings` VALUES (38,'payment_card_enabled','0','2026-06-01 21:09:20','2026-06-01 21:09:20');
INSERT INTO `settings` VALUES (39,'mpesa_sandbox','1','2026-06-01 21:09:20','2026-06-01 21:09:20');
INSERT INTO `settings` VALUES (40,'emola_api_key','','2026-06-01 21:22:26','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (41,'emola_username','','2026-06-01 21:22:26','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (42,'emola_password','','2026-06-01 21:22:26','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (43,'emola_partner_code','','2026-06-01 21:22:26','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (44,'emola_base_url','http://tv.itcore.co.za/emola/file.php','2026-06-01 21:22:26','2026-06-01 21:22:26');
INSERT INTO `settings` VALUES (47,'mpesa_api_key','','2026-06-01 19:22:31','2026-06-01 19:22:31');
INSERT INTO `settings` VALUES (48,'mpesa_public_key','','2026-06-01 19:22:31','2026-06-01 19:22:31');
INSERT INTO `settings` VALUES (49,'mpesa_service_provider_code','','2026-06-01 19:22:31','2026-06-01 19:22:31');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shipping_zones`
--

DROP TABLE IF EXISTS `shipping_zones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `shipping_zones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `base_fee` decimal(10,2) NOT NULL DEFAULT '0.00',
  `per_kg_rate` decimal(10,2) NOT NULL DEFAULT '0.00',
  `truckload_threshold_kg` int unsigned DEFAULT NULL,
  `truckload_flat_fee` decimal(10,2) DEFAULT NULL,
  `free_above_amount` decimal(10,2) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `province` (`province`),
  KEY `active` (`active`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shipping_zones`
--

LOCK TABLES `shipping_zones` WRITE;
/*!40000 ALTER TABLE `shipping_zones` DISABLE KEYS */;
INSERT INTO `shipping_zones` VALUES (1,'Maputo Cidade',NULL,100.00,5.00,1000,4500.00,5000.00,1,'Maputo capital e Matola','2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (2,'Maputo Província',NULL,150.00,6.00,1000,5500.00,5000.00,1,NULL,'2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (3,'Gaza',NULL,250.00,8.00,1500,9000.00,10000.00,1,'Xai-Xai e região','2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (4,'Inhambane',NULL,350.00,10.00,1500,12000.00,10000.00,1,NULL,'2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (5,'Sofala',NULL,600.00,12.00,2000,18000.00,15000.00,1,'Beira','2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (6,'Manica',NULL,700.00,13.00,2000,19000.00,15000.00,1,'Chimoio','2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (7,'Tete',NULL,800.00,14.00,2000,22000.00,15000.00,1,NULL,'2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (8,'Zambézia',NULL,850.00,15.00,2000,23000.00,15000.00,1,'Quelimane','2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (9,'Nampula',NULL,950.00,16.00,2500,28000.00,20000.00,1,NULL,'2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (10,'Cabo Delgado',NULL,1100.00,18.00,2500,32000.00,20000.00,1,'Pemba','2026-06-01 22:39:15','2026-06-01 22:39:15');
INSERT INTO `shipping_zones` VALUES (11,'Niassa',NULL,1100.00,18.00,2500,32000.00,20000.00,1,'Lichinga','2026-06-01 22:39:15','2026-06-01 22:39:15');
/*!40000 ALTER TABLE `shipping_zones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stocks`
--

DROP TABLE IF EXISTS `stocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stocks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kg',
  `expiry_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stocks_product_id_foreign` (`product_id`),
  CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stocks`
--

LOCK TABLES `stocks` WRITE;
/*!40000 ALTER TABLE `stocks` DISABLE KEYS */;
/*!40000 ALTER TABLE `stocks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `company_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `business_license` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `document_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `longitude` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `suppliers_user_id_foreign` (`user_id`),
  CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES (1,3,'Fornecedor Teste Lda',NULL,'Fornecedor de produtos agricolas de qualidade',NULL,'approved',NULL,NULL,NULL,NULL,NULL,'2025-12-29 23:19:27','2025-12-29 23:19:27');
INSERT INTO `suppliers` VALUES (2,4,'AgroDistribuidor B Lda',NULL,NULL,NULL,'approved',NULL,NULL,NULL,NULL,NULL,'2026-06-01 16:01:28','2026-06-01 16:01:28');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` enum('admin','supplier','customer') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `profile_photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@gmail.com','2025-12-29 23:19:27','$2y$12$KtOJX/4iKKyXWbgCuNJkfeVKh3Vv/4taT87D8DO1RXkItJOFV58BO',NULL,'admin',NULL,NULL,NULL,NULL,NULL,'2025-12-29 23:19:27','2026-01-02 08:46:44');
INSERT INTO `users` VALUES (2,'Cliente Teste','cliente@gmail.com','2025-12-29 23:19:27','$2y$12$PSASxnKIN9MQBftwAJ2UheKy/nMkgS4effDAFT9exQy5Ok0YdGbPW',NULL,'customer',NULL,NULL,NULL,NULL,NULL,'2025-12-29 23:19:27','2026-01-02 08:46:44');
INSERT INTO `users` VALUES (3,'Fornecedor Teste','fornecedor@gmail.com','2025-12-29 23:19:27','$2y$12$Qi7qWvZ.jxLS0yFhwdgayOytiIwkfEvMwIWQtCWhsA2FQ3nsIckzK',NULL,'supplier',NULL,NULL,NULL,NULL,NULL,'2025-12-29 23:19:27','2026-01-02 08:46:44');
INSERT INTO `users` VALUES (4,'Fornecedor B','fornecedorb@gmail.com',NULL,'$2y$12$LQv3c1yqBWVHxkd0LHAkCOYz6TtxMQJqhN8/LewKyJlVgM2eUVbY.',NULL,'supplier',NULL,NULL,NULL,NULL,NULL,'2026-06-01 16:01:28','2026-06-01 16:01:28');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wishlist_items`
--

DROP TABLE IF EXISTS `wishlist_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wishlist_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_product` (`user_id`,`product_id`),
  KEY `user_id` (`user_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `fk_wl_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_wl_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wishlist_items`
--

LOCK TABLES `wishlist_items` WRITE;
/*!40000 ALTER TABLE `wishlist_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `wishlist_items` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-02 12:03:00
