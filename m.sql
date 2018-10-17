
CREATE TABLE IF NOT EXISTS `activitylog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `action` varchar(100) CHARACTER SET utf8 NOT NULL,
  `additionalinfo` varchar(500) CHARACTER SET utf8 NOT NULL DEFAULT 'none',
  `ip` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `attempts` (
  `ip` varchar(15) NOT NULL,
  `count` int(11) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `hash` varchar(32) CHARACTER SET utf8 NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(15) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=50 ;


CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) CHARACTER SET utf8 NOT NULL,
  `password` varchar(128) CHARACTER SET utf8 NOT NULL,
  `email` varchar(100) CHARACTER SET utf8 NOT NULL,
  `resetkey` varchar(15) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `permissions` text CHARACTER SET utf8 NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `resetkey`, `permissions`, `active`) VALUES
(1, 'احمد عليوة', 'd968a03cd3697cde39a1fbe48c443ce44e1ecf516ce0d54a8112b804ad941dd028a652d89c662c3da9917faa7b41025daf88ad53209ddf780128a15b04ff5805', 'elamlhost@gmail.com', '0', 'all', 1);

CREATE TABLE `gold_trans` (
  `from_player` varchar(15) DEFAULT NULL,
  `to_player` varchar(15) DEFAULT NULL,
  `trans_date` datetime DEFAULT NULL,
  `gold` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `g_summary` (
  `players_count` int(5) DEFAULT '0',
  `active_players_count` int(5) DEFAULT '0',
  `truce_time` datetime DEFAULT NULL,
  `truce_reason` text,
  `news_text` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `money_log` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `transID` varchar(60) DEFAULT NULL,
  `usernam` varchar(15) DEFAULT NULL,
  `golds` int(6) NOT NULL,
  `money` int(3) NOT NULL,
  `currency` varchar(3) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `status` int(2) DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `p_players` (
  `id` int(7) NOT NULL AUTO_INCREMENT,
  `name` varchar(15) DEFAULT NULL,
  `pwd` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `invite_by` int(5) DEFAULT NULL,
  `activation_code` varchar(20) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` tinyint(1) NOT NULL DEFAULT '0',
  `house_name` varchar(20) DEFAULT NULL,
  `gold_num` int(11) NOT NULL DEFAULT '0',
  `gold_buy` int(11) NOT NULL DEFAULT '0',
  `avatar` varchar(255) DEFAULT './assets/default/img/u2rtl/u15.gif',
   PRIMARY KEY (`id`),
   UNIQUE KEY `NewIndex1` (`name`),
   UNIQUE KEY `NewIndex2` (`activation_code`),
   UNIQUE KEY `NewIndex4` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `servers` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `players_count` int(6) NOT NULL DEFAULT '0',
    `settings` text CHARACTER SET utf8 NOT NULL,
    `plus` text CHARACTER SET utf8 NOT NULL,
    `troop` text CHARACTER SET utf8 NOT NULL,
    `start_date` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `settings` (
    `name` varchar(50) NOT NULL,
    `value` text NOT NULL,
    PRIMARY KEY (`name`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO `settings` (`name`, `value`) VALUES
('page', '{"ar_title":"حرب التتار","en_title":"Tatar War","ar_meta":"game","en_meta":"game"}'),
('system', '{"spybass":"A1234567","adminName":"الادارة","adminPassword":"a234567","lang":"ar","server_url":"http://www.xtatar.com","admin_email":"servers@xtatar.com","email":"","installkey":"jhghfghffh"}'),
('blocked_email', 'hemaad48@yahoo.com,marhpa@yahoo.com,raray@hotmail.com,rakan.al.sakran@hotmail.com,saad-00-1@hotmail.com,ameer778@hotmail.com'),
('bad_words', 'احمد,عليوة,tatar,xtatar,احمد,t r a v i a n,kawaserwar,ntaatar,t a t a r s o,HtAtAr,t a t a r s w a r x,r i x,w a r,astatar,اساطير,satravian,s  a  t  r  a  v  i  a  n,rb2,goo'),
('G2A', '{"name":"G2A","image":"g2a-pay.png","merchant_id":"f53033fa-7fb2-47ca-97be-f494d276bea2","bonus":"100","currency":"EUR"}')
;

CREATE TABLE `packages` (
    `id` int(5) NOT NULL AUTO_INCREMENT,
    `name` varchar(50) NOT NULL,
    `gold` int(8) NOT NULL,
    `cost` varchar(10) NOT NULL,
    `bonus` int(3) NOT NULL DEFAULT '0',
    `image` varchar(30) DEFAULT NULL,
    PRIMARY KEY (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `friends` (
    `id` int(6) NOT NULL AUTO_INCREMENT,
    `toid` int(6) NOT NULL,
    `fromid` int(6) NOT NULL,
    `status` int(2) NOT NULL,
    `date` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `support_tickets` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `server_id` int(6) NOT NULL,
    `player_id` int(6) NOT NULL,
    `title` varchar(100) CHARACTER SET utf8 NOT NULL,
    `content` text CHARACTER SET utf8 NOT NULL,
    `type` tinyint(1) NOT NULL,
    `status` tinyint(1) NOT NULL DEFAULT '0',
    `added_time` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
   ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;
   
CREATE TABLE IF NOT EXISTS `support_tickets_replaies` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `ticket_id` int(11) NOT NULL,
    `replaier_id` int(5) DEFAULT NULL,
    `replaier_name` varchar(100) DEFAULT NULL,
    `is_player` tinyint(1) NOT NULL DEFAULT '1',
    `replay` text CHARACTER SET utf8 NOT NULL,
    `added_time` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;