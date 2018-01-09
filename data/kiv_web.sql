-- Adminer 4.3.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `games`;
CREATE TABLE `games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `owner_id` int(11) NOT NULL,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `owner` (`owner_id`),
  CONSTRAINT `games_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `games` (`id`, `stamp`, `owner_id`, `deleted`) VALUES
(88,	'2018-01-08 22:23:19',	1,	CONV('0', 2, 10) + 0),
(89,	'2018-01-08 22:23:48',	1,	CONV('0', 2, 10) + 0),
(90,	'2018-01-08 22:23:49',	1,	CONV('0', 2, 10) + 0),
(91,	'2018-01-09 13:49:35',	1,	CONV('0', 2, 10) + 0),
(92,	'2018-01-09 13:50:35',	1,	CONV('0', 2, 10) + 0),
(93,	'2018-01-09 13:56:14',	1,	CONV('0', 2, 10) + 0),
(94,	'2018-01-09 16:01:24',	1,	CONV('0', 2, 10) + 0);

DROP TABLE IF EXISTS `players`;
CREATE TABLE `players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `post` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `side` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `scored` tinyint(4) NOT NULL,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `game_id` (`game_id`),
  CONSTRAINT `players_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `players_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `players` (`id`, `post`, `side`, `user_id`, `game_id`, `scored`, `deleted`) VALUES
(8,	'front',	'home',	1,	88,	4,	CONV('0', 2, 10) + 0),
(9,	'back',	'home',	2,	88,	6,	CONV('0', 2, 10) + 0),
(10,	'front',	'guest',	3,	88,	1,	CONV('0', 2, 10) + 0),
(11,	'back',	'guest',	4,	88,	3,	CONV('0', 2, 10) + 0),
(12,	'front',	'home',	1,	89,	4,	CONV('0', 2, 10) + 0),
(13,	'back',	'home',	2,	89,	6,	CONV('0', 2, 10) + 0),
(14,	'front',	'guest',	3,	89,	1,	CONV('0', 2, 10) + 0),
(15,	'back',	'guest',	4,	89,	3,	CONV('0', 2, 10) + 0),
(16,	'front',	'home',	1,	90,	4,	CONV('0', 2, 10) + 0),
(17,	'back',	'home',	2,	90,	6,	CONV('0', 2, 10) + 0),
(18,	'front',	'guest',	3,	90,	1,	CONV('0', 2, 10) + 0),
(19,	'back',	'guest',	4,	90,	3,	CONV('0', 2, 10) + 0),
(20,	'front',	'home',	1,	91,	10,	CONV('0', 2, 10) + 0),
(21,	'back',	'home',	5,	91,	0,	CONV('0', 2, 10) + 0),
(22,	'front',	'guest',	4,	91,	6,	CONV('0', 2, 10) + 0),
(23,	'back',	'guest',	6,	91,	3,	CONV('0', 2, 10) + 0),
(24,	'front',	'home',	4,	92,	1,	CONV('0', 2, 10) + 0),
(25,	'back',	'home',	7,	92,	1,	CONV('0', 2, 10) + 0),
(26,	'front',	'guest',	6,	92,	5,	CONV('0', 2, 10) + 0),
(27,	'back',	'guest',	2,	92,	5,	CONV('0', 2, 10) + 0),
(28,	'front',	'home',	2,	93,	3,	CONV('0', 2, 10) + 0),
(29,	'back',	'home',	3,	93,	7,	CONV('0', 2, 10) + 0),
(30,	'front',	'guest',	4,	93,	0,	CONV('0', 2, 10) + 0),
(31,	'back',	'guest',	3,	93,	0,	CONV('0', 2, 10) + 0),
(32,	'front',	'home',	1,	94,	0,	CONV('0', 2, 10) + 0),
(33,	'back',	'home',	3,	94,	2,	CONV('0', 2, 10) + 0),
(34,	'front',	'guest',	4,	94,	5,	CONV('0', 2, 10) + 0),
(35,	'back',	'guest',	6,	94,	1,	CONV('0', 2, 10) + 0);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `role` enum('member','admin') COLLATE utf8_czech_ci NOT NULL,
  `deleted` bit(1) NOT NULL DEFAULT b'0',
  `photo` varchar(255) COLLATE utf8_czech_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

INSERT INTO `users` (`id`, `name`, `username`, `password`, `role`, `deleted`, `photo`) VALUES
(1,	'Ondra',	'ondra',	'$2y$10$X58LuLvFI/TK8iYbjoyC4e9r1O/p694hA4VU9e/BJE21lhZyoC.I6',	'admin',	CONV('0', 2, 10) + 0,	NULL),
(2,	'Matěj',	'matej',	'',	'member',	CONV('0', 2, 10) + 0,	NULL),
(3,	'Honza',	'honza',	'',	'member',	CONV('0', 2, 10) + 0,	NULL),
(4,	'David',	'david',	'',	'member',	CONV('0', 2, 10) + 0,	NULL),
(5,	'Gogo',	'gogo',	'$2y$10$tNwxHlx2pt8csPMxEqsc5ePpabBVyvRiMJ0eIhqWUnwCz8RDdcSl.',	'member',	CONV('0', 2, 10) + 0,	NULL),
(6,	'Petr',	'petr',	'',	'member',	CONV('0', 2, 10) + 0,	NULL),
(7,	'Vašek',	'vasek',	'',	'member',	CONV('0', 2, 10) + 0,	NULL),
(8,	'Franta <script>alert(1);</script>',	'franta',	'$2y$10$Asbcb/wu0FwdS/FWV3yhruyVgFOgMtzeualJne1KCwPHTtXCGt2xm',	'member',	CONV('0', 2, 10) + 0,	NULL),
(9,	'Jakub \'; DROP TABLE `users`;',	'jakub',	'$2y$10$4WgkSJTVmtPCpnZX2kD6d.8ewAOuIXnqViEaRl/NQD/liVS.OHff.',	'member',	CONV('0', 2, 10) + 0,	NULL),
(10,	'<script>alert(1);</script>',	'alert',	'$2y$10$GTVireLkXYXCjk4BHhioH.q5EtAPlrlXwLDaJuOia0sX4gwMFjHXK',	'member',	CONV('0', 2, 10) + 0,	NULL);

-- 2018-01-09 17:34:01
