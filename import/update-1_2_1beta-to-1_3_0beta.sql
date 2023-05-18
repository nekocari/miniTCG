
-- new game -> hangman
INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('games_hangman', 'games/hangman.php', 'Game', 'hangman', 'get|post', '0');
INSERT INTO `games_settings` (`id`, `game_key`, `status`, `type`, `wait_time`, `route_identifier`, `name_json`, `description_json`, `preview_img_path`, `game_img_path`) VALUES (NULL, 'hangman', 'online', 'custom', 15, 'games_hangman', '{\"de\":\"Galgenm\\u00e4nnchen\",\"en\":\"Hangman\"}', '{\"de\":\"Kannst du das gesuchte Wort erraten?\",\"en\":\"Can you guess the missing word?\"}', NULL, NULL);