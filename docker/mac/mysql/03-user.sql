USE yeticave;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = utf8mb4_0900_ai_ci;

INSERT INTO `users` (`email`, `name`, `password_hash`, `contact_info`) VALUES
    (
     'snow.rider@example.com',
     'Snow Rider',
     '$2y$12$nnjrby075K.wxDCOXR4zx.h3y6qfHai7dmXeUhdV2sbDBrcWmteae',
     'Telegram: @snow_rider'
    ),
    (
     'ivan.snegov@example.com',
     'Иван Снегов',
     '$2y$12$mh4QuaLUvnG9rPOCpgqWju8WcaelUHE1ZmWZdidLPlIpmIY9RSa/y',
     'Telegram: @ivan_snowrider'
    ),
    (
     'mountain.fox@example.com',
     'Mountain Fox',
     '$2y$12$E5GSFnKywkqzmPoGUht.F.3Y1mkcdnRqmDrbZn2u2Q5jDOdRUfwhi',
     'Telegram: @mountain_fox'
    ),
    (
     'ice.panda@example.com',
     'Ice Panda',
     '$2y$12$nBL26cMmLy7gQyWqbWB6guCqYa8taxlxOZJHmmrZRABsgou1RWfY.',
     'Telegram: @ice_panda'
    ),
    (
     'egor.yeti@example.com',
     'Егор Йети',
     '$2y$12$kbCwgNqx8EEHtIkpesNZNONSt1IPD6u5jEwVaevT/.Oqsx9ALbP/S',
     'Telegram: @yeti_master'
    );
