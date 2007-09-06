--
-- Ajax chat table
--

CREATE TABLE `chat` (
  `id` mediumint(9) NOT NULL auto_increment,
  `time` varchar(19) character set utf8 collate utf8_unicode_ci NOT NULL,
  `name` tinytext character set utf8 collate utf8_unicode_ci NOT NULL,
  `text` text character set utf8 collate utf8_unicode_ci NOT NULL,
  `uid` mediumint(9) NOT NULL,
  UNIQUE KEY `id` (`id`)
) TYPE=MyISAM;


-- 
-- Dumping data for table `chat`
-- 

INSERT INTO `chat` (`id`, `time`, `name`, `text`, `uid`) VALUES 
(1, '11/07/2007 19:24:59', 'miskotes', '[b]bold[/b] # [i]italic[/i] # [u]underline[/u] # fuck', 2),
(2, '19/07/2007 03:14:47', 'cobracrk', 'seems not xss vulnerable', 7),
(3, '19/07/2007 14:30:37', 'lupin', 'cool, but i don''t really like the refresh ;)', 8);


--
--  Ajax shout block
--

INSERT INTO {$db_prefix}blocks VALUES (20, 'ajax_shoutbox', 'c', 1, 1, 'BLOCK_SHOUTBOX', 'no');

--
-- End
--