CREATE DATABASE IF NOT EXISTS yeticave
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_0900_ai_ci;

-- utf8mb4_0900_ai_ci is available in MySQL 8.0 and higher.
-- For MySQL 5.7, use:
-- DEFAULT COLLATE utf8mb4_unicode_ci;

USE yeticave;

-- Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(255) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `contact_info` TEXT NULL DEFAULT NULL,

  -- created_at is filled by MySQL when the record is created
  -- updated_at is updated by the application when the record changes
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_0900_ai_ci;

-- Lot categories
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `slug` VARCHAR(64) NOT NULL,

  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_categories_name` (`name`),
  UNIQUE KEY `uq_categories_slug` (`slug`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_0900_ai_ci;

-- Lots
CREATE TABLE IF NOT EXISTS `lots` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,

  -- The winning bet is stored here after the lot expires.
  -- Foreign key is intentionally not added at this stage.
  `winner_bet_id` INT UNSIGNED NULL DEFAULT NULL,

  `title` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `image_url` VARCHAR(255) NOT NULL,

  -- Prices are stored as integer values
  `start_price` INT UNSIGNED NOT NULL,
  `bet_step` INT UNSIGNED NOT NULL,

  -- According to the spec, the expiration date is stored without time
  `expire_date` DATE NOT NULL,

  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,

  PRIMARY KEY (`id`),

  -- Indexes for filtering lots
  KEY `idx_lots_author_id` (`author_id`),
  KEY `idx_lots_category_id` (`category_id`),
  KEY `idx_lots_winner_bet_id` (`winner_bet_id`),
  KEY `idx_lots_expire_date` (`expire_date`),
  KEY `idx_lots_created_at` (`created_at`),

  -- Full-text index for searching lots by title and description
  FULLTEXT KEY `ft_lots_title_description` (`title`, `description`),

  CONSTRAINT `fk_lots_author`
    FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),

  CONSTRAINT `fk_lots_category`
    FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_0900_ai_ci;

-- User bets for lots
CREATE TABLE IF NOT EXISTS `bets` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` INT UNSIGNED NOT NULL,
  `lot_id` INT UNSIGNED NOT NULL,

  -- Bet amount is stored as an integer value
  `amount` INT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`id`),

  -- Index for "My bets" page
  KEY `idx_bets_user_created_at` (`user_id`, `created_at`),

  -- Index for showing recent bets for a selected lot
  KEY `idx_bets_lot_created_at` (`lot_id`, `created_at`),

  CONSTRAINT `fk_bets_user`
    FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),

  CONSTRAINT `fk_bets_lot`
    FOREIGN KEY (`lot_id`) REFERENCES `lots` (`id`)
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_0900_ai_ci;
