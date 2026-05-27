USE yeticave;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = utf8mb4_0900_ai_ci;

INSERT INTO `lots` (
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
          'assets/img/lot-1.jpg',
          10999,
          500,
          '2026-06-15'
      ),
      (
          2,
          1,
          'DC Ply Mens 2016/2017 Snowboard',
          'Мужской сноуборд DC Ply сезона 2016/2017. Хороший вариант для катания в парке и на трассе.',
          'assets/img/lot-2.jpg',
          159999,
          1000,
          '2026-06-16'
      ),
      (
          3,
          2,
          'Крепления Union Contact Pro 2015 года размер L/XL',
          'Крепления Union Contact Pro 2015 года, размер L/XL. Подходят для сноуборда и активного катания.',
          'assets/img/lot-3.jpg',
          8000,
          500,
          '2026-06-17'
      ),
      (
          4,
          3,
          'Ботинки для сноуборда DC Mutiny Charcoal',
          'Ботинки для сноуборда DC Mutiny Charcoal. Удобная модель для длительного катания.',
          'assets/img/lot-4.jpg',
          10999,
          500,
          '2026-06-18'
      ),
      (
          5,
          4,
          'Куртка для сноуборда DC Mutiny Charcoal',
          'Куртка для сноуборда DC Mutiny Charcoal. Подходит для зимнего спорта и активного отдыха.',
          'assets/img/lot-5.jpg',
          7500,
          500,
          '2026-06-19'
      ),
      (
          1,
          6,
          'Маска Oakley Canopy',
          'Маска Oakley Canopy для катания на сноуборде и лыжах. Защищает глаза от солнца, снега и ветра.',
          'assets/img/lot-6.jpg',
          5400,
          500,
          '2026-06-20'
      );
