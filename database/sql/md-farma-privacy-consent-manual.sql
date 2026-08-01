-- Persetujuan privasi eksplisit untuk konsultasi MD Farma.
-- Jalankan satu kali melalui phpMyAdmin jika tidak memakai php artisan migrate.
-- Script dibuat idempotent dan aman dijalankan ulang.

SET @db_name = DATABASE();

SET @column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'consultations'
      AND COLUMN_NAME = 'privacy_consent_at'
);
SET @statement = IF(
    @column_exists = 0,
    'ALTER TABLE `consultations` ADD COLUMN `privacy_consent_at` TIMESTAMP NULL AFTER `jenis_konsultasi`',
    'SELECT ''privacy_consent_at sudah tersedia'' AS info'
);
PREPARE prepared_statement FROM @statement;
EXECUTE prepared_statement;
DEALLOCATE PREPARE prepared_statement;

SET @column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'consultations'
      AND COLUMN_NAME = 'privacy_policy_version'
);
SET @statement = IF(
    @column_exists = 0,
    'ALTER TABLE `consultations` ADD COLUMN `privacy_policy_version` VARCHAR(40) NULL AFTER `privacy_consent_at`',
    'SELECT ''privacy_policy_version sudah tersedia'' AS info'
);
PREPARE prepared_statement FROM @statement;
EXECUTE prepared_statement;
DEALLOCATE PREPARE prepared_statement;

SET @column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'consultations'
      AND COLUMN_NAME = 'privacy_consent_text'
);
SET @statement = IF(
    @column_exists = 0,
    'ALTER TABLE `consultations` ADD COLUMN `privacy_consent_text` TEXT NULL AFTER `privacy_policy_version`',
    'SELECT ''privacy_consent_text sudah tersedia'' AS info'
);
PREPARE prepared_statement FROM @statement;
EXECUTE prepared_statement;
DEALLOCATE PREPARE prepared_statement;

SET @column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'consultations'
      AND COLUMN_NAME = 'privacy_consent_ip_hash'
);
SET @statement = IF(
    @column_exists = 0,
    'ALTER TABLE `consultations` ADD COLUMN `privacy_consent_ip_hash` CHAR(64) NULL AFTER `privacy_consent_text`',
    'SELECT ''privacy_consent_ip_hash sudah tersedia'' AS info'
);
PREPARE prepared_statement FROM @statement;
EXECUTE prepared_statement;
DEALLOCATE PREPARE prepared_statement;

SET @column_exists = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = @db_name
      AND TABLE_NAME = 'consultations'
      AND COLUMN_NAME = 'privacy_consent_user_agent_hash'
);
SET @statement = IF(
    @column_exists = 0,
    'ALTER TABLE `consultations` ADD COLUMN `privacy_consent_user_agent_hash` CHAR(64) NULL AFTER `privacy_consent_ip_hash`',
    'SELECT ''privacy_consent_user_agent_hash sudah tersedia'' AS info'
);
PREPARE prepared_statement FROM @statement;
EXECUTE prepared_statement;
DEALLOCATE PREPARE prepared_statement;
