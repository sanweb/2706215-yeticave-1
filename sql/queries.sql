-- Get all categories
SELECT `id`, `name`, `slug` FROM `categories`;

-- Get newest open lots
SELECT
  lots.`id`,
  lots.`title`,
  lots.`start_price`,
  lots.`image_url`,
  COALESCE(lot_bets.`max_amount`, lots.`start_price`) AS `price`,
  categories.`name` AS `category_name`
FROM `lots`
JOIN `categories` ON lots.`category_id` = categories.`id`
LEFT JOIN (
  SELECT `lot_id`, MAX(`amount`) AS `max_amount`
  FROM `bets`
  GROUP BY `lot_id`
) AS lot_bets ON lot_bets.`lot_id` = lots.`id`
WHERE lots.`expire_date` > CURRENT_DATE
ORDER BY lots.`created_at` DESC;

-- Get lot by ID with category name
SELECT
  lots.`id`,
  lots.`title`,
  lots.`description`,
  lots.`image_url`,
  lots.`start_price`,
  COALESCE(lot_bets.`max_amount`, lots.`start_price`) AS `price`,
  lots.`bet_step`,
  COALESCE(lot_bets.`max_amount`, lots.`start_price`) + lots.`bet_step` AS `min_bet`,
  lots.`expire_date`,
  categories.`name` AS `category_name`
FROM `lots`
JOIN `categories` ON lots.`category_id` = categories.`id`
LEFT JOIN (
  SELECT `lot_id`, MAX(`amount`) AS `max_amount`
  FROM `bets`
  GROUP BY `lot_id`
) AS lot_bets ON lot_bets.`lot_id` = lots.`id`
WHERE lots.`id` = 1;

-- Update lot title by ID
UPDATE `lots`
SET `title` = 'New lot title'
WHERE `id` = 1;

-- Get bets for lot by ID sorted by date
SELECT
  bets.`id`,
  bets.`user_id`,
  users.`name` AS `user_name`,
  bets.`lot_id`,
  bets.`amount`,
  bets.`created_at`
FROM `bets`
JOIN `users` ON bets.`user_id` = users.`id`
WHERE bets.`lot_id` = 1
ORDER BY bets.`created_at` DESC;
