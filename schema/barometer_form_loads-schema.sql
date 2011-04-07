DROP TABLE IF EXISTS `barometer_form_loads`;
CREATE TABLE IF NOT EXISTS `barometer_form_loads` (
  `referral_id` int(20) NOT NULL auto_increment,
  `barometer_id` varchar(25) default NULL,
  `reffering_domain` varchar(300) default NULL,
  `accessed` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`referral_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
