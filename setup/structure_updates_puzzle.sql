SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

ALTER TABLE `decks`  ADD `type` ENUM('default','puzzle') NOT NULL DEFAULT 'default'  AFTER `status`;

COMMIT;