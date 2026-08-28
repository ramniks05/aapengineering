-- AAP Engineerings — import in Hostinger phpMyAdmin
-- Database: u922228303_aapengineering
-- After import, delete install.php from server.

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `project_media`;
DROP TABLE IF EXISTS `projects`;
DROP TABLE IF EXISTS `enquiries`;
DROP TABLE IF EXISTS `gallery_items`;
DROP TABLE IF EXISTS `clients`;
DROP TABLE IF EXISTS `updates`;
DROP TABLE IF EXISTS `cities`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `migrations`;

SET FOREIGN_KEY_CHECKS=1;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `state` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `status` enum('upcoming','ongoing','completed') NOT NULL DEFAULT 'upcoming',
  `city_id` bigint unsigned DEFAULT NULL,
  `client_name` varchar(255) DEFAULT NULL,
  `project_type` varchar(255) DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` text,
  `cover_image_url` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`),
  KEY `projects_status_is_published_index` (`status`,`is_published`),
  KEY `projects_city_id_is_published_index` (`city_id`,`is_published`),
  CONSTRAINT `projects_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `project_media` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `type` enum('image','video_cdn','video_youtube') NOT NULL,
  `url` varchar(255) NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `project_media_project_id_foreign` (`project_id`),
  CONSTRAINT `project_media_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `enquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `project_interest` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `clients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `gallery_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `type` enum('image','video_cdn','video_youtube') NOT NULL DEFAULT 'image',
  `url` varchar(255) NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `updates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(500) DEFAULT NULL,
  `body` text,
  `cover_image_url` varchar(255) DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `updates_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Admin: admin@aapengineerings.com / Admin@123
INSERT INTO `users` (`name`,`email`,`password`,`created_at`,`updated_at`) VALUES
('AAP Admin','admin@aapengineerings.com','$2y$10$BAcFpdhSmLmO/3ygNxxCNOazddIEPvlw4A/eB8yqD7I1edJrg.gSO',NOW(),NOW());

INSERT INTO `cities` (`name`,`state`,`is_active`,`created_at`,`updated_at`) VALUES
('Pune','Maharashtra',1,NOW(),NOW()),
('Mumbai','Maharashtra',1,NOW(),NOW()),
('Nagpur','Maharashtra',1,NOW(),NOW()),
('Nashik','Maharashtra',1,NOW(),NOW()),
('Aurangabad','Maharashtra',1,NOW(),NOW());

INSERT INTO `projects` (`title`,`slug`,`status`,`city_id`,`client_name`,`project_type`,`short_description`,`description`,`cover_image_url`,`start_date`,`end_date`,`is_featured`,`is_published`,`sort_order`,`created_at`,`updated_at`) VALUES
('Industrial HT/LT Electrical Installation','industrial-htlt-electrical-installation','completed',1,'Precision Manufacturing Ltd','Industrial Electrical','Complete HT/LT distribution, panel installation and commissioning for a manufacturing plant.','AAP Engineerings delivered end-to-end electrical infrastructure for a new industrial facility.','https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1200&q=80','2026-02-28','2026-07-28',1,1,0,NOW(),NOW()),
('Commercial Building Power & Lighting','commercial-building-power-lighting','ongoing',2,'Skyline Developers','Commercial Fit-out','Full electrical package for a multi-floor commercial building including power, lighting and safety systems.','Ongoing turnkey electrical works for a commercial tower.','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1200&q=80','2026-03-28',NULL,1,1,1,NOW(),NOW()),
('Hospital Electrical Infrastructure Upgrade','hospital-electrical-infrastructure-upgrade','upcoming',3,'City Care Hospital','Healthcare Electrical','Upcoming upgrade of critical power systems, UPS coordination and distribution for a hospital campus.','Planned upgrade covering critical load segregation and phased execution.','https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1200&q=80','2026-04-28',NULL,1,1,2,NOW(),NOW()),
('Warehouse Electrical & Fire Alarm Works','warehouse-electrical-fire-alarm-works','completed',4,'LogiHub Warehousing','Warehouse Electrical','Complete electrical and fire alarm installation for a logistics warehouse.','Delivered power distribution, high-bay lighting, earthing and fire alarm system installation.','https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=1200&q=80','2026-05-28','2026-07-28',0,1,3,NOW(),NOW());

INSERT INTO `project_media` (`project_id`,`type`,`url`,`caption`,`sort_order`,`created_at`,`updated_at`) VALUES
(1,'image','https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1400&q=80','Site electrical works',0,NOW(),NOW()),
(1,'image','https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1400&q=80','Panel installation',1,NOW(),NOW()),
(1,'video_youtube','https://www.youtube.com/watch?v=aqz-KE-bpKQ','Project walkthrough',2,NOW(),NOW()),
(2,'image','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=1400&q=80','Commercial facade',0,NOW(),NOW());

INSERT INTO `clients` (`name`,`logo_url`,`industry`,`sort_order`,`is_active`,`created_at`,`updated_at`) VALUES
('Precision Manufacturing Ltd','https://images.unsplash.com/photo-1560179707-f14ea90d4564?auto=format&fit=crop&w=400&q=80','Manufacturing',0,1,NOW(),NOW()),
('Skyline Developers','https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?auto=format&fit=crop&w=400&q=80','Real Estate',1,1,NOW(),NOW()),
('City Care Hospital','https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=400&q=80','Healthcare',2,1,NOW(),NOW()),
('LogiHub Warehousing','https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=400&q=80','Logistics',3,1,NOW(),NOW());

INSERT INTO `gallery_items` (`title`,`type`,`url`,`category`,`caption`,`sort_order`,`is_active`,`created_at`,`updated_at`) VALUES
('Panel commissioning','image','https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1400&q=80','Industrial','Panel commissioning',0,1,NOW(),NOW()),
('Site cabling','image','https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1400&q=80','Industrial','Site cabling',1,1,NOW(),NOW()),
('Project walkthrough','video_youtube','https://www.youtube.com/watch?v=aqz-KE-bpKQ','Videos','Project walkthrough',4,1,NOW(),NOW());

INSERT INTO `updates` (`title`,`slug`,`excerpt`,`body`,`cover_image_url`,`published_at`,`is_published`,`created_at`,`updated_at`) VALUES
('AAP Engineerings expands industrial project capacity','aap-engineerings-expands-industrial-project-capacity','We have strengthened our execution team to take on larger HT/LT industrial scopes across Maharashtra.','AAP Engineerings continues to scale delivery capability for full electrical project packages.','https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=1200&q=80',NOW(),1,NOW(),NOW());
