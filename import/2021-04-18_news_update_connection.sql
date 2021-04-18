-- updates authentication on sign up
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
--
-- Tabellenstruktur für Tabelle `update_news`
--

CREATE TABLE IF NOT EXISTS `updates_news` (
  `update_id` int(10) UNSIGNED NOT NULL,
  `news_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`news_id`),
  UNIQUE KEY `update` (`update_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Constraints der Tabelle `updates_news`
--
ALTER TABLE `updates_news` ADD FOREIGN KEY (`news_id`) REFERENCES `news_entries`(`id`) ON DELETE CASCADE ON UPDATE CASCADE; 
ALTER TABLE `updates_news` ADD FOREIGN KEY (`update_id`) REFERENCES `updates`(`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;