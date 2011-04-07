DROP TABLE IF EXISTS `ip_addresses_spam`;
CREATE TABLE IF NOT EXISTS `ip_addresses_spam` (
  `ip_address_id` int(20) NOT NULL auto_increment,
  `ip_address` int(10) unsigned NOT NULL,
  `spam` int(1) NOT NULL,
  `spam_time` datetime NOT NULL,
  PRIMARY KEY  (`ip_address_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
