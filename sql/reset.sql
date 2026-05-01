-- reset.sql
-- Drops all YetiCave tables.
-- Be careful: this deletes all data.

USE yeticave;

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `bets`;
DROP TABLE IF EXISTS `lots`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;

SET FOREIGN_KEY_CHECKS = 1;