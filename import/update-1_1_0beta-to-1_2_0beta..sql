--
-- Update from 1.1.0 beta to 1.2.0 beta
--

-- redirect for pages 

INSERT INTO `routing` (`identifier`, `url`, `controller`, `action`, `method`, `deletable`) VALUES ('pages', 'pages/([^\/ .]+).php', 'Pages', 'view', 'get', '0');