SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

INSERT INTO `settings` (`name`, `value`, `description`) VALUES ('levelup_bonus_cards', '2', 'Anzahl der Bonuskarten für ein LevelUp');

COMMIT;