-- Location Tracker Database Schema
-- Import this file to phpMyAdmin in cPanel

-- Create Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `phone` VARCHAR(20) NOT NULL UNIQUE,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100),
  `password` VARCHAR(255) NOT NULL,
  `pin_code` VARCHAR(6),
  `role` ENUM('user', 'admin', 'manager') DEFAULT 'user',
  `status` ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
  `avatar` VARCHAR(255),
  `two_factor_enabled` BOOLEAN DEFAULT FALSE,
  `two_factor_secret` VARCHAR(255),
  `last_login` DATETIME,
  `login_attempts` INT DEFAULT 0,
  `locked_until` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX `phone_idx` (`phone`),
  INDEX `email_idx` (`email`),
  INDEX `status_idx` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Devices Table
CREATE TABLE IF NOT EXISTS `devices` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `device_name` VARCHAR(100) NOT NULL,
  `device_id` VARCHAR(255) UNIQUE,
  `device_type` ENUM('mobile', 'web', 'gps_tracker') DEFAULT 'mobile',
  `imei` VARCHAR(20),
  `os_type` VARCHAR(50),
  `os_version` VARCHAR(50),
  `app_version` VARCHAR(20),
  `status` ENUM('active', 'inactive', 'lost', 'offline') DEFAULT 'inactive',
  `battery_level` INT,
  `battery_status` VARCHAR(50),
  `last_seen` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `status_idx` (`status`),
  INDEX `device_id_idx` (`device_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Locations Table
CREATE TABLE IF NOT EXISTS `locations` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `device_id` INT,
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `accuracy` DECIMAL(10, 2),
  `altitude` DECIMAL(10, 2),
  `speed` DECIMAL(10, 2),
  `bearing` DECIMAL(5, 2),
  `address` TEXT,
  `city` VARCHAR(100),
  `province` VARCHAR(100),
  `country` VARCHAR(100),
  `zip_code` VARCHAR(10),
  `location_type` ENUM('gps', 'network', 'manual') DEFAULT 'gps',
  `is_geofence_violation` BOOLEAN DEFAULT FALSE,
  `is_moving` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`device_id`) REFERENCES `devices`(`id`) ON DELETE SET NULL,
  INDEX `user_id_idx` (`user_id`),
  INDEX `created_at_idx` (`created_at`),
  INDEX `latitude_longitude_idx` (`latitude`, `longitude`),
  SPATIAL INDEX `location_spatial_idx` (POINT(latitude, longitude))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Geofence Table
CREATE TABLE IF NOT EXISTS `geofences` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `latitude` DECIMAL(10, 8) NOT NULL,
  `longitude` DECIMAL(11, 8) NOT NULL,
  `radius` INT NOT NULL DEFAULT 100, -- dalam meter
  `type` ENUM('home', 'work', 'school', 'custom') DEFAULT 'custom',
  `alert_type` ENUM('both', 'entry', 'exit') DEFAULT 'both',
  `is_active` BOOLEAN DEFAULT TRUE,
  `notify_email` BOOLEAN DEFAULT TRUE,
  `notify_sms` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Geofence Alerts Table
CREATE TABLE IF NOT EXISTS `geofence_alerts` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `geofence_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `alert_type` ENUM('entry', 'exit') NOT NULL,
  `latitude` DECIMAL(10, 8),
  `longitude` DECIMAL(11, 8),
  `is_read` BOOLEAN DEFAULT FALSE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`geofence_id`) REFERENCES `geofences`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `is_read_idx` (`is_read`),
  INDEX `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Activity Log Table
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `action` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `ip_address` VARCHAR(45),
  `user_agent` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create API Keys Table
CREATE TABLE IF NOT EXISTS `api_keys` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `api_key` VARCHAR(255) NOT NULL UNIQUE,
  `api_secret` VARCHAR(255) NOT NULL,
  `name` VARCHAR(100),
  `description` TEXT,
  `rate_limit` INT DEFAULT 100, -- requests per hour
  `is_active` BOOLEAN DEFAULT TRUE,
  `last_used` DATETIME,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `api_key_idx` (`api_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Sharing Table (untuk share lokasi dengan user lain)
CREATE TABLE IF NOT EXISTS `location_sharing` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `owner_id` INT NOT NULL,
  `shared_with_id` INT NOT NULL,
  `share_type` ENUM('full', 'real-time', 'history-only') DEFAULT 'full',
  `permission` ENUM('view', 'view-export', 'manage') DEFAULT 'view',
  `expiry_date` DATETIME,
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`owner_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`shared_with_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `owner_id_idx` (`owner_id`),
  INDEX `shared_with_id_idx` (`shared_with_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Notifications Table
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` BIGINT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `title` VARCHAR(255) NOT NULL,
  `message` TEXT,
  `type` ENUM('alert', 'info', 'warning', 'error') DEFAULT 'info',
  `related_to` VARCHAR(50), -- 'geofence', 'device', 'battery', dll
  `related_id` INT,
  `is_read` BOOLEAN DEFAULT FALSE,
  `action_url` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  INDEX `user_id_idx` (`user_id`),
  INDEX `is_read_idx` (`is_read`),
  INDEX `created_at_idx` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `user_id` INT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT,
  `data_type` ENUM('string', 'boolean', 'integer', 'json') DEFAULT 'string',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_setting` (`user_id`, `setting_key`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user
INSERT INTO `users` (`phone`, `name`, `email`, `password`, `role`, `status`) 
VALUES ('+62812345678', 'Administrator', 'admin@example.com', '$2y$12$abcdefghijklmnopqrstuvwxyz', 'admin', 'active');

-- Create indexes untuk performa
ALTER TABLE `locations` ADD INDEX `idx_user_device_date` (`user_id`, `device_id`, `created_at`);
ALTER TABLE `geofence_alerts` ADD INDEX `idx_geofence_date` (`geofence_id`, `created_at`);

-- Backup suggestion
-- Jalankan backup reguler dengan command: mysqldump -u user -p location_tracker > backup_$(date +%Y%m%d_%H%M%S).sql
