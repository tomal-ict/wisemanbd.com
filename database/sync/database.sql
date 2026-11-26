-- =============================================
-- Ecommerce Database Sync Schema
-- Generated from a trusted local development database.
-- Deploy this file with the source code. Do not regenerate it on cPanel.
-- Regenerate locally: php artisan db:sync:export
-- Exported at: 2026-11-27 01:13:44
-- Engine: 10.11.16-MariaDB
-- Tables: 100
-- =============================================

-- @@TABLE:addons
CREATE TABLE IF NOT EXISTS `addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `unique_identifier` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `activated` int(11) NOT NULL DEFAULT 1,
  `image` varchar(1000) DEFAULT NULL,
  `purchase_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:addons|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:addons|name|`name` varchar(255) DEFAULT NULL
-- @@COLUMN:addons|unique_identifier|`unique_identifier` varchar(255) DEFAULT NULL
-- @@COLUMN:addons|version|`version` varchar(255) DEFAULT NULL
-- @@COLUMN:addons|activated|`activated` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:addons|image|`image` varchar(1000) DEFAULT NULL
-- @@COLUMN:addons|purchase_code|`purchase_code` varchar(255) DEFAULT NULL
-- @@COLUMN:addons|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:addons|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:addons|PRIMARY KEY (`id`)

-- @@TABLE:addresses
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `country_id` int(11) DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `city_id` int(11) DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `latitude` float DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `set_default` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_area` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:addresses|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:addresses|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:addresses|name|`name` varchar(255) NOT NULL
-- @@COLUMN:addresses|address|`address` varchar(255) DEFAULT NULL
-- @@COLUMN:addresses|country_id|`country_id` int(11) DEFAULT NULL
-- @@COLUMN:addresses|state_id|`state_id` int(11) NOT NULL
-- @@COLUMN:addresses|city_id|`city_id` int(11) DEFAULT NULL
-- @@COLUMN:addresses|longitude|`longitude` float DEFAULT NULL
-- @@COLUMN:addresses|latitude|`latitude` float DEFAULT NULL
-- @@COLUMN:addresses|postal_code|`postal_code` varchar(255) DEFAULT NULL
-- @@COLUMN:addresses|phone|`phone` varchar(255) DEFAULT NULL
-- @@COLUMN:addresses|set_default|`set_default` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:addresses|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:addresses|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:addresses|delivery_area|`delivery_area` varchar(50) DEFAULT NULL
-- @@KEY:addresses|PRIMARY KEY (`id`)

-- @@TABLE:app_translations
CREATE TABLE IF NOT EXISTS `app_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(10) DEFAULT NULL,
  `lang_key` varchar(255) DEFAULT NULL,
  `lang_value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:app_translations|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:app_translations|lang|`lang` varchar(10) DEFAULT NULL
-- @@COLUMN:app_translations|lang_key|`lang_key` varchar(255) DEFAULT NULL
-- @@COLUMN:app_translations|lang_value|`lang_value` varchar(255) DEFAULT NULL
-- @@COLUMN:app_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:app_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:app_translations|PRIMARY KEY (`id`)

-- @@TABLE:attribute_category
CREATE TABLE IF NOT EXISTS `attribute_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `attribute_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:attribute_category|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:attribute_category|category_id|`category_id` int(11) NOT NULL
-- @@COLUMN:attribute_category|attribute_id|`attribute_id` int(11) NOT NULL
-- @@COLUMN:attribute_category|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:attribute_category|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:attribute_category|PRIMARY KEY (`id`)

-- @@TABLE:attribute_translations
CREATE TABLE IF NOT EXISTS `attribute_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:attribute_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:attribute_translations|attribute_id|`attribute_id` bigint(20) NOT NULL
-- @@COLUMN:attribute_translations|name|`name` varchar(50) NOT NULL
-- @@COLUMN:attribute_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:attribute_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:attribute_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:attribute_translations|PRIMARY KEY (`id`)

-- @@TABLE:attribute_values
CREATE TABLE IF NOT EXISTS `attribute_values` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `attribute_id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `color_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:attribute_values|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:attribute_values|attribute_id|`attribute_id` int(11) NOT NULL
-- @@COLUMN:attribute_values|value|`value` varchar(255) NOT NULL
-- @@COLUMN:attribute_values|color_code|`color_code` varchar(100) DEFAULT NULL
-- @@COLUMN:attribute_values|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:attribute_values|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:attribute_values|PRIMARY KEY (`id`)

-- @@TABLE:attributes
CREATE TABLE IF NOT EXISTS `attributes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:attributes|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:attributes|name|`name` varchar(255) DEFAULT NULL
-- @@COLUMN:attributes|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:attributes|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:attributes|PRIMARY KEY (`id`)

-- @@TABLE:blog_categories
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:blog_categories|id|`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:blog_categories|category_name|`category_name` varchar(255) NOT NULL
-- @@COLUMN:blog_categories|slug|`slug` varchar(255) NOT NULL
-- @@COLUMN:blog_categories|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:blog_categories|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:blog_categories|deleted_at|`deleted_at` timestamp NULL DEFAULT NULL
-- @@KEY:blog_categories|PRIMARY KEY (`id`)

-- @@TABLE:blogs
CREATE TABLE IF NOT EXISTS `blogs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `banner` int(11) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_img` int(11) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:blogs|id|`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:blogs|category_id|`category_id` int(11) NOT NULL
-- @@COLUMN:blogs|title|`title` varchar(255) NOT NULL
-- @@COLUMN:blogs|slug|`slug` varchar(255) NOT NULL
-- @@COLUMN:blogs|short_description|`short_description` text DEFAULT NULL
-- @@COLUMN:blogs|description|`description` longtext DEFAULT NULL
-- @@COLUMN:blogs|banner|`banner` int(11) DEFAULT NULL
-- @@COLUMN:blogs|meta_title|`meta_title` varchar(255) DEFAULT NULL
-- @@COLUMN:blogs|meta_img|`meta_img` int(11) DEFAULT NULL
-- @@COLUMN:blogs|meta_description|`meta_description` text DEFAULT NULL
-- @@COLUMN:blogs|meta_keywords|`meta_keywords` text DEFAULT NULL
-- @@COLUMN:blogs|status|`status` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:blogs|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:blogs|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:blogs|deleted_at|`deleted_at` timestamp NULL DEFAULT NULL
-- @@KEY:blogs|PRIMARY KEY (`id`)

-- @@TABLE:brand_translations
CREATE TABLE IF NOT EXISTS `brand_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `brand_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:brand_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:brand_translations|brand_id|`brand_id` bigint(20) NOT NULL
-- @@COLUMN:brand_translations|name|`name` varchar(50) NOT NULL
-- @@COLUMN:brand_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:brand_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:brand_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:brand_translations|PRIMARY KEY (`id`)

-- @@TABLE:brands
CREATE TABLE IF NOT EXISTS `brands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `logo` varchar(100) DEFAULT NULL,
  `top` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:brands|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:brands|name|`name` varchar(50) NOT NULL
-- @@COLUMN:brands|logo|`logo` varchar(100) DEFAULT NULL
-- @@COLUMN:brands|top|`top` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:brands|slug|`slug` varchar(255) DEFAULT NULL
-- @@COLUMN:brands|meta_title|`meta_title` varchar(255) DEFAULT NULL
-- @@COLUMN:brands|meta_description|`meta_description` text DEFAULT NULL
-- @@COLUMN:brands|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:brands|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:brands|PRIMARY KEY (`id`)

-- @@TABLE:business_settings
CREATE TABLE IF NOT EXISTS `business_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(30) NOT NULL,
  `value` longtext DEFAULT NULL,
  `lang` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:business_settings|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:business_settings|type|`type` varchar(30) NOT NULL
-- @@COLUMN:business_settings|value|`value` longtext DEFAULT NULL
-- @@COLUMN:business_settings|lang|`lang` varchar(30) DEFAULT NULL
-- @@COLUMN:business_settings|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:business_settings|updated_at|`updated_at` timestamp NULL DEFAULT current_timestamp()
-- @@KEY:business_settings|PRIMARY KEY (`id`)

-- @@TABLE:carts
CREATE TABLE IF NOT EXISTS `carts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `temp_user_id` varchar(255) DEFAULT NULL,
  `address_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) DEFAULT NULL,
  `variation` text DEFAULT NULL,
  `price` double(20,2) DEFAULT 0.00,
  `tax` double(20,2) DEFAULT 0.00,
  `shipping_cost` double(20,2) DEFAULT 0.00,
  `shipping_type` varchar(30) NOT NULL DEFAULT '',
  `pickup_point` int(11) DEFAULT NULL,
  `discount` double(10,2) NOT NULL DEFAULT 0.00,
  `product_referral_code` varchar(255) DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `coupon_applied` tinyint(4) NOT NULL DEFAULT 0,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  `size` varchar(191) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_carts_user_id` (`user_id`),
  KEY `idx_carts_temp_user_id` (`temp_user_id`),
  KEY `idx_carts_product_id` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:carts|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:carts|owner_id|`owner_id` int(11) DEFAULT NULL
