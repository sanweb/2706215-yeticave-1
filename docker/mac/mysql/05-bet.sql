USE yeticave;

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;
SET collation_connection = utf8mb4_0900_ai_ci;

INSERT INTO `bets` (`user_id`, `lot_id`, `amount`) VALUES
    (2, 1, 11500),
    (3, 1, 12000);

-- Add bets for another lot
INSERT INTO `bets` (`user_id`, `lot_id`, `amount`) VALUES
    (1, 2, 161000),
    (4, 2, 162000);
