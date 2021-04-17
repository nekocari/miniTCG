-- updates authentication on sign up
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

ALTER TABLE `members` CHANGE `status` `status` ENUM('pending','default','suspended') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'pending';

--
-- Tabellenstruktur für Tabelle `members_activation_code`
--

CREATE TABLE IF NOT EXISTS `members_activation_code` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`member_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Constraints der Tabelle `members_activation_code`
--
ALTER TABLE `members_activation_code`
  ADD CONSTRAINT `members_activation_code_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;