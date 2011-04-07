DROP TABLE IF EXISTS `barometers`;
CREATE TABLE IF NOT EXISTS `barometers` (
  `barometer_id` varchar(25) NOT NULL,
  `recipient_email` varchar(100) NOT NULL,
  `created` datetime NOT NULL,
  `active` int(1) NOT NULL default '1',
  PRIMARY KEY  (`barometer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
