SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `cards` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner` int(10) UNSIGNED NOT NULL,
  `deck` int(10) UNSIGNED NOT NULL,
  `number` tinyint(4) NOT NULL,
  `name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('new','trade','keep','collect') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Erste Kategorie'),
(2, 'Zweite Kategorie');

CREATE TABLE `decks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deckname` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `status` enum('new','public') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new',
  `creator` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `decks_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `member` int(10) UNSIGNED NOT NULL,
  `deck` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `decks_subcategories` (
  `deck_id` int(10) UNSIGNED NOT NULL,
  `subcategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `games` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `member` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `level` (
  `id` tinyint(10) UNSIGNED NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `cards` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `level` (`id`, `level`, `name`, `cards`) VALUES
(1, 1, 'Level 1', 0),
(2, 2, 'Level 2', 50),
(3, 3, 'Level 3', 100),
(4, 4, 'Level 4', 150);

CREATE TABLE `members` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `info_text` text COLLATE utf8_unicode_ci,
  `info_text_html` text COLLATE utf8_unicode_ci,
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
  `join_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `login_date` timestamp NULL DEFAULT NULL,
  `status` enum('default','suspended') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'default',
  `ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `members` (`id`, `name`, `mail`, `password`, `info_text`, `info_text_html`, `level`, `join_date`, `login_date`, `status`, `ip`) VALUES
(1, 'Admin', 'admin@minitcg', '$2y$10$yq/CFMqDu8GVIK5F6QQ/7Oz5dUjdgsfirhOf0jaCh6.5Acrpt.Y2e', NULL, NULL, 1, '2018-02-20 07:40:09', '2019-01-10 23:46:01', 'default', '::1');

CREATE TABLE `members_online` (
  `member` int(10) UNSIGNED NOT NULL,
  `ip` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `members_rights` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `right_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `members_rights` (`member_id`, `right_id`) VALUES
(1, 1);

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender` int(10) UNSIGNED NOT NULL,
  `recipient` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('new','read') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `news_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL,
  `text_html` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `news_entries` (`id`, `date`, `author`, `title`, `text`, `text_html`) VALUES
(1, '2018-07-31 20:00:00', 1, 'Die erste News', 'Dieses TCG steht in den Startlöchern! Nicht mehr lange dann geht es los.\r\n\r\nDieser Text kann im Administrationsbereich bearbeitet, oder gelöscht werden.', '<p>Dieses TCG steht in den Startlöchern! Nicht mehr lange dann geht es los.</p>\n<p>Dieser Text kann im Administrationsbereich bearbeitet, oder gelöscht werden.</p>');

CREATE TABLE `rights` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(75) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `rights` (`id`, `name`, `description`) VALUES
(1, 'Admin', 'Alle Rechte'),
(2, 'CardCreator', 'Decks verwalten, News verwalten, Karten Updates verwalten'),
(3, 'ManageMembers', 'Mitgliedsdaten verwalten inkl. Karten gutschreiben'),
(4, 'EditSettings', 'Generelle App Einstellungen bearbeiten'),
(5, 'ManageCategories', 'Deck Kategorien verwalten'),
(6, 'ManageLevel', 'Level verwalten'),
(7, 'ManageNews', 'News Verwalten'),
(8, 'ManageUpdates', 'Karten Updates verwalten'),
(9, 'ManageRights', 'Nutzerrechte verwalten');

CREATE TABLE `settings` (
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `settings` (`name`, `value`, `description`) VALUES
('app_mail', 'mintcg@irgendwas.de', 'Kontakt Adresse des TCG'),
('app_name', 'miniTCG', 'Name des TCG'),
('cards_decksize', '6', 'Anzahl der Karten pro Deck'),
('cards_file_type', 'gif', 'Dateityp der Karten Bilder [ gif | jpg | png ]'),
('cards_master_template_height', '75', 'Höhe der Master Karte in Pixel'),
('cards_master_template_width', '150', 'Breite der Master Karte in Pixel'),
('cards_per_page', '35', 'Anzahl der Karten pro Seite in der Cardverwaltung und dem Mitgliedsprofil'),
('cards_startdeck_num', '10', 'Anzahl der Start-Karten mit für neue Mitglieder'),
('cards_template_height', '75', 'Höhe der normalen Karte in Pixel'),
('cards_template_width', '100', 'Breite der normalen Karte in Pixel'),
('date_format', 'd.m.Y', 'Formatierung des Datums'),
('deckpage_cards_per_row', '3', 'Karten pro Reihe auf der Deck Seite'),
('giftcards_for_master', '1', 'Anzahl geschenkter Karten für das Mastern eines Decks');

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `subcategories` (`id`, `name`, `category`) VALUES
(1, 'Unterkategorie Kat 1', 1),
(2, 'Unterkategorie Kat 2', 2);

CREATE TABLE `tradelog` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `member_id` int(10) UNSIGNED NOT NULL,
  `text` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `trades` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `offerer` int(10) UNSIGNED NOT NULL,
  `offered_card` int(10) UNSIGNED NOT NULL,
  `recipient` int(10) UNSIGNED NOT NULL,
  `requested_card` int(10) UNSIGNED NOT NULL,
  `text` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` enum('new','accepted','declined') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `updates` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('new','public') COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `updates_decks` (
  `update_id` int(11) UNSIGNED NOT NULL,
  `deck_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

CREATE TABLE `updates_members` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `update_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deck` (`deck`),
  ADD KEY `status` (`status`),
  ADD KEY `member` (`owner`);

ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

ALTER TABLE `decks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`deckname`),
  ADD KEY `creator` (`creator`);

ALTER TABLE `decks_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member` (`member`),
  ADD KEY `deck` (`deck`);

ALTER TABLE `decks_subcategories`
  ADD UNIQUE KEY `deck_id` (`deck_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member` (`member`);

ALTER TABLE `level`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level` (`level`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `cards` (`cards`);

ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`mail`),
  ADD KEY `level` (`level`);

ALTER TABLE `members_online`
  ADD PRIMARY KEY (`member`);

ALTER TABLE `members_rights`
  ADD UNIQUE KEY `member_right` (`member_id`,`right_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `right_id` (`right_id`);

ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender` (`sender`),
  ADD KEY `recipient` (`recipient`);

ALTER TABLE `news_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`);

ALTER TABLE `rights`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `category` (`category`);

ALTER TABLE `tradelog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tradelog_ibfk_1` (`member_id`);

ALTER TABLE `trades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requested_card` (`requested_card`),
  ADD KEY `recipient` (`recipient`),
  ADD KEY `offered_card` (`offered_card`),
  ADD KEY `offerer` (`offerer`),
  ADD KEY `status` (`status`);

ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

ALTER TABLE `updates_decks`
  ADD PRIMARY KEY (`update_id`,`deck_id`),
  ADD UNIQUE KEY `deck_id` (`deck_id`),
  ADD KEY `update_id` (`update_id`);

ALTER TABLE `updates_members`
  ADD UNIQUE KEY `member_id` (`member_id`,`update_id`),
  ADD KEY `update_id` (`update_id`);


ALTER TABLE `cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `decks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `decks_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `games`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `level`
  MODIFY `id` tinyint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `news_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

ALTER TABLE `rights`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

ALTER TABLE `tradelog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `trades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `updates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;


ALTER TABLE `cards`
  ADD CONSTRAINT `cards_ibfk_1` FOREIGN KEY (`deck`) REFERENCES `decks` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_ibfk_2` FOREIGN KEY (`owner`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `decks`
  ADD CONSTRAINT `decks_ibfk_1` FOREIGN KEY (`creator`) REFERENCES `members` (`id`) ON UPDATE CASCADE;

ALTER TABLE `decks_master`
  ADD CONSTRAINT `decks_master_ibfk_1` FOREIGN KEY (`member`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_master_ibfk_2` FOREIGN KEY (`deck`) REFERENCES `decks` (`id`) ON UPDATE CASCADE;

ALTER TABLE `decks_subcategories`
  ADD CONSTRAINT `decks_subcategories_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_subcategories_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON UPDATE CASCADE;

ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`member`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`level`) REFERENCES `level` (`id`);

ALTER TABLE `members_online`
  ADD CONSTRAINT `members_online_ibfk_1` FOREIGN KEY (`member`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `members_rights`
  ADD CONSTRAINT `members_rights_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `members_rights_ibfk_2` FOREIGN KEY (`right_id`) REFERENCES `rights` (`id`) ON UPDATE CASCADE;

ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`recipient`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `news_entries`
  ADD CONSTRAINT `news_entries_ibfk_1` FOREIGN KEY (`author`) REFERENCES `members` (`id`) ON UPDATE CASCADE;

ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

ALTER TABLE `tradelog`
  ADD CONSTRAINT `tradelog_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `trades`
  ADD CONSTRAINT `trades_ibfk_1` FOREIGN KEY (`offered_card`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trades_ibfk_2` FOREIGN KEY (`requested_card`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trades_ibfk_3` FOREIGN KEY (`offerer`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trades_ibfk_4` FOREIGN KEY (`recipient`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `updates_decks`
  ADD CONSTRAINT `updates_decks_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `updates_decks_ibfk_2` FOREIGN KEY (`update_id`) REFERENCES `updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `updates_members`
  ADD CONSTRAINT `updates_members_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `updates_members_ibfk_2` FOREIGN KEY (`update_id`) REFERENCES `updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
