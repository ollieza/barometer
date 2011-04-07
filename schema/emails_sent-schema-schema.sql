DROP TABLE IF EXISTS `emails_sent`;
CREATE TABLE IF NOT EXISTS `emails_sent` (
  `email_id` int(11) NOT NULL auto_increment,
  `barometer_id` varchar(20) NOT NULL,
  `sender_ip` int(10) unsigned NOT NULL,
  `sent` datetime NOT NULL,
  PRIMARY KEY  (`email_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;