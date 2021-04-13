-- updates für seachcards
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `settings` (`name`, `value`, `description`) 
VALUES ('card_filler_general_image', 'img/searchcards/searchcard.gif', 'Pfad vom Basispfad zur Grafik für gesuchte Karten'), 
	('card_filler_puzzle_folder', 'img/searchcards/puzzle/', 'Pfad vom Basispfad zum Ordner mit Grafiken für gesuchte Puzzle Karten<br>(Benennung: 1.[Dateityp der Karten] usw.)'),
	('card_filler_use_puzzle', '0', 'Speziellen Filler für Puzzle Decks verwenden? [1=Ja|0=Nein]');

COMMIT;