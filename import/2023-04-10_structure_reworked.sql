SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Datenbank: `minitcg`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `members_settings`
--

CREATE TABLE `members_settings` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `language` varchar(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'de',
  `timezone` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Europe/Berlin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `members_settings`
--
ALTER TABLE `members_settings`
  ADD PRIMARY KEY (`member_id`);

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `members_settings`
--
ALTER TABLE `members_settings`
  ADD CONSTRAINT `members_settings_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
 Insert INTO members_settings (member_id) SELECT id from members;
 
 -- Column renaming
 
 ALTER TABLE members_online RENAME COLUMN member TO member_id;
 
 ALTER TABLE `trades` CHANGE `offerer` `offerer_id` INT(10) UNSIGNED NOT NULL, CHANGE `recipient` `recipient_id` INT(10) UNSIGNED NOT NULL;

COMMIT;