-- Select database
USE yeticave;

-- Insert categories
INSERT INTO categories (`name`, `slug`) VALUES
  ('Доски и лыжи', 'boards'),
  ('Крепления', 'attachment'),
  ('Ботинки', 'boots'),
  ('Одежда', 'clothing'),
  ('Инструменты', 'tools'),
  ('Разное', 'other');

-- Insert Users with password 123456
INSERT INTO users (`email`, `name`, `password_hash`, `contact_info`) VALUES
  ('snow.rider@example.com', 'Snow Rider', '$2y$12$nnjrby075K.wxDCOXR4zx.h3y6qfHai7dmXeUhdV2sbDBrcWmteae', 'Telegram: @snow_rider'),
  ('ivan.snegov@example.com', 'Иван Снегов', '$2y$12$mh4QuaLUvnG9rPOCpgqWju8WcaelUHE1ZmWZdidLPlIpmIY9RSa/y', 'Telegram: @ivan_snowrider'),
  ('mountain.fox@example.com', 'Mountain Fox', '$2y$12$E5GSFnKywkqzmPoGUht.F.3Y1mkcdnRqmDrbZn2u2Q5jDOdRUfwhi', 'Telegram: @mountain_fox'),
  ('ice.panda@example.com', 'Ice Panda', '$2y$12$nBL26cMmLy7gQyWqbWB6guCqYa8taxlxOZJHmmrZRABsgou1RWfY.', 'Telegram: @ice_panda'),
  ('egor.yeti@example.com', 'Егор Йети', '$2y$12$kbCwgNqx8EEHtIkpesNZNONSt1IPD6u5jEwVaevT/.Oqsx9ALbP/S', 'Telegram: @yeti_master');

-- Insert lots
INSERT INTO lots (
  `author_id`,
  `category_id`,
  `title`,
  `description`,
  `image_url`,
  `start_price`,
  `bet_step`,
  `expire_date`
) VALUES
  (
    1,
    1,
    '2014 Rossignol District Snowboard',
    'Сноуборд Rossignol District 2014 года. Подходит для начинающих и уверенных райдеров.',
    'img/lot-1.jpg',
    10999,
    500,
    '2026-05-15'
  ),
  (
    2,
    1,
    'DC Ply Mens 2016/2017 Snowboard',
    'Мужской сноуборд DC Ply сезона 2016/2017. Хороший вариант для катания в парке и на трассе.',
    'img/lot-2.jpg',
    159999,
    1000,
    '2026-05-16'
  ),
  (
    3,
    2,
    'Крепления Union Contact Pro 2015 года размер L/XL',
    'Крепления Union Contact Pro 2015 года, размер L/XL. Подходят для сноуборда и активного катания.',
    'img/lot-3.jpg',
    8000,
    500,
    '2026-05-17'
  ),
  (
    4,
    3,
    'Ботинки для сноуборда DC Mutiny Charcoal',
    'Ботинки для сноуборда DC Mutiny Charcoal. Удобная модель для длительного катания.',
    'img/lot-4.jpg',
    10999,
    500,
    '2026-05-18'
  ),
  (
    5,
    4,
    'Куртка для сноуборда DC Mutiny Charcoal',
    'Куртка для сноуборда DC Mutiny Charcoal. Подходит для зимнего спорта и активного отдыха.',
    'img/lot-5.jpg',
    7500,
    500,
    '2026-05-19'
  ),
  (
    1,
    6,
    'Маска Oakley Canopy',
    'Маска Oakley Canopy для катания на сноуборде и лыжах. Защищает глаза от солнца, снега и ветра.',
    'img/lot-6.jpg',
    5400,
    500,
    '2026-05-20'
  );

-- Add bets for a lot
INSERT INTO bets (`user_id`, `lot_id`, `amount`) VALUES
  (2, 1, 11500),
  (3, 1, 12000);

-- Add bets for another lot
INSERT INTO bets (`user_id`, `lot_id`, `amount`) VALUES
  (1, 2, 161000),
  (4, 2, 162000);
