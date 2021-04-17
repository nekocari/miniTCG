-- updates enable messages between members
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

ALTER TABLE `messages` ADD `deleted` ENUM('sender','recipient') NULL DEFAULT NULL AFTER `status`;
ALTER TABLE `messages` CHANGE `sender` `sender_id` INT(10) UNSIGNED NULL DEFAULT NULL, CHANGE `recipient` `recipient_id` INT(10) UNSIGNED NOT NULL;

COMMIT;