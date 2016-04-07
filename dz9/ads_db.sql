-- Adminer 4.2.4 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `ads`;
CREATE TABLE `ads` (
  `id` varchar(20) NOT NULL,
  `private` tinyint(1) NOT NULL DEFAULT '0',
  `seller_name` varchar(20) NOT NULL,
  `email` varchar(20) NOT NULL,
  `allow_mails` varchar(2) NOT NULL DEFAULT '',
  `phone` varchar(11) NOT NULL,
  `location_id` varchar(6) NOT NULL DEFAULT '0',
  `category_id` tinyint(4) unsigned NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `category` (`id`, `name`) VALUES
(1,	'-- Выберите категорию --'),
(2,	'Транспорт'),
(3,	'Недвижимость'),
(4,	'Работа'),
(5,	'Услуги'),
(6,	'Личные вещи'),
(7,	'Для дома и дачи'),
(8,	'Бытовая электроника'),
(9,	'Прочее');

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `id` varchar(6) NOT NULL,
  `name` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `location` (`id`, `name`) VALUES
('0',	'-- Выберите город --'),
('641780',	'Новосибирск'),
('641490',	'Барабинск'),
('641510',	'Бердск'),
('641600',	'Искитим'),
('641630',	'Колывань'),
('641680',	'Краснообск'),
('641710',	'Куйбышев'),
('641760',	'Мошково');

-- 2016-04-07 06:54:30
