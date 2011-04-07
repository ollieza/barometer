DROP TABLE IF EXISTS `email_queue`;
CREATE TABLE IF NOT EXISTS `email_queue` (
  `email_queue_id` int(16) NOT NULL auto_increment,
  `email_from_email` varchar(250) NOT NULL,
  `email_from_name` varchar(100) NOT NULL,
  `email_reply_email` varchar(250) NOT NULL,
  `email_subject` varchar(500) NOT NULL,
  `email_message` text NOT NULL,
  `sender_ip` int(10) unsigned NOT NULL,
  `last_attempt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `barometer_id` varchar(25) NOT NULL,
  PRIMARY KEY  (`email_queue_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;