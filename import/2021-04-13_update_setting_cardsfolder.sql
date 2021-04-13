-- updates für cards_folder setting
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `settings` (`name`, `value`, `description`) 
VALUES ('cards_folder', 'img/cards', 'Pfad an dem die Ordner mit den Kartenbildern liegen');

COMMIT;