-- @@COLUMN:carts|user_id|`user_id` int(11) DEFAULT NULL
-- @@COLUMN:carts|temp_user_id|`temp_user_id` varchar(255) DEFAULT NULL
-- @@COLUMN:carts|address_id|`address_id` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:carts|product_id|`product_id` int(11) DEFAULT NULL
-- @@COLUMN:carts|variation|`variation` text DEFAULT NULL
-- @@COLUMN:carts|price|`price` double(20,2) DEFAULT 0.00
-- @@COLUMN:carts|tax|`tax` double(20,2) DEFAULT 0.00
-- @@COLUMN:carts|shipping_cost|`shipping_cost` double(20,2) DEFAULT 0.00
-- @@COLUMN:carts|shipping_type|`shipping_type` varchar(30) NOT NULL DEFAULT ''
-- @@COLUMN:carts|pickup_point|`pickup_point` int(11) DEFAULT NULL
-- @@COLUMN:carts|discount|`discount` double(10,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:carts|product_referral_code|`product_referral_code` varchar(255) DEFAULT NULL
-- @@COLUMN:carts|coupon_code|`coupon_code` varchar(255) DEFAULT NULL
-- @@COLUMN:carts|coupon_applied|`coupon_applied` tinyint(4) NOT NULL DEFAULT 0
-- @@COLUMN:carts|quantity|`quantity` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:carts|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:carts|updated_at|`updated_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:carts|size|`size` varchar(191) DEFAULT NULL
-- @@KEY:carts|PRIMARY KEY (`id`)
-- @@KEY:carts|KEY `idx_carts_user_id` (`user_id`)
-- @@KEY:carts|KEY `idx_carts_temp_user_id` (`temp_user_id`)
-- @@KEY:carts|KEY `idx_carts_product_id` (`product_id`)

-- @@TABLE:categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT 0,
  `level` int(11) NOT NULL DEFAULT 0,
  `name` varchar(50) NOT NULL,
  `order_level` int(11) NOT NULL DEFAULT 0,
  `commision_rate` double(8,2) NOT NULL DEFAULT 0.00,
  `banner` varchar(100) DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `featured` int(11) NOT NULL DEFAULT 0,
  `top` int(11) NOT NULL DEFAULT 0,
  `digital` int(11) NOT NULL DEFAULT 0,
  `slug` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:categories|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:categories|parent_id|`parent_id` int(11) DEFAULT 0
-- @@COLUMN:categories|level|`level` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:categories|name|`name` varchar(50) NOT NULL
-- @@COLUMN:categories|order_level|`order_level` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:categories|commision_rate|`commision_rate` double(8,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:categories|banner|`banner` varchar(100) DEFAULT NULL
-- @@COLUMN:categories|icon|`icon` varchar(100) DEFAULT NULL
-- @@COLUMN:categories|featured|`featured` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:categories|top|`top` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:categories|digital|`digital` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:categories|slug|`slug` varchar(255) DEFAULT NULL
-- @@COLUMN:categories|meta_title|`meta_title` varchar(255) DEFAULT NULL
-- @@COLUMN:categories|meta_description|`meta_description` text DEFAULT NULL
-- @@COLUMN:categories|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
-- @@COLUMN:categories|updated_at|`updated_at` timestamp NULL DEFAULT current_timestamp()
-- @@KEY:categories|PRIMARY KEY (`id`)
-- @@KEY:categories|KEY `slug` (`slug`)

-- @@TABLE:category_translations
CREATE TABLE IF NOT EXISTS `category_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:category_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:category_translations|category_id|`category_id` bigint(20) NOT NULL
-- @@COLUMN:category_translations|name|`name` varchar(50) NOT NULL
-- @@COLUMN:category_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:category_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:category_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:category_translations|PRIMARY KEY (`id`)

-- @@TABLE:cities
CREATE TABLE IF NOT EXISTS `cities` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state_id` int(11) NOT NULL,
  `cost` double(20,2) NOT NULL DEFAULT 0.00,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:cities|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:cities|name|`name` varchar(255) NOT NULL
-- @@COLUMN:cities|state_id|`state_id` int(11) NOT NULL
-- @@COLUMN:cities|cost|`cost` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:cities|status|`status` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:cities|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:cities|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:cities|deleted_at|`deleted_at` timestamp NULL DEFAULT NULL
-- @@KEY:cities|PRIMARY KEY (`id`)

-- @@TABLE:city_translations
CREATE TABLE IF NOT EXISTS `city_translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lang` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:city_translations|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:city_translations|city_id|`city_id` int(11) NOT NULL
-- @@COLUMN:city_translations|name|`name` varchar(255) NOT NULL
-- @@COLUMN:city_translations|lang|`lang` varchar(10) NOT NULL
-- @@COLUMN:city_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:city_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:city_translations|PRIMARY KEY (`id`)

-- @@TABLE:colors
CREATE TABLE IF NOT EXISTS `colors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `code` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:colors|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:colors|name|`name` varchar(30) DEFAULT NULL
-- @@COLUMN:colors|code|`code` varchar(10) DEFAULT NULL
-- @@COLUMN:colors|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:colors|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:colors|PRIMARY KEY (`id`)

-- @@TABLE:combined_orders
CREATE TABLE IF NOT EXISTS `combined_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:combined_orders|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:combined_orders|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:combined_orders|shipping_address|`shipping_address` text DEFAULT NULL
-- @@COLUMN:combined_orders|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:combined_orders|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:combined_orders|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:combined_orders|PRIMARY KEY (`id`)

-- @@TABLE:commission_histories
CREATE TABLE IF NOT EXISTS `commission_histories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `order_detail_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `admin_commission` double(25,2) NOT NULL,
  `seller_earning` double(25,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:commission_histories|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:commission_histories|order_id|`order_id` int(11) NOT NULL
-- @@COLUMN:commission_histories|order_detail_id|`order_detail_id` int(11) NOT NULL
-- @@COLUMN:commission_histories|seller_id|`seller_id` int(11) NOT NULL
-- @@COLUMN:commission_histories|admin_commission|`admin_commission` double(25,2) NOT NULL
-- @@COLUMN:commission_histories|seller_earning|`seller_earning` double(25,2) NOT NULL
-- @@COLUMN:commission_histories|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:commission_histories|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:commission_histories|PRIMARY KEY (`id`)

-- @@TABLE:conversations
CREATE TABLE IF NOT EXISTS `conversations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `title` varchar(1000) DEFAULT NULL,
  `sender_viewed` int(11) NOT NULL DEFAULT 1,
  `receiver_viewed` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:conversations|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:conversations|sender_id|`sender_id` int(11) NOT NULL
-- @@COLUMN:conversations|receiver_id|`receiver_id` int(11) NOT NULL
-- @@COLUMN:conversations|title|`title` varchar(1000) DEFAULT NULL
-- @@COLUMN:conversations|sender_viewed|`sender_viewed` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:conversations|receiver_viewed|`receiver_viewed` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:conversations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:conversations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:conversations|PRIMARY KEY (`id`)

-- @@TABLE:countries
CREATE TABLE IF NOT EXISTS `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(2) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:countries|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:countries|code|`code` varchar(2) NOT NULL DEFAULT ''
-- @@COLUMN:countries|name|`name` varchar(100) NOT NULL DEFAULT ''
-- @@COLUMN:countries|status|`status` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:countries|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:countries|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:countries|deleted_at|`deleted_at` timestamp NULL DEFAULT NULL
-- @@KEY:countries|PRIMARY KEY (`id`)

-- @@TABLE:coupon_usages
CREATE TABLE IF NOT EXISTS `coupon_usages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `coupon_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:coupon_usages|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:coupon_usages|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:coupon_usages|coupon_id|`coupon_id` int(11) NOT NULL
-- @@COLUMN:coupon_usages|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:coupon_usages|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:coupon_usages|PRIMARY KEY (`id`)

-- @@TABLE:coupons
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `details` longtext NOT NULL,
  `discount` double(20,2) NOT NULL,
  `discount_type` varchar(100) NOT NULL,
  `start_date` int(11) NOT NULL,
  `end_date` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:coupons|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:coupons|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:coupons|type|`type` varchar(255) NOT NULL
-- @@COLUMN:coupons|code|`code` varchar(255) NOT NULL
-- @@COLUMN:coupons|details|`details` longtext NOT NULL
-- @@COLUMN:coupons|discount|`discount` double(20,2) NOT NULL
-- @@COLUMN:coupons|discount_type|`discount_type` varchar(100) NOT NULL
-- @@COLUMN:coupons|start_date|`start_date` int(11) NOT NULL
-- @@COLUMN:coupons|end_date|`end_date` int(11) NOT NULL
-- @@COLUMN:coupons|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:coupons|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:coupons|PRIMARY KEY (`id`)

-- @@TABLE:currencies
CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `symbol` varchar(255) NOT NULL,
  `exchange_rate` double(10,5) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `code` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:currencies|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:currencies|name|`name` varchar(255) NOT NULL
-- @@COLUMN:currencies|symbol|`symbol` varchar(255) NOT NULL
-- @@COLUMN:currencies|exchange_rate|`exchange_rate` double(10,5) NOT NULL
-- @@COLUMN:currencies|status|`status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:currencies|code|`code` varchar(20) DEFAULT NULL
-- @@COLUMN:currencies|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:currencies|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:currencies|PRIMARY KEY (`id`)

-- @@TABLE:customer_package_payments
CREATE TABLE IF NOT EXISTS `customer_package_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `customer_package_id` int(11) NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `payment_details` longtext NOT NULL,
  `approval` int(11) NOT NULL,
  `offline_payment` int(11) NOT NULL COMMENT '1=offline payment\r\n2=online paymnet',
  `reciept` varchar(150) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:customer_package_payments|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:customer_package_payments|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:customer_package_payments|customer_package_id|`customer_package_id` int(11) NOT NULL
-- @@COLUMN:customer_package_payments|payment_method|`payment_method` varchar(255) NOT NULL
-- @@COLUMN:customer_package_payments|payment_details|`payment_details` longtext NOT NULL
-- @@COLUMN:customer_package_payments|approval|`approval` int(11) NOT NULL
-- @@COLUMN:customer_package_payments|offline_payment|`offline_payment` int(11) NOT NULL COMMENT '1=offline payment\r\n2=online paymnet'
-- @@COLUMN:customer_package_payments|reciept|`reciept` varchar(150) NOT NULL
-- @@COLUMN:customer_package_payments|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:customer_package_payments|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:customer_package_payments|PRIMARY KEY (`id`)

-- @@TABLE:customer_package_translations
CREATE TABLE IF NOT EXISTS `customer_package_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_package_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:customer_package_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:customer_package_translations|customer_package_id|`customer_package_id` bigint(20) NOT NULL
-- @@COLUMN:customer_package_translations|name|`name` varchar(50) NOT NULL
-- @@COLUMN:customer_package_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:customer_package_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:customer_package_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:customer_package_translations|PRIMARY KEY (`id`)

-- @@TABLE:customer_packages
CREATE TABLE IF NOT EXISTS `customer_packages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `amount` double(20,2) DEFAULT NULL,
  `product_upload` int(11) DEFAULT NULL,
  `logo` varchar(150) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:customer_packages|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:customer_packages|name|`name` varchar(255) DEFAULT NULL
-- @@COLUMN:customer_packages|amount|`amount` double(20,2) DEFAULT NULL
-- @@COLUMN:customer_packages|product_upload|`product_upload` int(11) DEFAULT NULL
-- @@COLUMN:customer_packages|logo|`logo` varchar(150) DEFAULT NULL
-- @@COLUMN:customer_packages|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:customer_packages|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:customer_packages|PRIMARY KEY (`id`)

-- @@TABLE:customer_product_translations
CREATE TABLE IF NOT EXISTS `customer_product_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer_product_id` bigint(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:customer_product_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:customer_product_translations|customer_product_id|`customer_product_id` bigint(20) NOT NULL
-- @@COLUMN:customer_product_translations|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:customer_product_translations|unit|`unit` varchar(20) DEFAULT NULL
-- @@COLUMN:customer_product_translations|description|`description` longtext DEFAULT NULL
-- @@COLUMN:customer_product_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:customer_product_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:customer_product_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:customer_product_translations|PRIMARY KEY (`id`)

-- @@TABLE:customer_products
CREATE TABLE IF NOT EXISTS `customer_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `published` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0,
  `added_by` varchar(50) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `subsubcategory_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `photos` varchar(255) DEFAULT NULL,
  `thumbnail_img` varchar(150) DEFAULT NULL,
  `conditon` varchar(50) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `video_provider` varchar(100) DEFAULT NULL,
  `video_link` varchar(200) DEFAULT NULL,
  `unit` varchar(200) DEFAULT NULL,
  `tags` varchar(255) DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `unit_price` double(20,2) DEFAULT 0.00,
  `meta_title` varchar(200) DEFAULT NULL,
  `meta_description` varchar(500) DEFAULT NULL,
  `meta_img` varchar(150) DEFAULT NULL,
  `pdf` varchar(200) DEFAULT NULL,
  `slug` varchar(200) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:customer_products|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:customer_products|name|`name` varchar(255) DEFAULT NULL
-- @@COLUMN:customer_products|published|`published` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:customer_products|status|`status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:customer_products|added_by|`added_by` varchar(50) DEFAULT NULL
-- @@COLUMN:customer_products|user_id|`user_id` int(11) DEFAULT NULL
-- @@COLUMN:customer_products|category_id|`category_id` int(11) DEFAULT NULL
-- @@COLUMN:customer_products|subcategory_id|`subcategory_id` int(11) DEFAULT NULL
-- @@COLUMN:customer_products|subsubcategory_id|`subsubcategory_id` int(11) DEFAULT NULL
-- @@COLUMN:customer_products|brand_id|`brand_id` int(11) DEFAULT NULL
-- @@COLUMN:customer_products|photos|`photos` varchar(255) DEFAULT NULL
-- @@COLUMN:customer_products|thumbnail_img|`thumbnail_img` varchar(150) DEFAULT NULL
-- @@COLUMN:customer_products|conditon|`conditon` varchar(50) DEFAULT NULL
-- @@COLUMN:customer_products|location|`location` text DEFAULT NULL
-- @@COLUMN:customer_products|video_provider|`video_provider` varchar(100) DEFAULT NULL
-- @@COLUMN:customer_products|video_link|`video_link` varchar(200) DEFAULT NULL
-- @@COLUMN:customer_products|unit|`unit` varchar(200) DEFAULT NULL
-- @@COLUMN:customer_products|tags|`tags` varchar(255) DEFAULT NULL
-- @@COLUMN:customer_products|description|`description` mediumtext DEFAULT NULL
-- @@COLUMN:customer_products|unit_price|`unit_price` double(20,2) DEFAULT 0.00
-- @@COLUMN:customer_products|meta_title|`meta_title` varchar(200) DEFAULT NULL
-- @@COLUMN:customer_products|meta_description|`meta_description` varchar(500) DEFAULT NULL
-- @@COLUMN:customer_products|meta_img|`meta_img` varchar(150) DEFAULT NULL
-- @@COLUMN:customer_products|pdf|`pdf` varchar(200) DEFAULT NULL
-- @@COLUMN:customer_products|slug|`slug` varchar(200) DEFAULT NULL
-- @@COLUMN:customer_products|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:customer_products|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:customer_products|PRIMARY KEY (`id`)

-- @@TABLE:customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:customers|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:customers|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:customers|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:customers|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:customers|PRIMARY KEY (`id`)

-- @@TABLE:firebase_notifications
CREATE TABLE IF NOT EXISTS `firebase_notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `item_type` varchar(255) NOT NULL,
  `item_type_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- @@COLUMN:firebase_notifications|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:firebase_notifications|title|`title` varchar(255) DEFAULT NULL
-- @@COLUMN:firebase_notifications|text|`text` text DEFAULT NULL
-- @@COLUMN:firebase_notifications|item_type|`item_type` varchar(255) NOT NULL
-- @@COLUMN:firebase_notifications|item_type_id|`item_type_id` int(11) NOT NULL
-- @@COLUMN:firebase_notifications|receiver_id|`receiver_id` int(11) NOT NULL
-- @@COLUMN:firebase_notifications|is_read|`is_read` tinyint(1) NOT NULL DEFAULT 0
-- @@COLUMN:firebase_notifications|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:firebase_notifications|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:firebase_notifications|PRIMARY KEY (`id`)

-- @@TABLE:flash_deal_products
CREATE TABLE IF NOT EXISTS `flash_deal_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `flash_deal_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount` double(20,2) DEFAULT 0.00,
  `discount_type` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:flash_deal_products|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:flash_deal_products|flash_deal_id|`flash_deal_id` int(11) NOT NULL
-- @@COLUMN:flash_deal_products|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:flash_deal_products|discount|`discount` double(20,2) DEFAULT 0.00
-- @@COLUMN:flash_deal_products|discount_type|`discount_type` varchar(20) DEFAULT NULL
-- @@COLUMN:flash_deal_products|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:flash_deal_products|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:flash_deal_products|PRIMARY KEY (`id`)

-- @@TABLE:flash_deal_translations
CREATE TABLE IF NOT EXISTS `flash_deal_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `flash_deal_id` bigint(20) NOT NULL,
  `title` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:flash_deal_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:flash_deal_translations|flash_deal_id|`flash_deal_id` bigint(20) NOT NULL
-- @@COLUMN:flash_deal_translations|title|`title` varchar(50) NOT NULL
-- @@COLUMN:flash_deal_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:flash_deal_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:flash_deal_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:flash_deal_translations|PRIMARY KEY (`id`)

-- @@TABLE:flash_deals
CREATE TABLE IF NOT EXISTS `flash_deals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `start_date` int(11) DEFAULT NULL,
  `end_date` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `featured` int(11) NOT NULL DEFAULT 0,
  `background_color` varchar(255) DEFAULT NULL,
  `text_color` varchar(255) DEFAULT NULL,
  `banner` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:flash_deals|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:flash_deals|title|`title` varchar(255) DEFAULT NULL
-- @@COLUMN:flash_deals|start_date|`start_date` int(11) DEFAULT NULL
-- @@COLUMN:flash_deals|end_date|`end_date` int(11) DEFAULT NULL
-- @@COLUMN:flash_deals|status|`status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:flash_deals|featured|`featured` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:flash_deals|background_color|`background_color` varchar(255) DEFAULT NULL
-- @@COLUMN:flash_deals|text_color|`text_color` varchar(255) DEFAULT NULL
-- @@COLUMN:flash_deals|banner|`banner` varchar(255) DEFAULT NULL
-- @@COLUMN:flash_deals|slug|`slug` varchar(255) DEFAULT NULL
-- @@COLUMN:flash_deals|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:flash_deals|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:flash_deals|PRIMARY KEY (`id`)

-- @@TABLE:home_categories
CREATE TABLE IF NOT EXISTS `home_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `subsubcategories` varchar(1000) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:home_categories|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:home_categories|category_id|`category_id` int(11) NOT NULL
-- @@COLUMN:home_categories|subsubcategories|`subsubcategories` varchar(1000) DEFAULT NULL
-- @@COLUMN:home_categories|status|`status` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:home_categories|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:home_categories|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:home_categories|PRIMARY KEY (`id`)

-- @@TABLE:incomplete_orders
CREATE TABLE IF NOT EXISTS `incomplete_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `temp_user_id` varchar(255) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `phone` varchar(191) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `city` varchar(191) DEFAULT NULL,
  `delivery_area` varchar(191) DEFAULT NULL,
  `products` text DEFAULT NULL,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(30) DEFAULT NULL,
  `viewed` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `incomplete_orders_temp_user_id_index` (`temp_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:incomplete_orders|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:incomplete_orders|temp_user_id|`temp_user_id` varchar(255) DEFAULT NULL
-- @@COLUMN:incomplete_orders|name|`name` varchar(191) DEFAULT NULL
-- @@COLUMN:incomplete_orders|email|`email` varchar(191) DEFAULT NULL
-- @@COLUMN:incomplete_orders|phone|`phone` varchar(191) DEFAULT NULL
-- @@COLUMN:incomplete_orders|address|`address` varchar(191) DEFAULT NULL
-- @@COLUMN:incomplete_orders|city|`city` varchar(191) DEFAULT NULL
-- @@COLUMN:incomplete_orders|delivery_area|`delivery_area` varchar(191) DEFAULT NULL
-- @@COLUMN:incomplete_orders|products|`products` text DEFAULT NULL
-- @@COLUMN:incomplete_orders|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:incomplete_orders|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:incomplete_orders|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:incomplete_orders|status|`status` varchar(30) DEFAULT NULL
-- @@COLUMN:incomplete_orders|viewed|`viewed` tinyint(1) NOT NULL DEFAULT 0
-- @@KEY:incomplete_orders|PRIMARY KEY (`id`)
-- @@KEY:incomplete_orders|KEY `incomplete_orders_temp_user_id_index` (`temp_user_id`)

-- @@TABLE:landing_pages
CREATE TABLE IF NOT EXISTS `landing_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `title` varchar(191) DEFAULT NULL,
  `discount` double(8,2) NOT NULL DEFAULT 0.00,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `header_text` text DEFAULT NULL,
  `footer_text` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:landing_pages|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:landing_pages|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:landing_pages|title|`title` varchar(191) DEFAULT NULL
-- @@COLUMN:landing_pages|discount|`discount` double(8,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:landing_pages|start_date|`start_date` timestamp NULL DEFAULT NULL
-- @@COLUMN:landing_pages|end_date|`end_date` timestamp NULL DEFAULT NULL
-- @@COLUMN:landing_pages|header_text|`header_text` text DEFAULT NULL
-- @@COLUMN:landing_pages|footer_text|`footer_text` text DEFAULT NULL
-- @@COLUMN:landing_pages|description|`description` longtext DEFAULT NULL
-- @@COLUMN:landing_pages|status|`status` tinyint(4) NOT NULL DEFAULT 1
-- @@COLUMN:landing_pages|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:landing_pages|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:landing_pages|PRIMARY KEY (`id`)

-- @@TABLE:languages
CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL,
  `app_lang_code` varchar(255) DEFAULT 'en',
  `rtl` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:languages|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:languages|name|`name` varchar(100) NOT NULL
-- @@COLUMN:languages|code|`code` varchar(100) NOT NULL
-- @@COLUMN:languages|app_lang_code|`app_lang_code` varchar(255) DEFAULT 'en'
-- @@COLUMN:languages|rtl|`rtl` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:languages|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:languages|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:languages|PRIMARY KEY (`id`)

-- @@TABLE:manual_payment_methods
CREATE TABLE IF NOT EXISTS `manual_payment_methods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `bank_info` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:manual_payment_methods|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:manual_payment_methods|type|`type` varchar(255) DEFAULT NULL
-- @@COLUMN:manual_payment_methods|heading|`heading` varchar(255) DEFAULT NULL
-- @@COLUMN:manual_payment_methods|description|`description` text DEFAULT NULL
-- @@COLUMN:manual_payment_methods|bank_info|`bank_info` text DEFAULT NULL
-- @@COLUMN:manual_payment_methods|photo|`photo` varchar(255) DEFAULT NULL
-- @@COLUMN:manual_payment_methods|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:manual_payment_methods|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:manual_payment_methods|PRIMARY KEY (`id`)

-- @@TABLE:messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:messages|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:messages|conversation_id|`conversation_id` int(11) NOT NULL
-- @@COLUMN:messages|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:messages|message|`message` text DEFAULT NULL
-- @@COLUMN:messages|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:messages|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:messages|PRIMARY KEY (`id`)

-- @@TABLE:migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:migrations|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:migrations|migration|`migration` varchar(191) NOT NULL
-- @@COLUMN:migrations|batch|`batch` int(11) NOT NULL
-- @@KEY:migrations|PRIMARY KEY (`id`)

-- @@TABLE:notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(191) NOT NULL,
  `notifiable_type` varchar(191) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:notifications|id|`id` char(36) NOT NULL
-- @@COLUMN:notifications|type|`type` varchar(191) NOT NULL
-- @@COLUMN:notifications|notifiable_type|`notifiable_type` varchar(191) NOT NULL
-- @@COLUMN:notifications|notifiable_id|`notifiable_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:notifications|data|`data` text NOT NULL
-- @@COLUMN:notifications|read_at|`read_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:notifications|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:notifications|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:notifications|PRIMARY KEY (`id`)
-- @@KEY:notifications|KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)

-- @@TABLE:order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `variation` longtext DEFAULT NULL,
  `color` varchar(191) DEFAULT NULL,
  `size` varchar(191) DEFAULT NULL,
  `price` double(20,2) DEFAULT NULL,
  `tax` double(20,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` double(20,2) NOT NULL DEFAULT 0.00,
  `quantity` int(11) DEFAULT NULL,
  `payment_status` varchar(10) NOT NULL DEFAULT 'unpaid',
  `delivery_status` varchar(20) DEFAULT 'pending',
  `shipping_type` varchar(255) DEFAULT NULL,
  `pickup_point_id` int(11) DEFAULT NULL,
  `product_referral_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:order_details|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:order_details|order_id|`order_id` int(11) NOT NULL
-- @@COLUMN:order_details|seller_id|`seller_id` int(11) DEFAULT NULL
-- @@COLUMN:order_details|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:order_details|variation|`variation` longtext DEFAULT NULL
-- @@COLUMN:order_details|color|`color` varchar(191) DEFAULT NULL
-- @@COLUMN:order_details|size|`size` varchar(191) DEFAULT NULL
-- @@COLUMN:order_details|price|`price` double(20,2) DEFAULT NULL
-- @@COLUMN:order_details|tax|`tax` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:order_details|shipping_cost|`shipping_cost` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:order_details|quantity|`quantity` int(11) DEFAULT NULL
-- @@COLUMN:order_details|payment_status|`payment_status` varchar(10) NOT NULL DEFAULT 'unpaid'
-- @@COLUMN:order_details|delivery_status|`delivery_status` varchar(20) DEFAULT 'pending'
-- @@COLUMN:order_details|shipping_type|`shipping_type` varchar(255) DEFAULT NULL
-- @@COLUMN:order_details|pickup_point_id|`pickup_point_id` int(11) DEFAULT NULL
-- @@COLUMN:order_details|product_referral_code|`product_referral_code` varchar(255) DEFAULT NULL
-- @@COLUMN:order_details|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:order_details|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:order_details|PRIMARY KEY (`id`)

-- @@TABLE:orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `combined_order_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `guest_id` int(11) DEFAULT NULL,
  `seller_id` int(11) DEFAULT NULL,
  `shipping_address` longtext DEFAULT NULL,
  `delivery_status` varchar(20) DEFAULT 'pending',
  `payment_type` varchar(20) DEFAULT NULL,
  `manual_payment` int(11) NOT NULL DEFAULT 0,
  `manual_payment_data` text DEFAULT NULL,
  `payment_status` varchar(20) DEFAULT 'unpaid',
  `payment_details` longtext DEFAULT NULL,
  `grand_total` double(20,2) DEFAULT NULL,
  `coupon_discount` double(20,2) NOT NULL DEFAULT 0.00,
  `code` mediumtext DEFAULT NULL,
  `tracking_code` varchar(255) DEFAULT NULL,
  `date` int(11) NOT NULL,
  `viewed` int(11) NOT NULL DEFAULT 0,
  `delivery_viewed` int(11) NOT NULL DEFAULT 1,
  `payment_status_viewed` int(11) DEFAULT 1,
  `commission_calculated` int(11) NOT NULL DEFAULT 0,
  `shipping_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:orders|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:orders|combined_order_id|`combined_order_id` int(11) DEFAULT NULL
-- @@COLUMN:orders|user_id|`user_id` int(11) DEFAULT NULL
-- @@COLUMN:orders|guest_id|`guest_id` int(11) DEFAULT NULL
-- @@COLUMN:orders|seller_id|`seller_id` int(11) DEFAULT NULL
-- @@COLUMN:orders|shipping_address|`shipping_address` longtext DEFAULT NULL
-- @@COLUMN:orders|delivery_status|`delivery_status` varchar(20) DEFAULT 'pending'
-- @@COLUMN:orders|payment_type|`payment_type` varchar(20) DEFAULT NULL
-- @@COLUMN:orders|manual_payment|`manual_payment` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:orders|manual_payment_data|`manual_payment_data` text DEFAULT NULL
-- @@COLUMN:orders|payment_status|`payment_status` varchar(20) DEFAULT 'unpaid'
-- @@COLUMN:orders|payment_details|`payment_details` longtext DEFAULT NULL
-- @@COLUMN:orders|grand_total|`grand_total` double(20,2) DEFAULT NULL
-- @@COLUMN:orders|coupon_discount|`coupon_discount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:orders|code|`code` mediumtext DEFAULT NULL
-- @@COLUMN:orders|tracking_code|`tracking_code` varchar(255) DEFAULT NULL
-- @@COLUMN:orders|date|`date` int(11) NOT NULL
-- @@COLUMN:orders|viewed|`viewed` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:orders|delivery_viewed|`delivery_viewed` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:orders|payment_status_viewed|`payment_status_viewed` int(11) DEFAULT 1
-- @@COLUMN:orders|commission_calculated|`commission_calculated` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:orders|shipping_type|`shipping_type` varchar(255) DEFAULT NULL
-- @@COLUMN:orders|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:orders|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:orders|PRIMARY KEY (`id`)

-- @@TABLE:otp_configurations
CREATE TABLE IF NOT EXISTS `otp_configurations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:otp_configurations|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:otp_configurations|type|`type` varchar(200) DEFAULT NULL
-- @@COLUMN:otp_configurations|value|`value` varchar(255) DEFAULT NULL
-- @@COLUMN:otp_configurations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:otp_configurations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:otp_configurations|PRIMARY KEY (`id`)

-- @@TABLE:page_translations
CREATE TABLE IF NOT EXISTS `page_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `page_id` bigint(20) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:page_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:page_translations|page_id|`page_id` bigint(20) NOT NULL
-- @@COLUMN:page_translations|title|`title` varchar(255) NOT NULL
-- @@COLUMN:page_translations|content|`content` longtext NOT NULL
-- @@COLUMN:page_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:page_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:page_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:page_translations|PRIMARY KEY (`id`)

-- @@TABLE:pages
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `meta_title` text DEFAULT NULL,
  `meta_description` varchar(1000) DEFAULT NULL,
  `keywords` varchar(1000) DEFAULT NULL,
  `meta_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:pages|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:pages|type|`type` varchar(50) NOT NULL
-- @@COLUMN:pages|title|`title` varchar(255) DEFAULT NULL
-- @@COLUMN:pages|slug|`slug` varchar(255) DEFAULT NULL
-- @@COLUMN:pages|content|`content` longtext DEFAULT NULL
-- @@COLUMN:pages|meta_title|`meta_title` text DEFAULT NULL
-- @@COLUMN:pages|meta_description|`meta_description` varchar(1000) DEFAULT NULL
-- @@COLUMN:pages|keywords|`keywords` varchar(1000) DEFAULT NULL
-- @@COLUMN:pages|meta_image|`meta_image` varchar(255) DEFAULT NULL
-- @@COLUMN:pages|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:pages|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:pages|PRIMARY KEY (`id`)

-- @@TABLE:password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) NOT NULL,
  `token` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:password_resets|email|`email` varchar(191) NOT NULL
-- @@COLUMN:password_resets|token|`token` varchar(191) NOT NULL
-- @@COLUMN:password_resets|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@KEY:password_resets|KEY `password_resets_email_index` (`email`)

-- @@TABLE:payku_payments
CREATE TABLE IF NOT EXISTS `payku_payments` (
  `transaction_id` varchar(191) NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `media` varchar(191) NOT NULL,
  `transaction_key` varchar(255) DEFAULT NULL,
  `payment_key` varchar(255) DEFAULT NULL,
  `verification_key` varchar(191) NOT NULL,
  `authorization_code` varchar(191) NOT NULL,
  `last_4_digits` int(10) unsigned DEFAULT NULL,
  `installments` varchar(191) DEFAULT NULL,
  `card_type` varchar(191) DEFAULT NULL,
  `additional_parameters` varchar(191) DEFAULT NULL,
  `currency` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `payku_payments_transaction_id_unique` (`transaction_id`),
  CONSTRAINT `payku_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `payku_transactions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:payku_payments|transaction_id|`transaction_id` varchar(191) NOT NULL
-- @@COLUMN:payku_payments|start|`start` date NOT NULL
-- @@COLUMN:payku_payments|end|`end` date NOT NULL
-- @@COLUMN:payku_payments|media|`media` varchar(191) NOT NULL
-- @@COLUMN:payku_payments|transaction_key|`transaction_key` varchar(255) DEFAULT NULL
-- @@COLUMN:payku_payments|payment_key|`payment_key` varchar(255) DEFAULT NULL
-- @@COLUMN:payku_payments|verification_key|`verification_key` varchar(191) NOT NULL
-- @@COLUMN:payku_payments|authorization_code|`authorization_code` varchar(191) NOT NULL
-- @@COLUMN:payku_payments|last_4_digits|`last_4_digits` int(10) unsigned DEFAULT NULL
-- @@COLUMN:payku_payments|installments|`installments` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_payments|card_type|`card_type` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_payments|additional_parameters|`additional_parameters` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_payments|currency|`currency` varchar(191) NOT NULL
-- @@COLUMN:payku_payments|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:payku_payments|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:payku_payments|UNIQUE KEY `payku_payments_transaction_id_unique` (`transaction_id`)
-- @@KEY:payku_payments|CONSTRAINT `payku_payments_transaction_id_foreign` FOREIGN KEY (`transaction_id`) REFERENCES `payku_transactions` (`id`)

-- @@TABLE:payku_transactions
CREATE TABLE IF NOT EXISTS `payku_transactions` (
  `id` varchar(191) NOT NULL,
  `status` varchar(191) DEFAULT NULL,
  `order` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `subject` varchar(191) DEFAULT NULL,
  `url` text DEFAULT NULL,
  `amount` int(10) unsigned DEFAULT NULL,
  `notified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `payku_transactions_id_unique` (`id`),
  UNIQUE KEY `payku_transactions_order_unique` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:payku_transactions|id|`id` varchar(191) NOT NULL
-- @@COLUMN:payku_transactions|status|`status` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_transactions|order|`order` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_transactions|email|`email` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_transactions|subject|`subject` varchar(191) DEFAULT NULL
-- @@COLUMN:payku_transactions|url|`url` text DEFAULT NULL
-- @@COLUMN:payku_transactions|amount|`amount` int(10) unsigned DEFAULT NULL
-- @@COLUMN:payku_transactions|notified_at|`notified_at` datetime DEFAULT NULL
-- @@COLUMN:payku_transactions|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:payku_transactions|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:payku_transactions|UNIQUE KEY `payku_transactions_id_unique` (`id`)
-- @@KEY:payku_transactions|UNIQUE KEY `payku_transactions_order_unique` (`order`)

-- @@TABLE:payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `seller_id` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `payment_details` longtext DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `txn_code` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:payments|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:payments|seller_id|`seller_id` int(11) NOT NULL
-- @@COLUMN:payments|amount|`amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:payments|payment_details|`payment_details` longtext DEFAULT NULL
-- @@COLUMN:payments|payment_method|`payment_method` varchar(255) DEFAULT NULL
-- @@COLUMN:payments|txn_code|`txn_code` varchar(100) DEFAULT NULL
-- @@COLUMN:payments|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:payments|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:payments|PRIMARY KEY (`id`)

-- @@TABLE:personal_access_tokens
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(191) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:personal_access_tokens|id|`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:personal_access_tokens|tokenable_type|`tokenable_type` varchar(191) NOT NULL
-- @@COLUMN:personal_access_tokens|tokenable_id|`tokenable_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:personal_access_tokens|name|`name` varchar(191) NOT NULL
-- @@COLUMN:personal_access_tokens|token|`token` varchar(64) NOT NULL
-- @@COLUMN:personal_access_tokens|abilities|`abilities` text DEFAULT NULL
-- @@COLUMN:personal_access_tokens|last_used_at|`last_used_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:personal_access_tokens|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:personal_access_tokens|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:personal_access_tokens|PRIMARY KEY (`id`)
-- @@KEY:personal_access_tokens|UNIQUE KEY `personal_access_tokens_token_unique` (`token`)
-- @@KEY:personal_access_tokens|KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)

-- @@TABLE:pickup_point_translations
CREATE TABLE IF NOT EXISTS `pickup_point_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pickup_point_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:pickup_point_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:pickup_point_translations|pickup_point_id|`pickup_point_id` bigint(20) NOT NULL
-- @@COLUMN:pickup_point_translations|name|`name` varchar(50) NOT NULL
-- @@COLUMN:pickup_point_translations|address|`address` text NOT NULL
-- @@COLUMN:pickup_point_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:pickup_point_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:pickup_point_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:pickup_point_translations|PRIMARY KEY (`id`)

-- @@TABLE:pickup_points
CREATE TABLE IF NOT EXISTS `pickup_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `staff_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` mediumtext NOT NULL,
  `phone` varchar(15) NOT NULL,
  `pick_up_status` int(11) DEFAULT NULL,
  `cash_on_pickup_status` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:pickup_points|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:pickup_points|staff_id|`staff_id` int(11) NOT NULL
-- @@COLUMN:pickup_points|name|`name` varchar(255) NOT NULL
-- @@COLUMN:pickup_points|address|`address` mediumtext NOT NULL
-- @@COLUMN:pickup_points|phone|`phone` varchar(15) NOT NULL
-- @@COLUMN:pickup_points|pick_up_status|`pick_up_status` int(11) DEFAULT NULL
-- @@COLUMN:pickup_points|cash_on_pickup_status|`cash_on_pickup_status` int(11) DEFAULT NULL
-- @@COLUMN:pickup_points|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:pickup_points|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:pickup_points|PRIMARY KEY (`id`)

-- @@TABLE:pos_customers
CREATE TABLE IF NOT EXISTS `pos_customers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customer_code` varchar(30) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `opening_balance` double(20,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_customers|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_customers|customer_code|`customer_code` varchar(30) DEFAULT NULL
-- @@COLUMN:pos_customers|name|`name` varchar(150) NOT NULL
-- @@COLUMN:pos_customers|mobile|`mobile` varchar(30) DEFAULT NULL
-- @@COLUMN:pos_customers|email|`email` varchar(150) DEFAULT NULL
-- @@COLUMN:pos_customers|address|`address` varchar(191) DEFAULT NULL
-- @@COLUMN:pos_customers|opening_balance|`opening_balance` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_customers|status|`status` tinyint(4) NOT NULL DEFAULT 1
-- @@COLUMN:pos_customers|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_customers|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_customers|PRIMARY KEY (`id`)

-- @@TABLE:pos_expense_categories
CREATE TABLE IF NOT EXISTS `pos_expense_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_expense_categories|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_expense_categories|code|`code` varchar(30) DEFAULT NULL
-- @@COLUMN:pos_expense_categories|name|`name` varchar(100) NOT NULL
-- @@COLUMN:pos_expense_categories|description|`description` text DEFAULT NULL
-- @@COLUMN:pos_expense_categories|status|`status` tinyint(4) NOT NULL DEFAULT 1
-- @@COLUMN:pos_expense_categories|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_expense_categories|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_expense_categories|PRIMARY KEY (`id`)

-- @@TABLE:pos_expenses
CREATE TABLE IF NOT EXISTS `pos_expenses` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `expense_code` varchar(50) DEFAULT NULL,
  `category_id` int(10) unsigned NOT NULL DEFAULT 0,
  `expense_date` date NOT NULL,
  `expense_for` varchar(150) DEFAULT NULL,
  `reference_no` varchar(50) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_expenses|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_expenses|expense_code|`expense_code` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_expenses|category_id|`category_id` int(10) unsigned NOT NULL DEFAULT 0
-- @@COLUMN:pos_expenses|expense_date|`expense_date` date NOT NULL
-- @@COLUMN:pos_expenses|expense_for|`expense_for` varchar(150) DEFAULT NULL
-- @@COLUMN:pos_expenses|reference_no|`reference_no` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_expenses|amount|`amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_expenses|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_expenses|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_expenses|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_expenses|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_expenses|PRIMARY KEY (`id`)

-- @@TABLE:pos_hold_items
CREATE TABLE IF NOT EXISTS `pos_hold_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hold_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `stock_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `variant` varchar(200) DEFAULT NULL,
  `qty` double(20,2) NOT NULL DEFAULT 0.00,
  `price` double(20,2) NOT NULL DEFAULT 0.00,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_hold_items|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_hold_items|hold_id|`hold_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_hold_items|product_id|`product_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:pos_hold_items|stock_id|`stock_id` int(10) unsigned DEFAULT NULL
-- @@COLUMN:pos_hold_items|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_hold_items|variant|`variant` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_hold_items|qty|`qty` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_hold_items|price|`price` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_hold_items|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_hold_items|total|`total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_hold_items|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_hold_items|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_hold_items|PRIMARY KEY (`id`)

-- @@TABLE:pos_holds
CREATE TABLE IF NOT EXISTS `pos_holds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hold_no` varchar(50) NOT NULL,
  `customer_id` int(10) unsigned NOT NULL DEFAULT 1,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` double(20,2) NOT NULL DEFAULT 0.00,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_holds|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_holds|hold_no|`hold_no` varchar(50) NOT NULL
-- @@COLUMN:pos_holds|customer_id|`customer_id` int(10) unsigned NOT NULL DEFAULT 1
-- @@COLUMN:pos_holds|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_holds|subtotal|`subtotal` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_holds|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_holds|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_holds|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_holds|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_holds|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_holds|PRIMARY KEY (`id`)

-- @@TABLE:pos_purchase_items
CREATE TABLE IF NOT EXISTS `pos_purchase_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `variant` varchar(200) DEFAULT NULL,
  `color` varchar(200) DEFAULT NULL,
  `size` varchar(200) DEFAULT NULL,
  `qty` double(20,2) NOT NULL DEFAULT 0.00,
  `unit_cost` double(20,2) NOT NULL DEFAULT 0.00,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_purchase_items|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_purchase_items|purchase_id|`purchase_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_purchase_items|product_id|`product_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:pos_purchase_items|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_purchase_items|variant|`variant` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_purchase_items|color|`color` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_purchase_items|size|`size` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_purchase_items|qty|`qty` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_items|unit_cost|`unit_cost` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_items|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_items|total|`total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_items|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_purchase_items|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_purchase_items|PRIMARY KEY (`id`)

-- @@TABLE:pos_purchase_payments
CREATE TABLE IF NOT EXISTS `pos_purchase_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_id` int(10) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_purchase_payments|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_purchase_payments|purchase_id|`purchase_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_purchase_payments|payment_date|`payment_date` date NOT NULL
-- @@COLUMN:pos_purchase_payments|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_purchase_payments|amount|`amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_payments|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_purchase_payments|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_purchase_payments|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_purchase_payments|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_purchase_payments|PRIMARY KEY (`id`)

-- @@TABLE:pos_purchase_return_items
CREATE TABLE IF NOT EXISTS `pos_purchase_return_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_return_id` int(10) unsigned NOT NULL,
  `purchase_item_id` int(10) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `qty` double(20,2) NOT NULL DEFAULT 0.00,
  `unit_cost` double(20,2) NOT NULL DEFAULT 0.00,
  `total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_purchase_return_items|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_purchase_return_items|purchase_return_id|`purchase_return_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_purchase_return_items|purchase_item_id|`purchase_item_id` int(10) unsigned DEFAULT NULL
-- @@COLUMN:pos_purchase_return_items|product_id|`product_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:pos_purchase_return_items|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_purchase_return_items|qty|`qty` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_return_items|unit_cost|`unit_cost` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_return_items|total|`total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_return_items|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_purchase_return_items|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_purchase_return_items|PRIMARY KEY (`id`)

-- @@TABLE:pos_purchase_returns
CREATE TABLE IF NOT EXISTS `pos_purchase_returns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_no` varchar(50) NOT NULL,
  `purchase_id` int(10) unsigned NOT NULL,
  `return_date` date NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL DEFAULT 1,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` double(20,2) NOT NULL DEFAULT 0.00,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `paid_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Accepted',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_purchase_returns|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_purchase_returns|return_no|`return_no` varchar(50) NOT NULL
-- @@COLUMN:pos_purchase_returns|purchase_id|`purchase_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_purchase_returns|return_date|`return_date` date NOT NULL
-- @@COLUMN:pos_purchase_returns|supplier_id|`supplier_id` int(10) unsigned NOT NULL DEFAULT 1
-- @@COLUMN:pos_purchase_returns|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_returns|subtotal|`subtotal` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_returns|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_returns|paid_amount|`paid_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchase_returns|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_purchase_returns|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_purchase_returns|status|`status` varchar(20) NOT NULL DEFAULT 'Accepted'
-- @@COLUMN:pos_purchase_returns|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_purchase_returns|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_purchase_returns|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_purchase_returns|PRIMARY KEY (`id`)

-- @@TABLE:pos_purchases
CREATE TABLE IF NOT EXISTS `pos_purchases` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_no` varchar(50) NOT NULL,
  `purchase_date` date NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL DEFAULT 1,
  `discount_type` varchar(20) DEFAULT NULL,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `other_charges` double(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` double(20,2) NOT NULL DEFAULT 0.00,
  `round_off` double(20,2) NOT NULL DEFAULT 0.00,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `paid_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_purchases|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_purchases|purchase_no|`purchase_no` varchar(50) NOT NULL
-- @@COLUMN:pos_purchases|purchase_date|`purchase_date` date NOT NULL
-- @@COLUMN:pos_purchases|supplier_id|`supplier_id` int(10) unsigned NOT NULL DEFAULT 1
-- @@COLUMN:pos_purchases|discount_type|`discount_type` varchar(20) DEFAULT NULL
-- @@COLUMN:pos_purchases|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchases|other_charges|`other_charges` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchases|subtotal|`subtotal` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchases|round_off|`round_off` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchases|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchases|paid_amount|`paid_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_purchases|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_purchases|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_purchases|status|`status` varchar(20) NOT NULL DEFAULT 'Pending'
-- @@COLUMN:pos_purchases|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_purchases|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_purchases|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_purchases|PRIMARY KEY (`id`)

-- @@TABLE:pos_return_items
CREATE TABLE IF NOT EXISTS `pos_return_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_id` int(10) unsigned NOT NULL,
  `sale_item_id` int(10) unsigned DEFAULT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `stock_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `variant` varchar(200) DEFAULT NULL,
  `qty` double(20,2) NOT NULL DEFAULT 0.00,
  `price` double(20,2) NOT NULL DEFAULT 0.00,
  `purchase_price` double(20,2) NOT NULL DEFAULT 0.00,
  `total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_return_items|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_return_items|return_id|`return_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_return_items|sale_item_id|`sale_item_id` int(10) unsigned DEFAULT NULL
-- @@COLUMN:pos_return_items|product_id|`product_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:pos_return_items|stock_id|`stock_id` int(10) unsigned DEFAULT NULL
-- @@COLUMN:pos_return_items|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_return_items|variant|`variant` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_return_items|qty|`qty` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_return_items|price|`price` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_return_items|purchase_price|`purchase_price` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_return_items|total|`total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_return_items|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_return_items|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_return_items|PRIMARY KEY (`id`)

-- @@TABLE:pos_returns
CREATE TABLE IF NOT EXISTS `pos_returns` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `return_no` varchar(50) NOT NULL,
  `sale_id` int(10) unsigned NOT NULL,
  `return_date` date NOT NULL,
  `customer_id` int(10) unsigned NOT NULL DEFAULT 1,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` double(20,2) NOT NULL DEFAULT 0.00,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `paid_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Accepted',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_returns|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_returns|return_no|`return_no` varchar(50) NOT NULL
-- @@COLUMN:pos_returns|sale_id|`sale_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_returns|return_date|`return_date` date NOT NULL
-- @@COLUMN:pos_returns|customer_id|`customer_id` int(10) unsigned NOT NULL DEFAULT 1
-- @@COLUMN:pos_returns|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_returns|subtotal|`subtotal` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_returns|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_returns|paid_amount|`paid_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_returns|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_returns|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_returns|status|`status` varchar(20) NOT NULL DEFAULT 'Accepted'
-- @@COLUMN:pos_returns|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_returns|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_returns|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_returns|PRIMARY KEY (`id`)

-- @@TABLE:pos_sale_items
CREATE TABLE IF NOT EXISTS `pos_sale_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(10) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `stock_id` int(10) unsigned DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `variant` varchar(200) DEFAULT NULL,
  `qty` double(20,2) NOT NULL DEFAULT 0.00,
  `price` double(20,2) NOT NULL DEFAULT 0.00,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `purchase_price` double(20,2) NOT NULL DEFAULT 0.00,
  `total` double(20,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_sale_items|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_sale_items|sale_id|`sale_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_sale_items|product_id|`product_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:pos_sale_items|stock_id|`stock_id` int(10) unsigned DEFAULT NULL
-- @@COLUMN:pos_sale_items|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_sale_items|variant|`variant` varchar(200) DEFAULT NULL
-- @@COLUMN:pos_sale_items|qty|`qty` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sale_items|price|`price` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sale_items|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sale_items|purchase_price|`purchase_price` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sale_items|total|`total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sale_items|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_sale_items|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_sale_items|PRIMARY KEY (`id`)

-- @@TABLE:pos_sale_payments
CREATE TABLE IF NOT EXISTS `pos_sale_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` int(10) unsigned NOT NULL,
  `payment_date` date NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_sale_payments|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_sale_payments|sale_id|`sale_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_sale_payments|payment_date|`payment_date` date NOT NULL
-- @@COLUMN:pos_sale_payments|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_sale_payments|amount|`amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sale_payments|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_sale_payments|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_sale_payments|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_sale_payments|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_sale_payments|PRIMARY KEY (`id`)

-- @@TABLE:pos_sales
CREATE TABLE IF NOT EXISTS `pos_sales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `slip_no` varchar(50) NOT NULL,
  `sale_date` date NOT NULL,
  `customer_id` int(10) unsigned NOT NULL DEFAULT 1,
  `discount_type` varchar(20) DEFAULT NULL,
  `discount_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `other_charges` double(20,2) NOT NULL DEFAULT 0.00,
  `subtotal` double(20,2) NOT NULL DEFAULT 0.00,
  `round_off` double(20,2) NOT NULL DEFAULT 0.00,
  `grand_total` double(20,2) NOT NULL DEFAULT 0.00,
  `paid_amount` double(20,2) NOT NULL DEFAULT 0.00,
  `change_return` double(20,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'Final',
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_sales|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_sales|slip_no|`slip_no` varchar(50) NOT NULL
-- @@COLUMN:pos_sales|sale_date|`sale_date` date NOT NULL
-- @@COLUMN:pos_sales|customer_id|`customer_id` int(10) unsigned NOT NULL DEFAULT 1
-- @@COLUMN:pos_sales|discount_type|`discount_type` varchar(20) DEFAULT NULL
-- @@COLUMN:pos_sales|discount_amount|`discount_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|other_charges|`other_charges` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|subtotal|`subtotal` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|round_off|`round_off` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|grand_total|`grand_total` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|paid_amount|`paid_amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|change_return|`change_return` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_sales|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_sales|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_sales|status|`status` varchar(20) NOT NULL DEFAULT 'Final'
-- @@COLUMN:pos_sales|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_sales|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_sales|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_sales|PRIMARY KEY (`id`)

-- @@TABLE:pos_stock_entries
CREATE TABLE IF NOT EXISTS `pos_stock_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `entry_date` date NOT NULL,
  `qty` double(20,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_stock_entries|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_stock_entries|product_id|`product_id` bigint(20) unsigned NOT NULL
-- @@COLUMN:pos_stock_entries|entry_date|`entry_date` date NOT NULL
-- @@COLUMN:pos_stock_entries|qty|`qty` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_stock_entries|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_stock_entries|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_stock_entries|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_stock_entries|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_stock_entries|PRIMARY KEY (`id`)

-- @@TABLE:pos_supplier_payments
CREATE TABLE IF NOT EXISTS `pos_supplier_payments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `payment_no` varchar(50) NOT NULL,
  `payment_date` date NOT NULL,
  `supplier_id` int(10) unsigned NOT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `amount` double(20,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_supplier_payments|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_supplier_payments|payment_no|`payment_no` varchar(50) NOT NULL
-- @@COLUMN:pos_supplier_payments|payment_date|`payment_date` date NOT NULL
-- @@COLUMN:pos_supplier_payments|supplier_id|`supplier_id` int(10) unsigned NOT NULL
-- @@COLUMN:pos_supplier_payments|payment_method|`payment_method` varchar(50) DEFAULT NULL
-- @@COLUMN:pos_supplier_payments|amount|`amount` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_supplier_payments|note|`note` text DEFAULT NULL
-- @@COLUMN:pos_supplier_payments|created_by|`created_by` bigint(20) unsigned DEFAULT NULL
-- @@COLUMN:pos_supplier_payments|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_supplier_payments|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_supplier_payments|PRIMARY KEY (`id`)

-- @@TABLE:pos_suppliers
CREATE TABLE IF NOT EXISTS `pos_suppliers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_code` varchar(30) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` varchar(191) DEFAULT NULL,
  `opening_balance` double(20,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:pos_suppliers|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:pos_suppliers|supplier_code|`supplier_code` varchar(30) DEFAULT NULL
-- @@COLUMN:pos_suppliers|name|`name` varchar(150) NOT NULL
-- @@COLUMN:pos_suppliers|mobile|`mobile` varchar(30) DEFAULT NULL
-- @@COLUMN:pos_suppliers|email|`email` varchar(150) DEFAULT NULL
-- @@COLUMN:pos_suppliers|address|`address` varchar(191) DEFAULT NULL
-- @@COLUMN:pos_suppliers|opening_balance|`opening_balance` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:pos_suppliers|status|`status` tinyint(4) NOT NULL DEFAULT 1
-- @@COLUMN:pos_suppliers|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:pos_suppliers|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:pos_suppliers|PRIMARY KEY (`id`)

-- @@TABLE:product_stocks
CREATE TABLE IF NOT EXISTS `product_stocks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `variant` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` double(20,2) NOT NULL DEFAULT 0.00,
  `qty` int(11) NOT NULL DEFAULT 0,
  `image` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:product_stocks|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:product_stocks|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:product_stocks|variant|`variant` varchar(255) NOT NULL
-- @@COLUMN:product_stocks|sku|`sku` varchar(255) DEFAULT NULL
-- @@COLUMN:product_stocks|price|`price` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:product_stocks|qty|`qty` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:product_stocks|image|`image` int(11) DEFAULT NULL
-- @@COLUMN:product_stocks|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:product_stocks|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:product_stocks|PRIMARY KEY (`id`)

-- @@TABLE:product_taxes
CREATE TABLE IF NOT EXISTS `product_taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `tax_id` int(11) NOT NULL,
  `tax` double(20,2) NOT NULL,
  `tax_type` varchar(10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:product_taxes|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:product_taxes|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:product_taxes|tax_id|`tax_id` int(11) NOT NULL
-- @@COLUMN:product_taxes|tax|`tax` double(20,2) NOT NULL
-- @@COLUMN:product_taxes|tax_type|`tax_type` varchar(10) NOT NULL
-- @@COLUMN:product_taxes|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:product_taxes|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:product_taxes|PRIMARY KEY (`id`)

-- @@TABLE:product_translations
CREATE TABLE IF NOT EXISTS `product_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `unit` varchar(20) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:product_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:product_translations|product_id|`product_id` bigint(20) NOT NULL
-- @@COLUMN:product_translations|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:product_translations|unit|`unit` varchar(20) DEFAULT NULL
-- @@COLUMN:product_translations|description|`description` longtext DEFAULT NULL
-- @@COLUMN:product_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:product_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:product_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:product_translations|PRIMARY KEY (`id`)

-- @@TABLE:products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `added_by` varchar(6) NOT NULL DEFAULT 'admin',
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `photos` varchar(2000) DEFAULT NULL,
  `thumbnail_img` varchar(100) DEFAULT NULL,
  `video_provider` varchar(20) DEFAULT NULL,
  `video_link` varchar(100) DEFAULT NULL,
  `tags` varchar(500) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `shortdescription` varchar(255) DEFAULT NULL,
  `unit_price` double(20,2) NOT NULL,
  `purchase_price` double(20,2) DEFAULT NULL,
  `variant_product` int(11) NOT NULL DEFAULT 0,
  `attributes` varchar(1000) NOT NULL DEFAULT '[]',
  `choice_options` mediumtext DEFAULT NULL,
  `colors` mediumtext DEFAULT NULL,
  `sizes` text DEFAULT NULL,
  `measurement_active` tinyint(4) NOT NULL DEFAULT 0,
  `measurement_img` varchar(255) DEFAULT NULL,
  `variations` text DEFAULT NULL,
  `todays_deal` int(11) NOT NULL DEFAULT 0,
  `published` int(11) NOT NULL DEFAULT 1,
  `approved` tinyint(1) NOT NULL DEFAULT 1,
  `stock_visibility_state` varchar(10) NOT NULL DEFAULT 'quantity',
  `cash_on_delivery` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = On, 0 = Off',
  `featured` int(11) NOT NULL DEFAULT 0,
  `mega_deal` int(11) NOT NULL DEFAULT 0,
  `seller_featured` int(11) NOT NULL DEFAULT 0,
  `current_stock` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(20) DEFAULT NULL,
  `min_qty` int(11) NOT NULL DEFAULT 1,
  `low_stock_quantity` int(11) DEFAULT NULL,
  `discount` double(20,2) DEFAULT NULL,
  `discount_type` varchar(10) DEFAULT NULL,
  `discount_start_date` int(11) DEFAULT NULL,
  `discount_end_date` int(11) DEFAULT NULL,
  `tax` double(20,2) DEFAULT NULL,
  `tax_type` varchar(10) DEFAULT NULL,
  `shipping_type` varchar(20) DEFAULT 'flat_rate',
  `shipping_cost` text DEFAULT NULL,
  `is_quantity_multiplied` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Mutiplied with shipping cost',
  `est_shipping_days` int(11) DEFAULT NULL,
  `num_of_sale` int(11) NOT NULL DEFAULT 0,
  `meta_title` mediumtext DEFAULT NULL,
  `meta_description` longtext DEFAULT NULL,
  `meta_img` varchar(255) DEFAULT NULL,
  `pdf` varchar(255) DEFAULT NULL,
  `slug` mediumtext NOT NULL,
  `rating` double(8,2) NOT NULL DEFAULT 0.00,
  `barcode` varchar(255) DEFAULT NULL,
  `digital` int(11) NOT NULL DEFAULT 0,
  `auction_product` int(11) NOT NULL DEFAULT 0,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `external_link` varchar(500) DEFAULT NULL,
  `external_link_btn` varchar(255) DEFAULT 'Buy Now',
  `wholesale_product` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pos_cost_price` double(20,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `tags` (`tags`(255)),
  KEY `unit_price` (`unit_price`),
  KEY `created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:products|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:products|name|`name` varchar(200) NOT NULL
-- @@COLUMN:products|added_by|`added_by` varchar(6) NOT NULL DEFAULT 'admin'
-- @@COLUMN:products|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:products|category_id|`category_id` int(11) NOT NULL
-- @@COLUMN:products|brand_id|`brand_id` int(11) DEFAULT NULL
-- @@COLUMN:products|photos|`photos` varchar(2000) DEFAULT NULL
-- @@COLUMN:products|thumbnail_img|`thumbnail_img` varchar(100) DEFAULT NULL
-- @@COLUMN:products|video_provider|`video_provider` varchar(20) DEFAULT NULL
-- @@COLUMN:products|video_link|`video_link` varchar(100) DEFAULT NULL
-- @@COLUMN:products|tags|`tags` varchar(500) DEFAULT NULL
-- @@COLUMN:products|description|`description` longtext DEFAULT NULL
-- @@COLUMN:products|shortdescription|`shortdescription` varchar(255) DEFAULT NULL
-- @@COLUMN:products|unit_price|`unit_price` double(20,2) NOT NULL
-- @@COLUMN:products|purchase_price|`purchase_price` double(20,2) DEFAULT NULL
-- @@COLUMN:products|variant_product|`variant_product` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|attributes|`attributes` varchar(1000) NOT NULL DEFAULT '[]'
-- @@COLUMN:products|choice_options|`choice_options` mediumtext DEFAULT NULL
-- @@COLUMN:products|colors|`colors` mediumtext DEFAULT NULL
-- @@COLUMN:products|sizes|`sizes` text DEFAULT NULL
-- @@COLUMN:products|measurement_active|`measurement_active` tinyint(4) NOT NULL DEFAULT 0
-- @@COLUMN:products|measurement_img|`measurement_img` varchar(255) DEFAULT NULL
-- @@COLUMN:products|variations|`variations` text DEFAULT NULL
-- @@COLUMN:products|todays_deal|`todays_deal` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|published|`published` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:products|approved|`approved` tinyint(1) NOT NULL DEFAULT 1
-- @@COLUMN:products|stock_visibility_state|`stock_visibility_state` varchar(10) NOT NULL DEFAULT 'quantity'
-- @@COLUMN:products|cash_on_delivery|`cash_on_delivery` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = On, 0 = Off'
-- @@COLUMN:products|featured|`featured` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|mega_deal|`mega_deal` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|seller_featured|`seller_featured` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|current_stock|`current_stock` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|unit|`unit` varchar(20) DEFAULT NULL
-- @@COLUMN:products|min_qty|`min_qty` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:products|low_stock_quantity|`low_stock_quantity` int(11) DEFAULT NULL
-- @@COLUMN:products|discount|`discount` double(20,2) DEFAULT NULL
-- @@COLUMN:products|discount_type|`discount_type` varchar(10) DEFAULT NULL
-- @@COLUMN:products|discount_start_date|`discount_start_date` int(11) DEFAULT NULL
-- @@COLUMN:products|discount_end_date|`discount_end_date` int(11) DEFAULT NULL
-- @@COLUMN:products|tax|`tax` double(20,2) DEFAULT NULL
-- @@COLUMN:products|tax_type|`tax_type` varchar(10) DEFAULT NULL
-- @@COLUMN:products|shipping_type|`shipping_type` varchar(20) DEFAULT 'flat_rate'
-- @@COLUMN:products|shipping_cost|`shipping_cost` text DEFAULT NULL
-- @@COLUMN:products|is_quantity_multiplied|`is_quantity_multiplied` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 = Mutiplied with shipping cost'
-- @@COLUMN:products|est_shipping_days|`est_shipping_days` int(11) DEFAULT NULL
-- @@COLUMN:products|num_of_sale|`num_of_sale` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|meta_title|`meta_title` mediumtext DEFAULT NULL
-- @@COLUMN:products|meta_description|`meta_description` longtext DEFAULT NULL
-- @@COLUMN:products|meta_img|`meta_img` varchar(255) DEFAULT NULL
-- @@COLUMN:products|pdf|`pdf` varchar(255) DEFAULT NULL
-- @@COLUMN:products|slug|`slug` mediumtext NOT NULL
-- @@COLUMN:products|rating|`rating` double(8,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:products|barcode|`barcode` varchar(255) DEFAULT NULL
-- @@COLUMN:products|digital|`digital` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|auction_product|`auction_product` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|file_name|`file_name` varchar(255) DEFAULT NULL
-- @@COLUMN:products|file_path|`file_path` varchar(255) DEFAULT NULL
-- @@COLUMN:products|external_link|`external_link` varchar(500) DEFAULT NULL
-- @@COLUMN:products|external_link_btn|`external_link_btn` varchar(255) DEFAULT 'Buy Now'
-- @@COLUMN:products|wholesale_product|`wholesale_product` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:products|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:products|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:products|pos_cost_price|`pos_cost_price` double(20,2) NOT NULL DEFAULT 0.00
-- @@KEY:products|PRIMARY KEY (`id`)
-- @@KEY:products|KEY `name` (`name`)
-- @@KEY:products|KEY `tags` (`tags`(255))
-- @@KEY:products|KEY `unit_price` (`unit_price`)
-- @@KEY:products|KEY `created_at` (`created_at`)

-- @@TABLE:proxypay_payments
CREATE TABLE IF NOT EXISTS `proxypay_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) NOT NULL,
  `reference_id` varchar(20) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `package_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `amount` double(25,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:proxypay_payments|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:proxypay_payments|payment_type|`payment_type` varchar(20) NOT NULL
-- @@COLUMN:proxypay_payments|reference_id|`reference_id` varchar(20) NOT NULL
-- @@COLUMN:proxypay_payments|order_id|`order_id` int(11) DEFAULT NULL
-- @@COLUMN:proxypay_payments|package_id|`package_id` int(11) DEFAULT NULL
-- @@COLUMN:proxypay_payments|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:proxypay_payments|amount|`amount` double(25,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:proxypay_payments|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:proxypay_payments|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:proxypay_payments|PRIMARY KEY (`id`)

-- @@TABLE:reviews
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 0,
  `comment` mediumtext NOT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `viewed` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:reviews|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:reviews|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:reviews|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:reviews|rating|`rating` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:reviews|comment|`comment` mediumtext NOT NULL
-- @@COLUMN:reviews|status|`status` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:reviews|viewed|`viewed` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:reviews|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:reviews|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:reviews|PRIMARY KEY (`id`)

-- @@TABLE:role_translations
CREATE TABLE IF NOT EXISTS `role_translations` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lang` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:role_translations|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:role_translations|role_id|`role_id` bigint(20) NOT NULL
-- @@COLUMN:role_translations|name|`name` varchar(50) NOT NULL
-- @@COLUMN:role_translations|lang|`lang` varchar(100) NOT NULL
-- @@COLUMN:role_translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:role_translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:role_translations|PRIMARY KEY (`id`)

-- @@TABLE:roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `permissions` mediumtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:roles|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:roles|name|`name` varchar(30) NOT NULL
-- @@COLUMN:roles|permissions|`permissions` mediumtext NOT NULL
-- @@COLUMN:roles|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:roles|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:roles|PRIMARY KEY (`id`)

-- @@TABLE:searches
CREATE TABLE IF NOT EXISTS `searches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `query` varchar(1000) NOT NULL,
  `count` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:searches|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:searches|query|`query` varchar(1000) NOT NULL
-- @@COLUMN:searches|count|`count` int(11) NOT NULL DEFAULT 1
-- @@COLUMN:searches|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:searches|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:searches|PRIMARY KEY (`id`)

-- @@TABLE:seller_withdraw_requests
CREATE TABLE IF NOT EXISTS `seller_withdraw_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `amount` double(20,2) DEFAULT NULL,
  `message` longtext DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `viewed` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:seller_withdraw_requests|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:seller_withdraw_requests|user_id|`user_id` int(11) DEFAULT NULL
-- @@COLUMN:seller_withdraw_requests|amount|`amount` double(20,2) DEFAULT NULL
-- @@COLUMN:seller_withdraw_requests|message|`message` longtext DEFAULT NULL
-- @@COLUMN:seller_withdraw_requests|status|`status` int(11) DEFAULT NULL
-- @@COLUMN:seller_withdraw_requests|viewed|`viewed` int(11) DEFAULT NULL
-- @@COLUMN:seller_withdraw_requests|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:seller_withdraw_requests|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:seller_withdraw_requests|PRIMARY KEY (`id`)

-- @@TABLE:sellers
CREATE TABLE IF NOT EXISTS `sellers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `rating` double(3,2) NOT NULL DEFAULT 0.00,
  `num_of_reviews` int(11) NOT NULL DEFAULT 0,
  `num_of_sale` int(11) NOT NULL DEFAULT 0,
  `verification_status` int(11) NOT NULL DEFAULT 0,
  `verification_info` longtext DEFAULT NULL,
  `cash_on_delivery_status` int(11) NOT NULL DEFAULT 0,
  `admin_to_pay` double(20,2) NOT NULL DEFAULT 0.00,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_acc_name` varchar(200) DEFAULT NULL,
  `bank_acc_no` varchar(50) DEFAULT NULL,
  `bank_routing_no` int(11) DEFAULT NULL,
  `bank_payment_status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:sellers|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:sellers|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:sellers|rating|`rating` double(3,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:sellers|num_of_reviews|`num_of_reviews` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:sellers|num_of_sale|`num_of_sale` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:sellers|verification_status|`verification_status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:sellers|verification_info|`verification_info` longtext DEFAULT NULL
-- @@COLUMN:sellers|cash_on_delivery_status|`cash_on_delivery_status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:sellers|admin_to_pay|`admin_to_pay` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:sellers|bank_name|`bank_name` varchar(255) DEFAULT NULL
-- @@COLUMN:sellers|bank_acc_name|`bank_acc_name` varchar(200) DEFAULT NULL
-- @@COLUMN:sellers|bank_acc_no|`bank_acc_no` varchar(50) DEFAULT NULL
-- @@COLUMN:sellers|bank_routing_no|`bank_routing_no` int(11) DEFAULT NULL
-- @@COLUMN:sellers|bank_payment_status|`bank_payment_status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:sellers|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:sellers|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:sellers|PRIMARY KEY (`id`)
-- @@KEY:sellers|UNIQUE KEY `user_id` (`user_id`)

-- @@TABLE:shops
CREATE TABLE IF NOT EXISTS `shops` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `sliders` longtext DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(500) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `google` varchar(255) DEFAULT NULL,
  `twitter` varchar(255) DEFAULT NULL,
  `youtube` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `pick_up_point_id` text DEFAULT NULL,
  `shipping_cost` double(20,2) NOT NULL DEFAULT 0.00,
  `delivery_pickup_latitude` float DEFAULT NULL,
  `delivery_pickup_longitude` float DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:shops|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:shops|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:shops|name|`name` varchar(200) DEFAULT NULL
-- @@COLUMN:shops|logo|`logo` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|sliders|`sliders` longtext DEFAULT NULL
-- @@COLUMN:shops|phone|`phone` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|address|`address` varchar(500) DEFAULT NULL
-- @@COLUMN:shops|facebook|`facebook` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|google|`google` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|twitter|`twitter` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|youtube|`youtube` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|slug|`slug` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|meta_title|`meta_title` varchar(255) DEFAULT NULL
-- @@COLUMN:shops|meta_description|`meta_description` text DEFAULT NULL
-- @@COLUMN:shops|pick_up_point_id|`pick_up_point_id` text DEFAULT NULL
-- @@COLUMN:shops|shipping_cost|`shipping_cost` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:shops|delivery_pickup_latitude|`delivery_pickup_latitude` float DEFAULT NULL
-- @@COLUMN:shops|delivery_pickup_longitude|`delivery_pickup_longitude` float DEFAULT NULL
-- @@COLUMN:shops|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:shops|updated_at|`updated_at` timestamp NULL DEFAULT current_timestamp()
-- @@KEY:shops|PRIMARY KEY (`id`)

-- @@TABLE:size_stocks
CREATE TABLE IF NOT EXISTS `size_stocks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(10) unsigned NOT NULL,
  `color` varchar(191) DEFAULT NULL,
  `size` varchar(191) DEFAULT NULL,
  `variant` varchar(255) DEFAULT NULL,
  `qty` int(11) NOT NULL DEFAULT 0,
  `sku` varchar(191) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `size_stocks_product_id_size_color_index` (`product_id`,`size`,`color`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- @@COLUMN:size_stocks|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:size_stocks|product_id|`product_id` int(10) unsigned NOT NULL
-- @@COLUMN:size_stocks|color|`color` varchar(191) DEFAULT NULL
-- @@COLUMN:size_stocks|size|`size` varchar(191) DEFAULT NULL
-- @@COLUMN:size_stocks|variant|`variant` varchar(255) DEFAULT NULL
-- @@COLUMN:size_stocks|qty|`qty` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:size_stocks|sku|`sku` varchar(191) DEFAULT NULL
-- @@COLUMN:size_stocks|price|`price` decimal(10,2) DEFAULT NULL
-- @@COLUMN:size_stocks|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:size_stocks|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:size_stocks|PRIMARY KEY (`id`)
-- @@KEY:size_stocks|KEY `size_stocks_product_id_size_color_index` (`product_id`,`size`,`color`)

-- @@TABLE:sizes
CREATE TABLE IF NOT EXISTS `sizes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:sizes|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:sizes|name|`name` varchar(30) DEFAULT NULL
-- @@COLUMN:sizes|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:sizes|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:sizes|PRIMARY KEY (`id`)

-- @@TABLE:sms_templates
CREATE TABLE IF NOT EXISTS `sms_templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `identifier` varchar(100) NOT NULL,
  `sms_body` longtext NOT NULL,
  `template_id` varchar(100) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:sms_templates|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:sms_templates|identifier|`identifier` varchar(100) NOT NULL
-- @@COLUMN:sms_templates|sms_body|`sms_body` longtext NOT NULL
-- @@COLUMN:sms_templates|template_id|`template_id` varchar(100) DEFAULT NULL
-- @@COLUMN:sms_templates|status|`status` tinyint(1) NOT NULL DEFAULT 1
-- @@COLUMN:sms_templates|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:sms_templates|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:sms_templates|PRIMARY KEY (`id`)

-- @@TABLE:staff
CREATE TABLE IF NOT EXISTS `staff` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:staff|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:staff|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:staff|role_id|`role_id` int(11) NOT NULL
-- @@COLUMN:staff|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:staff|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:staff|PRIMARY KEY (`id`)

-- @@TABLE:states
CREATE TABLE IF NOT EXISTS `states` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `country_id` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:states|id|`id` bigint(20) NOT NULL AUTO_INCREMENT
-- @@COLUMN:states|name|`name` varchar(255) NOT NULL
-- @@COLUMN:states|country_id|`country_id` int(11) NOT NULL
-- @@COLUMN:states|status|`status` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:states|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:states|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:states|deleted_at|`deleted_at` timestamp NULL DEFAULT NULL
-- @@KEY:states|PRIMARY KEY (`id`)

-- @@TABLE:subscribers
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:subscribers|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:subscribers|email|`email` varchar(50) NOT NULL
-- @@COLUMN:subscribers|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:subscribers|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:subscribers|PRIMARY KEY (`id`)
-- @@KEY:subscribers|UNIQUE KEY `email` (`email`)

-- @@TABLE:taxes
CREATE TABLE IF NOT EXISTS `taxes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `tax_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:taxes|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:taxes|name|`name` varchar(255) NOT NULL
-- @@COLUMN:taxes|tax_status|`tax_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0 = Inactive, 1 = Active'
-- @@COLUMN:taxes|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:taxes|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:taxes|PRIMARY KEY (`id`)

-- @@TABLE:ticket_replies
CREATE TABLE IF NOT EXISTS `ticket_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply` longtext NOT NULL,
  `files` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:ticket_replies|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:ticket_replies|ticket_id|`ticket_id` int(11) NOT NULL
-- @@COLUMN:ticket_replies|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:ticket_replies|reply|`reply` longtext NOT NULL
-- @@COLUMN:ticket_replies|files|`files` longtext DEFAULT NULL
-- @@COLUMN:ticket_replies|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
-- @@COLUMN:ticket_replies|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:ticket_replies|PRIMARY KEY (`id`)

-- @@TABLE:tickets
CREATE TABLE IF NOT EXISTS `tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `details` longtext DEFAULT NULL,
  `files` longtext DEFAULT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'pending',
  `viewed` int(11) NOT NULL DEFAULT 0,
  `client_viewed` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:tickets|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:tickets|code|`code` int(11) NOT NULL
-- @@COLUMN:tickets|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:tickets|subject|`subject` varchar(255) NOT NULL
-- @@COLUMN:tickets|details|`details` longtext DEFAULT NULL
-- @@COLUMN:tickets|files|`files` longtext DEFAULT NULL
-- @@COLUMN:tickets|status|`status` varchar(10) NOT NULL DEFAULT 'pending'
-- @@COLUMN:tickets|viewed|`viewed` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:tickets|client_viewed|`client_viewed` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:tickets|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
-- @@COLUMN:tickets|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:tickets|PRIMARY KEY (`id`)

-- @@TABLE:transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gateway` varchar(255) DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `additional_content` text DEFAULT NULL,
  `mpesa_request` varchar(255) DEFAULT NULL,
  `mpesa_receipt` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- @@COLUMN:transactions|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:transactions|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:transactions|gateway|`gateway` varchar(255) DEFAULT NULL
-- @@COLUMN:transactions|payment_type|`payment_type` varchar(255) DEFAULT NULL
-- @@COLUMN:transactions|additional_content|`additional_content` text DEFAULT NULL
-- @@COLUMN:transactions|mpesa_request|`mpesa_request` varchar(255) DEFAULT NULL
-- @@COLUMN:transactions|mpesa_receipt|`mpesa_receipt` varchar(255) DEFAULT NULL
-- @@COLUMN:transactions|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:transactions|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:transactions|PRIMARY KEY (`id`)

-- @@TABLE:translations
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(10) DEFAULT NULL,
  `lang_key` text DEFAULT NULL,
  `lang_value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:translations|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:translations|lang|`lang` varchar(10) DEFAULT NULL
-- @@COLUMN:translations|lang_key|`lang_key` text DEFAULT NULL
-- @@COLUMN:translations|lang_value|`lang_value` text DEFAULT NULL
-- @@COLUMN:translations|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:translations|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:translations|PRIMARY KEY (`id`)

-- @@TABLE:uploads
CREATE TABLE IF NOT EXISTS `uploads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `file_original_name` varchar(255) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `extension` varchar(10) DEFAULT NULL,
  `type` varchar(15) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:uploads|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:uploads|file_original_name|`file_original_name` varchar(255) DEFAULT NULL
-- @@COLUMN:uploads|file_name|`file_name` varchar(255) DEFAULT NULL
-- @@COLUMN:uploads|user_id|`user_id` int(11) DEFAULT NULL
-- @@COLUMN:uploads|file_size|`file_size` int(11) DEFAULT NULL
-- @@COLUMN:uploads|extension|`extension` varchar(10) DEFAULT NULL
-- @@COLUMN:uploads|type|`type` varchar(15) DEFAULT NULL
-- @@COLUMN:uploads|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:uploads|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:uploads|deleted_at|`deleted_at` timestamp NULL DEFAULT NULL
-- @@KEY:uploads|PRIMARY KEY (`id`)

-- @@TABLE:users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `referred_by` int(11) DEFAULT NULL,
  `provider_id` varchar(50) DEFAULT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'customer',
  `name` varchar(191) NOT NULL,
  `email` varchar(191) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `verification_code` text DEFAULT NULL,
  `new_email_verificiation_code` text DEFAULT NULL,
  `password` varchar(191) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `device_token` varchar(255) DEFAULT NULL,
  `avatar` varchar(256) DEFAULT NULL,
  `avatar_original` varchar(256) DEFAULT NULL,
  `address` varchar(300) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `balance` double(20,2) NOT NULL DEFAULT 0.00,
  `banned` tinyint(4) NOT NULL DEFAULT 0,
  `referral_code` varchar(255) DEFAULT NULL,
  `customer_package_id` int(11) DEFAULT NULL,
  `remaining_uploads` int(11) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:users|id|`id` int(10) unsigned NOT NULL AUTO_INCREMENT
-- @@COLUMN:users|referred_by|`referred_by` int(11) DEFAULT NULL
-- @@COLUMN:users|provider_id|`provider_id` varchar(50) DEFAULT NULL
-- @@COLUMN:users|user_type|`user_type` varchar(20) NOT NULL DEFAULT 'customer'
-- @@COLUMN:users|name|`name` varchar(191) NOT NULL
-- @@COLUMN:users|email|`email` varchar(191) DEFAULT NULL
-- @@COLUMN:users|email_verified_at|`email_verified_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:users|verification_code|`verification_code` text DEFAULT NULL
-- @@COLUMN:users|new_email_verificiation_code|`new_email_verificiation_code` text DEFAULT NULL
-- @@COLUMN:users|password|`password` varchar(191) DEFAULT NULL
-- @@COLUMN:users|remember_token|`remember_token` varchar(100) DEFAULT NULL
-- @@COLUMN:users|device_token|`device_token` varchar(255) DEFAULT NULL
-- @@COLUMN:users|avatar|`avatar` varchar(256) DEFAULT NULL
-- @@COLUMN:users|avatar_original|`avatar_original` varchar(256) DEFAULT NULL
-- @@COLUMN:users|address|`address` varchar(300) DEFAULT NULL
-- @@COLUMN:users|country|`country` varchar(30) DEFAULT NULL
-- @@COLUMN:users|state|`state` varchar(30) DEFAULT NULL
-- @@COLUMN:users|city|`city` varchar(30) DEFAULT NULL
-- @@COLUMN:users|postal_code|`postal_code` varchar(20) DEFAULT NULL
-- @@COLUMN:users|phone|`phone` varchar(20) DEFAULT NULL
-- @@COLUMN:users|balance|`balance` double(20,2) NOT NULL DEFAULT 0.00
-- @@COLUMN:users|banned|`banned` tinyint(4) NOT NULL DEFAULT 0
-- @@COLUMN:users|referral_code|`referral_code` varchar(255) DEFAULT NULL
-- @@COLUMN:users|customer_package_id|`customer_package_id` int(11) DEFAULT NULL
-- @@COLUMN:users|remaining_uploads|`remaining_uploads` int(11) DEFAULT 0
-- @@COLUMN:users|created_at|`created_at` timestamp NULL DEFAULT NULL
-- @@COLUMN:users|updated_at|`updated_at` timestamp NULL DEFAULT NULL
-- @@KEY:users|PRIMARY KEY (`id`)
-- @@KEY:users|UNIQUE KEY `users_email_unique` (`email`)

-- @@TABLE:wallets
CREATE TABLE IF NOT EXISTS `wallets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` double(20,2) NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `payment_details` longtext DEFAULT NULL,
  `approval` int(11) NOT NULL DEFAULT 0,
  `offline_payment` int(11) NOT NULL DEFAULT 0,
  `reciept` varchar(150) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:wallets|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:wallets|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:wallets|amount|`amount` double(20,2) NOT NULL
-- @@COLUMN:wallets|payment_method|`payment_method` varchar(255) DEFAULT NULL
-- @@COLUMN:wallets|payment_details|`payment_details` longtext DEFAULT NULL
-- @@COLUMN:wallets|approval|`approval` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:wallets|offline_payment|`offline_payment` int(11) NOT NULL DEFAULT 0
-- @@COLUMN:wallets|reciept|`reciept` varchar(150) DEFAULT NULL
-- @@COLUMN:wallets|created_at|`created_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@COLUMN:wallets|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:wallets|PRIMARY KEY (`id`)

-- @@TABLE:wishlists
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- @@COLUMN:wishlists|id|`id` int(11) NOT NULL AUTO_INCREMENT
-- @@COLUMN:wishlists|user_id|`user_id` int(11) NOT NULL
-- @@COLUMN:wishlists|product_id|`product_id` int(11) NOT NULL
-- @@COLUMN:wishlists|created_at|`created_at` timestamp NULL DEFAULT current_timestamp()
-- @@COLUMN:wishlists|updated_at|`updated_at` timestamp NOT NULL DEFAULT current_timestamp()
-- @@KEY:wishlists|PRIMARY KEY (`id`)

