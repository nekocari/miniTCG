-- add new meta field for better form rendering and validation
ALTER TABLE `settings` ADD `meta` TEXT NOT NULL DEFAULT '{\"type\":\"text\",\"allowed_values\":\"\"}' AFTER `description`;

-- add new settings to enable or disable preselected option in cardmanager select
INSERT INTO `settings` (`name`, `value`, `description`) VALUES
('cardmanager_preselection', '0', '{\"en\":\"Enable preselection of category in cardmanager 1=yes 0=no (not suitabel for some configurations)\",\"de\":\"Aktiviere Vorauswahl der Kategorie in Kartenverwaltung 1=ja 0=nein (nicht für jede Konfiguration geeignet)\"}');