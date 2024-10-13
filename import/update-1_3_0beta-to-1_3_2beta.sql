-- change date cols to use timestamp
ALTER TABLE `members` CHANGE `join_date` `join_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `decks_votes_upcoming` CHANGE `date` `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `decks_votes_upcoming` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `members_games` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `members_online` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `members_wishlist` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `messages` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `news_entries` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `shop_cards` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `tradelog` CHANGE `utc` `utc` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;
ALTER TABLE `trades` CHANGE `utc` `utc` TIMESTAMP NOT NULL;
ALTER TABLE `updates` CHANGE `utc` `utc` TIMESTAMP NOT NULL;

-- add new route for wishlist
INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('member_wishlist', 'member/wishlist.php', 'Member', 'wishlist', 'get|post', '0')