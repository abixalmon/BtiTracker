
--- drop blocks table and import new

DROP TABLE IF EXISTS `btit_blocks`;
CREATE TABLE `btit_blocks` (
  `blockid` int(11) unsigned NOT NULL auto_increment,
  `content` varchar(255) NOT NULL default '',
  `position` char(1) NOT NULL default '',
  `sortid` int(11) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned default NULL,
  `title` varchar(40) NOT NULL,
  `cache` enum('yes','no') NOT NULL,
  `minclassview` int(11) NOT NULL default '0',
  `maxclassview` int(11) NOT NULL default '8',
  PRIMARY KEY  (`blockid`),
  KEY `position` (`position`)
) ENGINE=MyISAM;

INSERT INTO `btit_blocks` (`blockid`, `content`, `position`, `sortid`, `status`, `title`, `cache`, `minclassview`, `maxclassview`) VALUES
(1, 'menu', 'r', 5, 1, 'BLOCK_MENU', 'no', 3, 8),
(2, 'clock', 'r', 2, 1, 'BLOCK_CLOCK', 'no', 3, 8),
(3, 'forum', 'l', 2, 1, 'BLOCK_FORUM', 'no', 3, 8),
(4, 'lastmember', 'l', 1, 1, 'BLOCK_LASTMEMBER', 'no', 3, 8),
(6, 'trackerinfo', 'l', 6, 1, 'BLOCK_INFO', 'no', 3, 8),
(7, 'user', 'r', 4, 1, 'BLOCK_USER', 'no', 3, 8),
(8, 'online', 'b', 0, 1, 'BLOCK_ONLINE', 'no', 3, 8),
(10, 'toptorrents', 'c', 5, 1, 'BLOCK_TOPTORRENTS', 'no', 3, 8),
(11, 'lasttorrents', 'c', 4, 1, 'BLOCK_LASTTORRENTS', 'no', 3, 8),
(12, 'news', 'c', 1, 1, 'BLOCK_NEWS', 'no', 1, 8),
(13, 'mainmenu', 't', 1, 1, 'BLOCK_MENU', 'no', 1, 8),
(14, 'maintrackertoolbar', 't', 2, 1, 'BLOCK_MAINTRACKERTOOLBAR', 'no', 3, 8),
(15, 'mainusertoolbar', 't', 2, 1, 'BLOCK_MAINUSERTOOLBAR', 'no', 1, 8),
(16, 'serverload', 'c', 8, 0, 'BLOCK_SERVERLOAD', 'no', 8, 8),
(17, 'poller', 'l', 3, 1, 'BLOCK_POLL', 'no', 3, 8),
(18, 'seedwanted', 'c', 3, 1, 'BLOCK_SEEDWANTED', 'no', 3, 8),
(19, 'paypal', 'r', 1, 1, 'BLOCK_PAYPAL', 'no', 3, 8),
(20, 'ajax_shoutbox', 'c', 2, 1, 'BLOCK_SHOUTBOX', 'no', 3, 8);

--- new chat table (shoutbox)

