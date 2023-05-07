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


-- membercards

INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('admin_member_card', 'admin/members/membercard.php', 'Admin', 'membercard', 'get|post', '0');
INSERT INTO `settings` (`name`, `value`, `description`) VALUES ('members_card_default_path', 'public/img/membercards/_default.gif', '{\"en\":\"Filepath in case of not existing membercards\",\"de\":\"Dateipfand für nicht existierende Membercards\"}');
INSERT INTO `settings` (`name`, `value`, `description`) VALUES ('members_card_folder', 'public/img/membercards', '{\"en\":\"path to where the membercard image folder is located\",\"de\":\"Pfad zum Ordner mit den Membercards\"}');

-- level badges --

INSERT INTO `settings` (`name`, `value`, `description`) VALUES ('level_badge_folder', 'public/img/level', '{\"en\":\"path to where the level badge image folder is located\",\"de\":\"Pfad zum Ordner mit den Level Badges\"}');

COMMIT;