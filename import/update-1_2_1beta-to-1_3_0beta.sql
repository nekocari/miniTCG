
-- new game -> hangman
INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('game_hangman', 'games/hangman.php', 'Game', 'hangman', 'get|post', '0');
INSERT INTO `games_settings` (`id`, `game_key`, `status`, `type`, `wait_time`, `route_identifier`, `name_json`, `description_json`, `preview_img_path`, `game_img_path`) VALUES (NULL, 'hangman', 'online', 'custom', 15, 'game_hangman', '{\"de\":\"Galgenm\\u00e4nnchen\",\"en\":\"Hangman\"}', '{\"de\":\"Kannst du das gesuchte Wort erraten?\",\"en\":\"Can you guess the missing word?\"}', NULL, NULL);

-- file editing pages
INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('admin_pages', 'admin/pages/list.php', 'Admin', 'pagesIndex', 'get', '0');
INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('admin_pages_edit', 'admin/pages/edit.php', 'Admin', 'pagesEdit', 'get|post', '0');

-- update for design switch
UPDATE `settings` SET `value` = '1', `description` = '{\"en\":\"theme template folder\",\"de\":\"Theme Template Ordner\"}' WHERE `settings`.`name` = 'app_theme';