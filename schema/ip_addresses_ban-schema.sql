DROP TABLE IF EXISTS `ip_addresses_ban`;
CREATE TABLE IF NOT EXISTS `ip_addresses_ban` (
  `ip_address` int(10) unsigned NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY  (`ip_address`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
