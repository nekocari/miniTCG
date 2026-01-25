SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Database: `mini_setup`
--

-- --------------------------------------------------------

--
-- Table structure for table `cards`
--

CREATE TABLE `cards` (
  `id` int(10) UNSIGNED NOT NULL,
  `owner` int(10) UNSIGNED NOT NULL,
  `deck` int(10) UNSIGNED NOT NULL,
  `number` tinyint(4) NOT NULL,
  `name` varchar(60) NOT NULL,
  `status_id` tinyint(3) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cards_stati`
--

CREATE TABLE `cards_stati` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'as displayed on page',
  `tradeable` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'open for trade offers?',
  `position` tinyint(3) UNSIGNED NOT NULL COMMENT 'defines the sorting of cards',
  `new` enum('0','1') NOT NULL DEFAULT '0',
  `collections` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'will collections be in here?',
  `public` enum('0','1') NOT NULL DEFAULT '1' COMMENT 'other members can see cards in here'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `cards_stati`
--

INSERT INTO `cards_stati` (`id`, `name`, `tradeable`, `position`, `new`, `collections`, `public`) VALUES
(1, 'New', '0', 1, '1', '0', '0'),
(2, 'Trade', '1', 2, '0', '0', '1'),
(3, 'Keep', '0', 3, '0', '0', '1'),
(4, 'Collect', '0', 4, '0', '1', '1');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, '1st Category'),
(2, '2nd Category'),
(3, '3rd Category');

-- --------------------------------------------------------

--
-- Table structure for table `decks`
--

CREATE TABLE `decks` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `deckname` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `description_html` text DEFAULT NULL,
  `status` enum('new','public') NOT NULL DEFAULT 'new',
  `type_id` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `size` smallint(3) UNSIGNED NOT NULL,
  `creator` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `decks_master`
--

CREATE TABLE `decks_master` (
  `id` int(10) UNSIGNED NOT NULL,
  `member` int(10) UNSIGNED NOT NULL,
  `deck` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `decks_subcategories`
--

CREATE TABLE `decks_subcategories` (
  `deck_id` int(10) UNSIGNED NOT NULL,
  `subcategory_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `decks_types`
--

CREATE TABLE `decks_types` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `size` tinyint(3) UNSIGNED NOT NULL,
  `cards_per_row` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `card_image_width` smallint(4) NOT NULL,
  `card_image_height` smallint(4) NOT NULL,
  `master_image_width` smallint(4) NOT NULL,
  `master_image_height` smallint(4) NOT NULL,
  `template_path` varchar(255) DEFAULT NULL,
  `filler_type` enum('identical','individual') NOT NULL DEFAULT 'identical',
  `filler_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `decks_types`
--

INSERT INTO `decks_types` (`id`, `name`, `size`, `cards_per_row`, `card_image_width`, `card_image_height`, `master_image_width`, `master_image_height`, `template_path`, `filler_type`, `filler_path`) VALUES
(1, 'default', 6, 3, 100, 90, 130, 100, NULL, 'identical', '_filler/default.gif'),
(2, 'puzzle', 6, 3, 110, 100, 120, 100, 'deck_type_puzzle.php', 'individual', '_filler/puzzle/'),
(3, 'special', 4, 2, 90, 125, 120, 100, 'deck_type_special.php', 'identical', '_filler/default.gif');

-- --------------------------------------------------------

--
-- Table structure for table `decks_votes_upcoming`
--

CREATE TABLE `decks_votes_upcoming` (
  `deck_id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

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
-- Dumping data for table `games_custom`
--

INSERT INTO `games_custom` (`id`, `settings_id`, `results`, `view_file_path`, `js_file_path`) VALUES
(1, 6, 'won=>win-card:1, lost=>lost', 'game/hangman.php', 'hangman.js'),
(2, 5, 'won=>win-card:1, tied=>win-money:50, lost=>lost', 'game/rock_paper_scissors.php', 'rock_paper_scissors.js');

-- --------------------------------------------------------

--
-- Table structure for table `games_lucky`
--

CREATE TABLE `games_lucky` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `settings_id` tinyint(3) UNSIGNED NOT NULL,
  `choices_json` text NOT NULL,
  `results_json` text NOT NULL,
  `choice_type` enum('text','image') NOT NULL DEFAULT 'text'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `games_lucky`
--

INSERT INTO `games_lucky` (`id`, `settings_id`, `choices_json`, `results_json`, `choice_type`) VALUES
(1, 2, '[\"Kopf\",\"Zahl\"]', '[\"win-card:1\",\"lost\"]', 'text'),
(2, 1, '[\"1\",\"2\",\"3\",\"4\",\"5\",\"6\",\"7\",\"8\",\"9\"]', '[\"win-card:1\",\"win-card:1\",\"win-card:2\",\"win-money:500\",\"win-card:2\",\"win-money:250\",\"lost\",\"lost\",\"lost\"]', 'text'),
(3, 4, '[\"public\\/img\\/games\\/poke\\/rattata.png\",\"public\\/img\\/games\\/poke\\/pikachu.png\",\"public\\/img\\/games\\/poke\\/jigglypuff.png\",\"public\\/img\\/games\\/poke\\/meowth.png\",\"public\\/img\\/games\\/poke\\/psyduck.png\"]', '[\"lost\",\"win-money:100\",\"win-money:150\",\"win-money:200\",\"win-money:250\"]', 'image');

-- --------------------------------------------------------

--
-- Table structure for table `games_settings`
--

CREATE TABLE `games_settings` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `game_key` varchar(150) NOT NULL,
  `status` enum('online','offline') NOT NULL DEFAULT 'online',
  `type` enum('custom','lucky') NOT NULL DEFAULT 'lucky',
  `wait_time` int(11) DEFAULT 15,
  `route_identifier` varchar(255) NOT NULL,
  `name_json` text NOT NULL,
  `description_json` text NOT NULL,
  `preview_img_path` varchar(255) DEFAULT NULL,
  `game_img_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `games_settings`
--

INSERT INTO `games_settings` (`id`, `game_key`, `status`, `type`, `wait_time`, `route_identifier`, `name_json`, `description_json`, `preview_img_path`, `game_img_path`) VALUES
(1, 'lucky_number', 'online', 'lucky', 5, 'game_lucky_number', '{\"de\":\"Gl\\u00fcckszahl\",\"en\":\"Lucky Number\"}', '{\"de\":\"W\\u00e4hle eine Zahl und mit Gl\\u00fcck gewinnst du eine oder sogar zwei Karten!\",\"en\":\"Pick your Lucky Number, and you might win up to two Random Cards!\"}', NULL, ''),
(2, 'head_or_tail', 'online', 'lucky', 3, 'game_head_or_tail', '{\"de\":\"Kopf oder Zahl\",\"en\":\"Head or Tail\"}', '{\"de\":\"Ich werfe eine M\\u00fcnze. Tippst das Ergebnis richtig, bekommst du eine Karte.\",\"en\":\"I\'ll throw a coin. If you guess the result right, you win a Random Card.\"}', NULL, ''),
(3, 'trade_in', 'online', 'custom', NULL, 'game_trade_in', '{\"de\":\"Tausch Mich\",\"en\":\"Trade In\"}', '{\"de\":\"Tausche eine doppelte Karte gegen eine Zufallskarte!\",\"en\":\"Trade in your duplicate Card for a Random Card!\"}', NULL, ''),
(4, 'poke', 'online', 'lucky', 2, 'game_default_lucky', '{\"de\":\"Lucky Pok\\u00e9\",\"en\":\"Lucky Pok\\u00e9\"}', '{\"de\":\"Schnapp dir ein Pokemon!  \\r\\n<br>\\r\\n<small>(images from <a href=\\\"https:\\/\\/www.flaticon.com\\/authors\\/roundicons-freebies\\\" target=\\\"_blank\\\">roundicons<\\/a>)<\\/small>\",\"en\":\"Catch a Pokemon!\\r\\n<br>\\r\\n<small>(images from <a href=\\\"https:\\/\\/www.flaticon.com\\/authors\\/roundicons-freebies\\\" target=\\\"_blank\\\">roundicons<\\/a>)<\\/small>\"}', NULL, NULL),
(5, 'rps', 'online', 'custom', 1, 'game_rps', '{\"de\":\"Schere Stein Papier\",\"en\":\"Rock Paper Scissors\"}', '{\"de\":\"Triff deine Wahl.\",\"en\":\"Make your choice.\"}', NULL, NULL),
(6, 'hangman', 'online', 'custom', 0, 'game_hangman', '{\"de\":\"Galgenm\\u00e4nnchen\",\"en\":\"Hangman\"}', '{\"de\":\"Kannst du das gesuchte Wort erraten?\",\"en\":\"Can you guess the missing word?\"}', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `level`
--

CREATE TABLE `level` (
  `id` tinyint(10) UNSIGNED NOT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `cards` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `level`
--

INSERT INTO `level` (`id`, `level`, `name`, `cards`) VALUES
(1, 1, 'Level 1', 0),
(2, 2, 'Level 2', 25),
(3, 3, 'Level 3', 50),
(4, 4, 'Level 4', 75),
(9, 5, 'Level 5', 100);

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(100) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `info_text` text DEFAULT NULL,
  `info_text_html` text DEFAULT NULL,
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `money` int(11) NOT NULL DEFAULT 0,
  `join_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `login_date` timestamp NULL DEFAULT NULL,
  `status` enum('pending','default','suspended') NOT NULL DEFAULT 'pending',
  `ip` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_activation_code`
--

CREATE TABLE `members_activation_code` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `code` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_games`
--

CREATE TABLE `members_games` (
  `id` int(10) UNSIGNED NOT NULL,
  `type` varchar(150) NOT NULL,
  `member` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_online`
--

CREATE TABLE `members_online` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `ip` varchar(50) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `utc` TIMESTAMP NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_rights`
--

CREATE TABLE `members_rights` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `right_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_settings`
--

CREATE TABLE `members_settings` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `language` varchar(3) NOT NULL DEFAULT 'de',
  `timezone` varchar(255) NOT NULL DEFAULT 'Europe/Berlin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `members_wishlist`
--

CREATE TABLE `members_wishlist` (
  `id` int(10) UNSIGNED NOT NULL,
  `member_id` int(10) UNSIGNED NOT NULL,
  `deck_id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(10) UNSIGNED DEFAULT NULL,
  `recipient_id` int(10) UNSIGNED NOT NULL,
  `text` text NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `utc` TIMESTAMP NOT NULL,
  `status` enum('new','read') NOT NULL DEFAULT 'new',
  `deleted` enum('sender','recipient') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `news_entries`
--

CREATE TABLE `news_entries` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL,
  `author` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `text_html` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE `rights` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(75) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rights`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `routing`
--

CREATE TABLE `routing` (
  `identifier` varchar(255) NOT NULL,
  `url` text NOT NULL,
  `controller` varchar(255) NOT NULL,
  `action` varchar(255) NOT NULL,
  `method` enum('get','post','get|post') NOT NULL DEFAULT 'get',
  `deletable` enum('0','1') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `routing`
--

INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES
('access_denied_authorization', 'access_denied.php', 'Pages', 'accessDenied', 'get', '0'),
('admin_card_status_add', 'admin/settings/card_status/add.php', 'Admin', 'addCardStatus', 'get|post', '0'),
('admin_card_status_delete', 'admin/settings/card_status/delete.php', 'Admin', 'deleteCardStatus', 'get|post', '0'),
('admin_card_status_index', 'admin/settings/card_status/index.php', 'Admin', 'cardStatus', 'get|post', '0'),
('admin_dashboard', 'admin/', 'Admin', 'dashboard', 'get', '0'),
('admin_deck_index', 'admin/decks/list.php', 'Deck', 'adminDecklist', 'get', '0'),
('admin_deck_type_add', 'admin/decks/types/add.php', 'DeckType', 'add', 'get|post', '0'),
('admin_deck_type_edit', 'admin/decks/types/edit.php', 'DeckType', 'edit', 'get|post', '0'),
('admin_deck_type_index', 'admin/decks/types/list.php', 'DeckType', 'list', 'get|post', '0'),
('admin_deck_type_template_edit', 'admin/decks/types/edit_template.php', 'DeckType', 'editTemplate', 'get|post', '0'),
('admin_games_add', 'admin/game_settings/add.php', 'Admin', 'addGame', 'get|post', '0'),
('admin_games_edit', 'admin/game_settings/edit.php', 'Admin', 'editGame', 'get|post', '0'),
('admin_games_index', 'admin/game_settings/index.php', 'Admin', 'games_index', 'get|post', '0'),
('admin_member_card', 'admin/members/membercard.php', 'Admin', 'membercard', 'get|post', '0'),
('admin_member_edit', 'admin/members/edit.php', 'Admin', 'editMember', 'get|post', '0'),
('admin_member_gift_cards', 'admin/members/gift_cards.php', 'Admin', 'giftCards', 'get|post', '0'),
('admin_member_gift_money', 'admin/members/gift_money.php', 'Admin', 'giftMoney', 'get|post', '0'),
('admin_member_index', 'admin/members/list.php', 'Admin', 'memberlist', 'get|post', '0'),
('admin_member_manage_rights', 'admin/members/manage_rights.php', 'Admin', 'manageRights', 'get|post', '0'),
('admin_member_reset_password', 'admin/members/reset_password.php', 'Admin', 'resetPassword', 'get|post', '0'),
('admin_member_search', 'admin/members/search.php', 'Admin', 'searchMember', 'get|post', '0'),
('admin_pages', 'admin/pages/list.php', 'Admin', 'pagesIndex', 'get|post', '0'),
('admin_pages_edit', 'admin/pages/edit.php', 'Admin', 'pagesEdit', 'get|post', '0'),
('admin_routes_add', 'admin/routes/add.php', 'Admin', 'addRoute', 'get|post', '0'),
('admin_routes_edit', 'admin/routes/edit.php', 'Admin', 'editRoute', 'get|post', '0'),
('admin_routes_index', 'admin/routes.php', 'Admin', 'routes', 'get|post', '0'),
('admin_settings', 'admin/settings.php', 'Admin', 'settings', 'get|post', '0'),
('admin_sql_import', 'admin/sql_import.php', 'Admin', 'importSql', 'get|post', '0'),
('card_replace_image', 'admin/deck/edit/replace_card.php', 'Card', 'replaceImage', 'get|post', '0'),
('card_search', 'cardsearch.php', 'Card', 'search', 'get|post', '0'),
('category_add', 'admin/category/add.php', 'Category', 'addCategory', 'get|post', '0'),
('category_edit', 'admin/category/edit.php', 'Category', 'editCategory', 'get|post', '0'),
('category_index', 'admin/category.php', 'Category', 'categories', 'get|post', '0'),
('deck_by_category', 'decks/category.php', 'Deck', 'category', 'get', '0'),
('deck_detail_page', 'decks/deck.php', 'Deck', 'deckpage', 'get|post', '0'),
('deck_edit', 'admin/deck/edit.php', 'Deck', 'deckEdit', 'get|post', '0'),
('deck_index', 'decks.php', 'Deck', 'index', 'get', '0'),
('deck_update', 'admin/deck/update.php', 'Update', 'updates', 'get|post', '0'),
('deck_update_add', 'admin/deck/update/add.php', 'Update', 'add', 'get|post', '0'),
('deck_update_edit', 'admin/deck/update/edit.php', 'Update', 'edit', 'get|post', '0'),
('deck_upload', 'admin/deck/upload.php', 'Deck', 'deckUpload', 'get|post', '0'),
('decks_list_upcoming', 'decks/upcoming.php', 'Deck', 'upcomingIndex', 'get', '0'),
('delete_account', 'member/delete_account.php', 'Login', 'deleteAccount', 'get|post', '0'),
('edit_userdata', 'member/userdata.php', 'Login', 'editUserdata', 'get|post', '0'),
('faq', 'faq.php', 'Pages', 'faq', 'get', '0'),
('game', 'games.php', 'Game', 'index', 'get', '0'),
('game_custom', 'games/game.php', 'Game', 'customGame', 'get|post', '0'),
('game_default_lucky', 'games/lucky_game.php', 'Game', 'customLucky', 'get|post', '0'),
('game_hangman', 'games/hangman.php', 'Game', 'hangman', 'get|post', '0'),
('game_head_or_tail', 'games/head_or_tail.php', 'Game', 'headOrTail', 'get|post', '0'),
('game_lucky_number', 'games/lucky_number.php', 'Game', 'luckyNumber', 'get|post', '0'),
('game_rps', 'games/rps.php', 'Game', 'rockPaperScissors', 'get|post', '0'),
('game_trade_in', 'games/trade_in.php', 'Game', 'tradeIn', 'get|post', '0'),
('homepage', '', 'Pages', 'home', 'get', '0'),
('imprint', 'imprint.php', 'Pages', 'imprint', 'get', '0'),
('level_add', 'admin/level/add.php', 'Level', 'addLevel', 'get|post', '0'),
('level_edit', 'admin/level/edit.php', 'Level', 'editLevel', 'get|post', '0'),
('level_index', 'admin/level.php', 'Level', 'level', 'get', '0'),
('login_activation', 'activation.php', 'Login', 'activate', 'get|post', '0'),
('login_error', 'error_login.php', 'Pages', 'notLoggedIn', 'get', '0'),
('lost_password', 'lost_password.php', 'Login', 'password', 'get|post', '0'),
('member_cardmanager', 'member/cardmanager.php', 'Cardmanager', 'cardmanager', 'get|post', '0'),
('member_cardupdate', 'member/cardupdate.php', 'Update', 'take', 'get|post', '0'),
('member_dashboard', 'member/dashboard.php', 'Login', 'dashboard', 'get', '0'),
('member_index', 'memberlist.php', 'Member', 'memberlist', 'get', '0'),
('member_mastercards', 'member/mastercards.php', 'Member', 'mastercards', 'get', '0'),
('member_wishlist', 'member/wishlist.php', 'Member', 'wishlist', 'get|post', '0'),
('member_profil', 'members/profil.php', 'Member', 'profil', 'get', '0'),
('messages_recieved', 'member/inbox.php', 'Message', 'received', 'get|post', '0'),
('messages_sent', 'member/outbox.php', 'Message', 'sent', 'get|post', '0'),
('messages_write', 'member/new_message.php', 'Message', 'write', 'get|post', '0'),
('news_add', 'admin/news/add.php', 'News', 'add', 'get|post', '0'),
('news_edit', 'admin/news/edit.php', 'News', 'edit', 'get|post', '0'),
('news_index', 'admin/news/list.php', 'News', 'index', 'get|post', '0'),
('news_link_update', 'admin/news/update.php', 'News', 'linkUpdate', 'get|post', '0'),
('not_found', 'error.php', 'Pages', 'notFound', 'get', '0'),
('pages', 'pages/([^/ .]+).php', 'Pages', 'view', 'get', '0'),
('shop', 'cardshop.php', 'Shop', 'shop', 'get|post', '0'),
('signin', 'signin.php', 'Login', 'signin', 'get|post', '0'),
('signout', 'signout.php', 'Login', 'signout', 'get', '0'),
('signup', 'signup.php', 'Login', 'signup', 'get|post', '0'),
('subcategory_add', 'admin/subcategory/add.php', 'Category', 'addSubcategory', 'get|post', '0'),
('subcategory_edit', 'admin/subcategory/edit.php', 'Category', 'editSubcategory', 'get|post', '0'),
('trade', 'member/trade.php', 'Trade', 'add', 'get|post', '0'),
('tradelog_member', 'member/tradelog.php', 'Tradelog', 'overview', 'get', '0'),
('trades_recieved', 'member/trades_recieved.php', 'Trade', 'recieved', 'get|post', '0'),
('trades_sent', 'member/trades_sent.php', 'Trade', 'sent', 'get|post', '0');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `name` varchar(150) NOT NULL,
  `value` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`name`, `value`, `description`) VALUES
('app_mail', 'mintcg@irgendwas.de', '{\"en\":\"Address for mailing\",\"de\":\"Adresse für Mails\"}'),
('app_meta_description', 'an automatic trading card game', '{\"en\":\"Page description for search engines\",\"de\":\"Seitenbeschreibung für Suchmaschinen\"}'),
('app_name', 'miniTCG', '{\"en\":\"Name this TCG\",\"de\":\"Name des TCG\"}'),
('app_theme', '1', '{\"en\":\"theme template folder\",\"de\":\"Theme Template Ordner\"}'),
('cards_decks_public', '0', '{\"en\":\"Cards publicly accessible? 1=yes 0=no\",\"de\":\"Karten für jedermann öffentlich zugänglich? 1=ja 0=nein\"}'),
('cards_decks_upcoming_public', '1', '{\"en\":\"Upcoming decks publicly accessible?  1=yes 0=no\",\"de\":\"Unveröffentlichte Decks sichtbar 1=ja 0=nein\"}'),
('cards_file_type', 'gif', '{\"en\":\"Filetype of card images [ gif | jpg | png ]\",\"de\":\"Dateityp der Karten Bilder [ gif | jpg | png ]\"}'),
('cards_folder', 'public/img/cards', '{\"en\":\"path to where the card image folders are located\",\"de\":\"Pfad an dem die Ordner mit den Kartenbildern liegen\"}'),
('cards_per_page', '35', '{\"en\":\"Number of cards per page displayed in cardmanager and member profil\",\"de\":\"Anzahl der Karten pro Seite in der Cardverwaltung und dem Mitgliedsprofil\"}'),
('cardmanager_preselection', '0', '{\"en\":\"Enable preselection of category in cardmanager 1=yes 0=no (not suitabel for some configurations)\",\"de\":\"Aktiviere Vorauswahl der Kategorie in Kartenverwaltung 1=ja 0=nein (nicht für jede Konfiguration geeignet)\"}'),
('cards_startdeck_num', '15', '{\"en\":\"Number of cards new members recieve when starting out\",\"de\":\"Anzahl der Start-Karten für neue Mitglieder\"}'),
('currency_icon_path', 'public/img/icons/mycurrency.png', '{\"en\":\"Path from root to image file for currency (optional)\",\"de\":\"Pfad vom Basispfad aus zur Grafik für Währung (optional)\"}'),
('currency_name', 'Taler', '{\"en\":\"Name your virtual currency\",\"de\":\"Name der virtuellen Währung\"}'),
('date_format', 'd.m.Y', '{\"en\":\"Default date format\",\"de\":\"Formatierung des Datums\"}'),
('date_time_format', 'd.m.Y  \\&\\b\\u\\l\\l\\; H:i', '{\"en\":\"Default date format with time\",\"de\":\"Formatierung von Datum mit Uhrzeit\"}'),
('level_badge_folder', 'public/img/level', '{\"en\":\"path to where the level badge image folder is located\",\"de\":\"Pfad zum Ordner mit den Level Badges\"}'),
('levelup_gift_cards', '3', '{\"en\":\"Number of cards a member will recieve for leveling up\",\"de\":\"Anzahl der Karten die ein Mitglied für den Levelaufstieg erhält\"}'),
('master_gift_cards', '2', '{\"en\":\"Number of cards a member will recieve for mastering a deck\",\"de\":\"Anzahl der Karten die ein Mitglied für das mastern eines Decks erhält\"}'),
('members_card_default_path', 'public/img/membercards/_default.gif', '{\"en\":\"Filepath in case of not existing membercards\",\"de\":\"Dateipfand für nicht existierende Membercards\"}'),
('members_card_folder', 'public/img/membercards', '{\"en\":\"path to where the membercard image folder is located\",\"de\":\"Pfad zum Ordner mit den Membercards\"}'),
('members_profil_public', '0', '{\"en\":\"Member profiles publicly accessible? 1=yes 0=no\",\"de\":\"Mitgliederprofile für jedermann öffentlich zugänglich? 1=ja 0=nein\"}'),
('shop_max_stock', '6', '{\"en\":\"Maximum number of cards in shop\",\"de\":\"maximale Anzahl Karten im Shop\"}'),
('shop_next_restock', '2024-10-06T10:40:03+00:00', '{\"en\":\"Next shop restock date\",\"de\":\"Nächste Auffüllung des Shops\"}'),
('shop_price_max', '250', '{\"en\":\"Maximum price for cards\",\"de\":\"Maximalpreis der Karten\"}'),
('shop_price_min', '100', '{\"en\":\"Minimum price for cards\",\"de\":\"Mindestpreis der Karten\"}'),
('shop_restock_minutes', '5', '{\"en\":\"Time between each restock\",\"de\":\"Wartezeit zwischen Auffüllungen im Shop\"}');

-- --------------------------------------------------------

--
-- Table structure for table `shop_cards`
--

CREATE TABLE `shop_cards` (
  `id` int(11) UNSIGNED NOT NULL,
  `deck_id` int(11) UNSIGNED NOT NULL,
  `number` tinyint(4) UNSIGNED NOT NULL DEFAULT 1,
  `name` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `utc` TIMESTAMP NOT NULL,
  `price` smallint(6) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `name`, `category`) VALUES
(1, 'Subcat A', 2),
(2, 'Subcat A', 1),
(3, 'Subcat B', 1),
(5, 'Subcat B', 2);

-- --------------------------------------------------------

--
-- Table structure for table `tradelog`
--

CREATE TABLE `tradelog` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  `member_id` int(10) UNSIGNED NOT NULL,
  `sys_msg_code` varchar(255) NOT NULL,
  `text` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trades`
--

CREATE TABLE `trades` (
  `id` int(10) UNSIGNED NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `utc` TIMESTAMP NOT NULL,
  `offerer_id` int(10) UNSIGNED NOT NULL,
  `offered_card` int(10) UNSIGNED NOT NULL,
  `recipient_id` int(10) UNSIGNED NOT NULL,
  `requested_card` int(10) UNSIGNED NOT NULL,
  `text` varchar(250) DEFAULT NULL,
  `status` enum('new','accepted','declined') NOT NULL DEFAULT 'new'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates`
--

CREATE TABLE `updates` (
  `id` int(11) UNSIGNED NOT NULL,
  `date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `utc` TIMESTAMP NOT NULL,
  `status` enum('new','public') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates_decks`
--

CREATE TABLE `updates_decks` (
  `update_id` int(11) UNSIGNED NOT NULL,
  `deck_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates_members`
--

CREATE TABLE `updates_members` (
  `member_id` int(10) UNSIGNED NOT NULL,
  `update_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `updates_news`
--

CREATE TABLE `updates_news` (
  `update_id` int(10) UNSIGNED NOT NULL,
  `news_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cards`
--
ALTER TABLE `cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deck` (`deck`),
  ADD KEY `member` (`owner`),
  ADD KEY `card_status` (`status_id`);

--
-- Indexes for table `cards_stati`
--
ALTER TABLE `cards_stati`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `decks`
--
ALTER TABLE `decks`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short` (`deckname`),
  ADD KEY `creator` (`creator`),
  ADD KEY `decks_types` (`type_id`);

--
-- Indexes for table `decks_master`
--
ALTER TABLE `decks_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member` (`member`),
  ADD KEY `deck` (`deck`);

--
-- Indexes for table `decks_subcategories`
--
ALTER TABLE `decks_subcategories`
  ADD UNIQUE KEY `deck_id` (`deck_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `decks_types`
--
ALTER TABLE `decks_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `decks_votes_upcoming`
--
ALTER TABLE `decks_votes_upcoming`
  ADD PRIMARY KEY (`deck_id`,`member_id`),
  ADD KEY `deck_id` (`deck_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `games_custom`
--
ALTER TABLE `games_custom`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_id` (`settings_id`);

--
-- Indexes for table `games_lucky`
--
ALTER TABLE `games_lucky`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_id` (`settings_id`);

--
-- Indexes for table `games_settings`
--
ALTER TABLE `games_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`game_key`),
  ADD KEY `route_identifier` (`route_identifier`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `level`
--
ALTER TABLE `level`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `level` (`level`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `cards` (`cards`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `email` (`mail`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `members_activation_code`
--
ALTER TABLE `members_activation_code`
  ADD PRIMARY KEY (`member_id`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `members_games`
--
ALTER TABLE `members_games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member` (`member`),
  ADD KEY `type` (`type`);

--
-- Indexes for table `members_online`
--
ALTER TABLE `members_online`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `members_rights`
--
ALTER TABLE `members_rights`
  ADD UNIQUE KEY `member_right` (`member_id`,`right_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `right_id` (`right_id`);

--
-- Indexes for table `members_settings`
--
ALTER TABLE `members_settings`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `members_wishlist`
--
ALTER TABLE `members_wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `member_id_2` (`member_id`,`deck_id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `deck_id` (`deck_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender` (`sender_id`),
  ADD KEY `recipient` (`recipient_id`);

--
-- Indexes for table `news_entries`
--
ALTER TABLE `news_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author` (`author`);

--
-- Indexes for table `rights`
--
ALTER TABLE `rights`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routing`
--
ALTER TABLE `routing`
  ADD PRIMARY KEY (`identifier`),
  ADD KEY `url` (`url`(1024)),
  ADD KEY `method` (`method`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `shop_cards`
--
ALTER TABLE `shop_cards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deck_id` (`deck_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`),
  ADD KEY `category` (`category`);

--
-- Indexes for table `tradelog`
--
ALTER TABLE `tradelog`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tradelog_ibfk_1` (`member_id`);

--
-- Indexes for table `trades`
--
ALTER TABLE `trades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `requested_card` (`requested_card`),
  ADD KEY `recipient` (`recipient_id`),
  ADD KEY `offered_card` (`offered_card`),
  ADD KEY `offerer` (`offerer_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `updates`
--
ALTER TABLE `updates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `updates_decks`
--
ALTER TABLE `updates_decks`
  ADD PRIMARY KEY (`update_id`,`deck_id`),
  ADD UNIQUE KEY `deck_id` (`deck_id`),
  ADD KEY `update_id` (`update_id`);

--
-- Indexes for table `updates_members`
--
ALTER TABLE `updates_members`
  ADD PRIMARY KEY (`member_id`,`update_id`),
  ADD KEY `update_id` (`update_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `updates_news`
--
ALTER TABLE `updates_news`
  ADD PRIMARY KEY (`news_id`),
  ADD UNIQUE KEY `update` (`update_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cards`
--
ALTER TABLE `cards`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cards_stati`
--
ALTER TABLE `cards_stati`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `decks`
--
ALTER TABLE `decks`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `decks_master`
--
ALTER TABLE `decks_master`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `decks_types`
--
ALTER TABLE `decks_types`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `games_custom`
--
ALTER TABLE `games_custom`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `games_lucky`
--
ALTER TABLE `games_lucky`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `games_settings`
--
ALTER TABLE `games_settings`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `level`
--
ALTER TABLE `level`
  MODIFY `id` tinyint(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members_games`
--
ALTER TABLE `members_games`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `members_wishlist`
--
ALTER TABLE `members_wishlist`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news_entries`
--
ALTER TABLE `news_entries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rights`
--
ALTER TABLE `rights`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shop_cards`
--
ALTER TABLE `shop_cards`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tradelog`
--
ALTER TABLE `tradelog`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trades`
--
ALTER TABLE `trades`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `updates`
--
ALTER TABLE `updates`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cards`
--
ALTER TABLE `cards`
  ADD CONSTRAINT `cards_deks` FOREIGN KEY (`deck`) REFERENCES `decks` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_members` FOREIGN KEY (`owner`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `cards_stati` FOREIGN KEY (`status_id`) REFERENCES `cards_stati` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `decks`
--
ALTER TABLE `decks`
  ADD CONSTRAINT `decks_creators` FOREIGN KEY (`creator`) REFERENCES `members` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_types` FOREIGN KEY (`type_id`) REFERENCES `decks_types` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `decks_master`
--
ALTER TABLE `decks_master`
  ADD CONSTRAINT `decks_master_ibfk_1` FOREIGN KEY (`member`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_master_ibfk_2` FOREIGN KEY (`deck`) REFERENCES `decks` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `decks_subcategories`
--
ALTER TABLE `decks_subcategories`
  ADD CONSTRAINT `decks_subcategories_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_subcategories_ibfk_2` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `decks_votes_upcoming`
--
ALTER TABLE `decks_votes_upcoming`
  ADD CONSTRAINT `decks_votes_upcoming_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `decks_votes_upcoming_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `games_custom`
--
ALTER TABLE `games_custom`
  ADD CONSTRAINT `games_custom_ibfk_1` FOREIGN KEY (`settings_id`) REFERENCES `games_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `games_lucky`
--
ALTER TABLE `games_lucky`
  ADD CONSTRAINT `games_lucky_ibfk_1` FOREIGN KEY (`settings_id`) REFERENCES `games_settings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`level`) REFERENCES `level` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `members_activation_code`
--
ALTER TABLE `members_activation_code`
  ADD CONSTRAINT `members_activation_code_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_games`
--
ALTER TABLE `members_games`
  ADD CONSTRAINT `members_games_ibfk_1` FOREIGN KEY (`member`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `members_games_ibfk_2` FOREIGN KEY (`type`) REFERENCES `games_settings` (`game_key`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_online`
--
ALTER TABLE `members_online`
  ADD CONSTRAINT `members_online_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_rights`
--
ALTER TABLE `members_rights`
  ADD CONSTRAINT `members_rights_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `members_rights_ibfk_2` FOREIGN KEY (`right_id`) REFERENCES `rights` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `members_settings`
--
ALTER TABLE `members_settings`
  ADD CONSTRAINT `members_settings_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `members_wishlist`
--
ALTER TABLE `members_wishlist`
  ADD CONSTRAINT `members_wishlist_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `members_wishlist_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `news_entries`
--
ALTER TABLE `news_entries`
  ADD CONSTRAINT `news_entries_ibfk_1` FOREIGN KEY (`author`) REFERENCES `members` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `shop_cards`
--
ALTER TABLE `shop_cards`
  ADD CONSTRAINT `shop_cards_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category`) REFERENCES `categories` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `tradelog`
--
ALTER TABLE `tradelog`
  ADD CONSTRAINT `tradelog_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trades`
--
ALTER TABLE `trades`
  ADD CONSTRAINT `trades_ibfk_1` FOREIGN KEY (`offered_card`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trades_ibfk_2` FOREIGN KEY (`requested_card`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trades_ibfk_3` FOREIGN KEY (`offerer_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trades_ibfk_4` FOREIGN KEY (`recipient_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `updates_decks`
--
ALTER TABLE `updates_decks`
  ADD CONSTRAINT `updates_decks_ibfk_1` FOREIGN KEY (`deck_id`) REFERENCES `decks` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `updates_decks_ibfk_2` FOREIGN KEY (`update_id`) REFERENCES `updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `updates_members`
--
ALTER TABLE `updates_members`
  ADD CONSTRAINT `updates_members_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `updates_members_ibfk_2` FOREIGN KEY (`update_id`) REFERENCES `updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `updates_news`
--
ALTER TABLE `updates_news`
  ADD CONSTRAINT `updates_news_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news_entries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `updates_news_ibfk_2` FOREIGN KEY (`update_id`) REFERENCES `updates` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
