-- MD Farma: hardening integritas konsultasi dan audit admin tunggal
-- Jalankan satu kali melalui phpMyAdmin pada database md_farma.
-- Aman dijalankan ulang: tabel memakai IF NOT EXISTS dan index diperiksa dahulu.

CREATE TABLE IF NOT EXISTS `consultation_status_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `consultation_id` BIGINT UNSIGNED NOT NULL,
  `admin_id` BIGINT UNSIGNED NULL,
  `previous_status` VARCHAR(20) NOT NULL,
  `new_status` VARCHAR(20) NOT NULL,
  `reason` TEXT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `consultation_status_logs_consultation_time_index` (`consultation_id`, `created_at`),
  CONSTRAINT `consultation_status_logs_consultation_id_foreign`
    FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_status_logs_admin_id_foreign`
    FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `consultation_access_logs` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `consultation_id` BIGINT UNSIGNED NOT NULL,
  `message_id` BIGINT UNSIGNED NULL,
  `archive_copy_request_id` BIGINT UNSIGNED NULL,
  `admin_id` BIGINT UNSIGNED NULL,
  `action` VARCHAR(50) NOT NULL,
  `metadata` JSON NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `consultation_access_logs_action_time_index` (`consultation_id`, `action`, `created_at`),
  KEY `consultation_access_logs_admin_time_index` (`admin_id`, `created_at`),
  CONSTRAINT `consultation_access_logs_consultation_id_foreign`
    FOREIGN KEY (`consultation_id`) REFERENCES `consultations` (`id`) ON DELETE CASCADE,
  CONSTRAINT `consultation_access_logs_message_id_foreign`
    FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consultation_access_logs_archive_copy_request_id_foreign`
    FOREIGN KEY (`archive_copy_request_id`) REFERENCES `consultation_archive_copy_requests` (`id`) ON DELETE SET NULL,
  CONSTRAINT `consultation_access_logs_admin_id_foreign`
    FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tambahkan unique index username hanya bila belum ada dan tidak ada username duplikat.
SET @admin_username_unique_exists := (
  SELECT COUNT(*)
  FROM information_schema.statistics
  WHERE table_schema = DATABASE()
    AND table_name = 'admins'
    AND non_unique = 0
    AND column_name = 'username'
);

SET @admin_username_duplicates := (
  SELECT COUNT(*)
  FROM (
    SELECT `username`
    FROM `admins`
    GROUP BY `username`
    HAVING COUNT(*) > 1
  ) AS duplicated_admin_usernames
);

SET @admin_unique_sql := IF(
  @admin_username_unique_exists = 0 AND @admin_username_duplicates = 0,
  'ALTER TABLE `admins` ADD UNIQUE INDEX `admins_username_unique` (`username`)',
  'SELECT "Unique index admin dilewati: index sudah ada atau username duplikat ditemukan." AS info'
);

PREPARE admin_unique_statement FROM @admin_unique_sql;
EXECUTE admin_unique_statement;
DEALLOCATE PREPARE admin_unique_statement;
