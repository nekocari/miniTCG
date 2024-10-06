--
-- Table structure for table `games_custom`
--

CREATE TABLE `games_custom` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `settings_id` tinyint(3) UNSIGNED NOT NULL,
  `results` text NOT NULL,
  `view_file_path` varchar(255) NOT NULL,
  `js_file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- data for table `games_custom`
--

INSERT INTO `games_custom` (`id`, `settings_id`, `results`, `view_file_path`, `js_file_path`) VALUES
(1, (SELECT id from games_settings WHERE game_key = 'hangman'), 'won=>win-card:1, lost=>lost', 'game/hangman.php', 'hangman.js'),
(2, (SELECT id from games_settings WHERE game_key = 'rps'), 'won=>win-card:1, tied=>win-money:50, lost=>lost', 'game/rock_paper_scissors.php', 'rock_paper_scissors.js');

--
-- Indexes for table `games_custom`
--
ALTER TABLE `games_custom`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_id` (`settings_id`);

--
-- AUTO_INCREMENT for table `games_custom`
--
ALTER TABLE `games_custom`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for table `games_custom`
--
ALTER TABLE `games_custom`
  ADD CONSTRAINT `games_custom_ibfk_1` FOREIGN KEY (`settings_id`) REFERENCES `games_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
  
--
-- new ROUTE custom games
--
INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('game_custom', 'games/game.php', 'Game', 'customGame', 'get|post', '0');