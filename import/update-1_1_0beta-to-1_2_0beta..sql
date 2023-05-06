--
-- Update from 1.1.0 beta to 1.2.0 beta
--

START TRANSACTION;


-- redirect for pages 

INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('pages', 'pages/([^\/ .]+).php', 'Pages', 'view', 'get', '0');


-- update games_settings table

ALTER TABLE `games_settings` ADD `status` ENUM('online','offline') NOT NULL DEFAULT 'online' AFTER `game_key`, ADD INDEX `status` (`status`);


-- rps game

INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('game_rps', 'games/rps.php', 'Game', 'rockPaperScissors', 'get|post', '0');
INSERT INTO `games_settings` (`id`, `game_key`, `status`, `type`, `wait_time`, `route_identifier`, `name_json`, `description_json`, `preview_img_path`, `game_img_path`) VALUES (NULL, 'rps', 'online', 'custom', '15', 'game_rps', '{\"de\":\"Schere Stein Papier\",\"en\":\"Rock Paper Scissors\"}', '{\"de\":\"Triff deine Wahl.\",\"en\":\"Make your choice.\"}', NULL, NULL);


COMMIT;