CREATE TABLE `btit_chat` (
  `id` mediumint(9) NOT NULL auto_increment,
  `uid` mediumint(9) NOT NULL,
  `time` int(10) NOT NULL default '0',
  `name` tinytext NOT NULL,
  `text` text NOT NULL,
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM;

--- new files table (substitute of namemap + summary)

CREATE TABLE IF NOT EXISTS btit_files (
  `info_hash` varchar(40) NOT NULL default '',
  `filename` varchar(250) NOT NULL default '',
  `url` varchar(250) NOT NULL default '',
  `info` varchar(250) NOT NULL default '',
  `data` datetime NOT NULL default '0000-00-00 00:00:00',
  `size` bigint(20) NOT NULL default '0',
  `comment` text,
  `category` int(10) unsigned NOT NULL default '6',
  `external` enum('yes','no') NOT NULL default 'no',
  `announce_url` varchar(100) NOT NULL default '',
  `uploader` int(10) NOT NULL default '1',
  `lastupdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `anonymous` enum('true','false') NOT NULL default 'false',
  `lastsuccess` datetime NOT NULL default '0000-00-00 00:00:00',
  `dlbytes` bigint(20) unsigned NOT NULL default '0',
  `seeds` int(10) unsigned NOT NULL default '0',
  `leechers` int(10) unsigned NOT NULL default '0',
  `finished` int(10) unsigned NOT NULL default '0',
  `lastcycle` int(10) unsigned NOT NULL default '0',
  `lastSpeedCycle` int(10) unsigned NOT NULL default '0',
  `speed` bigint(20) unsigned NOT NULL default '0',
  `bin_hash` blob NOT NULL default '',
  PRIMARY KEY  (`info_hash`),
  KEY `filename` (`filename`),
  KEY `category` (`category`),
  KEY `uploader` (`uploader`),
  KEY `bin_hash` (`bin_hash`(20))
) TYPE=MyISAM;

--- get all torrents from old namemap table

INSERT INTO btit_files (info_hash, filename, url,
info, data, size, comment, category, `external`,
announce_url, uploader, lastupdate, anonymous, lastsuccess)
SELECT info_hash, filename, url,
info, data, size, comment, category, `external`,
announce_url, uploader, lastupdate, anonymous, lastsuccess FROM namemap;

--- update all torrents with summary's info

UPDATE btit_files bf, summary s SET bf.dlbytes=s.dlbytes, bf.seeds=s.seeds, bf.leechers=s.leechers,
bf.finished=s.finished, bf.lastcycle=s.lastcycle, bf.lastSpeedCycle=s.lastSpeedCycle,
bf.speed=s.speed, bf.bin_hash=UNHEX(s.info_hash) WHERE s.info_hash=bf.info_hash;


--- add parent field for forum

ALTER TABLE `btit_forums`
ADD `id_parent` int(10) NOT NULL default '0',
ADD INDEX ( `id_parent` );


--- new table for installed hack
CREATE TABLE `btit_hacks` (
  `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
  `title` VARCHAR( 200 ) NOT NULL ,
  `version` VARCHAR( 10 ) NOT NULL ,
  `author` VARCHAR( 100 ) NOT NULL ,
  `added` INT( 11 ) NOT NULL,
  `folder` VARCHAR( 100 ) NOT NULL ,
  PRIMARY KEY  (`id`)
) TYPE = MYISAM ;


TRUNCATE TABLE `btit_language`;

INSERT INTO `btit_language` (`id`, `language`, `language_url`) VALUES
(1, 'English', 'language/english'),
(2, 'Romanian', 'language/romanian'),
(3, 'Polish', 'language/polish'),
(4, 'Serbocroatian', 'language/serbocroatian'),
(5, 'Dutch', 'language/dutch'),
(6, 'Italiano', 'language/italian');

--- new modules system
CREATE TABLE `btit_modules` (
  `id` mediumint(3) NOT NULL auto_increment,
  `name` varchar(40) NOT NULL default '',
  `activated` enum('yes','no') NOT NULL default 'yes',
  `type` enum('staff','misc','torrent','style') NOT NULL default 'misc',
  `changed` datetime NOT NULL default '0000-00-00 00:00:00',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) Type=MyISAM;

--- it's only an example
INSERT INTO `btit_modules` (`name`, `activated`, `type`, `changed`, `created`) VALUES
('shout', 'yes', 'staff', '2007-11-29 17:37:08', '2007-11-29 17:37:08');

--- new online system

DROP TABLE IF EXISTS `btit_online`;
CREATE TABLE IF NOT EXISTS `btit_online` (
  `session_id` varchar(40) NOT NULL,
  `user_id` int(10) NOT NULL,
  `user_ip` varchar(15) NOT NULL,
  `location` varchar(20) NOT NULL,
  `lastaction` int(10) NOT NULL,
  `user_name` varchar(40) NOT NULL,
  `user_group` varchar(50) NOT NULL,
  `prefixcolor` varchar(200) NOT NULL,
  `suffixcolor` varchar(200) NOT NULL,
  PRIMARY KEY  (`session_id`),
  KEY `user_id` (`user_id`)
) TYPE=MyISAM;


--- new ajax poll system
CREATE TABLE `btit_poller` (
  `ID` int(11) NOT NULL auto_increment,
  `startDate` int(10) NOT NULL default '0',
  `endDate` int(10) NOT NULL default '0',
  `pollerTitle` varchar(255) default NULL,
  `starterID` mediumint(8) NOT NULL default '0',
  `active` enum('yes','no') NOT NULL default 'no',
  PRIMARY KEY  (`ID`)
) Type=MyISAM;

INSERT INTO `btit_poller` VALUES (1, UNIX_TIMESTAMP(), 0, 'How would you rate this new script?', 2, 'yes');

--- new ajax poll system
CREATE TABLE `btit_poller_option` (
  `ID` int(11) NOT NULL auto_increment,
  `pollerID` int(11) default NULL,
  `optionText` varchar(255) default NULL,
  `pollerOrder` int(11) default NULL,
  `defaultChecked` char(1) default '0',
  PRIMARY KEY  (`ID`)
) Type=MyISAM;

INSERT INTO `btit_poller_option` VALUES (1, 1, 'Excellent', 1, '1'),
(2, 1, 'Very good', 2, '0'),
(3, 1, 'Good', 3, '0'),
(4, 1, 'Fair', 3, '0'),
(5, 1, 'Poor', 4, '0');

--- new ajax poll system
CREATE TABLE `btit_poller_vote` (
  `ID` int(11) NOT NULL auto_increment,
  `pollerID` int(11) NOT NULL default '0',
  `optionID` int(11) default NULL,
  `ipAddress` bigint(11) default '0',
  `voteDate` int(10) NOT NULL default '0',
  `memberID` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID`)
) Type=MyISAM;


--- new, settings are saved in db now

CREATE TABLE `btit_settings` (
  `key` varchar(30) NOT NULL,
  `value` varchar(200) NOT NULL,
  PRIMARY KEY  (`key`)
) TYPE=MyISAM;

--- default, need to be change by owner!
INSERT INTO `btit_settings` (`key`, `value`) VALUES 
('name', 'Btit Local Test'),
('url', 'http://localhost'),
('announce', 'a:2:{i:0;s:30:"http://localhost/announce.php\r";i:1;s:30:"http://localhost:2710/announce";}'),
('email', 'admin@localhost'),
('torrentdir', 'torrents'),
('external', 'true'),
('gzip', 'true'),
('debug', 'true'),
('disable_dht', 'true'),
('livestat', 'true'),
('logactive', 'true'),
('loghistory', 'true'),
('p_announce', 'true'),
('p_scrape', 'false'),
('show_uploader', 'false'),
('usepopup', 'false'),
('default_language', '1'),
('default_charset', 'UTF-8'),
('default_style', '1'),
('max_users', '0'),
('max_torrents_per_page', '15'),
('sanity_update', '1800'),
('external_update', '1800'),
('max_announce', '1800'),
('min_announce', '300'),
('max_peers_per_announce', '50'),
('dynamic', 'false'),
('nat', 'false'),
('persist', 'false'),
('allow_override_ip', 'false'),
('countbyte', 'true'),
('peercaching', 'true'),
('maxpid_seeds', '3'),
('maxpid_leech', '1'),
('validation', 'user'),
('imagecode', 'true'),
('forum', ''),
('clocktype', 'false'),
('newslimit', '3'),
('forumlimit', '5'),
('last10limit', '5'),
('mostpoplimit', '5');


TRUNCATE TABLE `btit_style`;

INSERT INTO `btit_style` (`id`, `style`, `style_url`) VALUES
(1, 'xBtit_Default', 'style/xbtit_default'),
(2, 'Mint Green', 'style/mintgreen'),
(3, 'Dark Lair', 'style/darklair'),
(4, 'The Hive', 'style/thehive');


ALTER TABLE `btit_users`
CHANGE `avatar` `avatar` VARCHAR( 200 ) default NULL,
ADD `smf_fid` int(10) NOT NULL default '0';

ALTER TABLE `btit_users_level`
CHANGE `prefixcolor` `prefixcolor` VARCHAR( 200 ) NOT NULL,
CHANGE `suffixcolor` `suffixcolor` VARCHAR( 200 ) NOT NULL;

