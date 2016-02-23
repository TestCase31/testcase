-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Фев 23 2016 г., 23:10
-- Версия сервера: 5.5.25
-- Версия PHP: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `ws_testcase`
--
CREATE DATABASE `ws_testcase` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ws_testcase`;

DELIMITER $$
--
-- Функции
--
CREATE DEFINER=`root`@`localhost` FUNCTION `tc_getnextseq`() RETURNS int(10) unsigned
    MODIFIES SQL DATA
begin
declare max_id int(10);
INSERT INTO tc_seq(id_seq) VALUES (NULL);
SELECT max(id_seq) INTO max_id FROM tc_seq;
RETURN max_id;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Структура таблицы `tc_catalog`
--

CREATE TABLE IF NOT EXISTS `tc_catalog` (
  `id_cat` int(11) NOT NULL,
  `id_type_cat` int(11) NOT NULL,
  `n_cat` varchar(2000) NOT NULL,
  PRIMARY KEY (`id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Каталог товаров';

--
-- Дамп данных таблицы `tc_catalog`
--

INSERT INTO `tc_catalog` (`id_cat`, `id_type_cat`, `n_cat`) VALUES
(45, 43, 'Apple iPhone 5 16Gb'),
(46, 43, 'Apple iPhone 4S'),
(178, 43, 'HTC One X 32Gb');

-- --------------------------------------------------------

--
-- Структура таблицы `tc_prm`
--

CREATE TABLE IF NOT EXISTS `tc_prm` (
  `id_prm` int(11) NOT NULL,
  `id_prm_type` int(11) NOT NULL,
  `v_prm` varchar(2000) CHARACTER SET cp1251 NOT NULL,
  `lnk_prm` int(11) DEFAULT NULL,
  `lnk_cat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Значения параметров каталога';

--
-- Дамп данных таблицы `tc_prm`
--

INSERT INTO `tc_prm` (`id_prm`, `id_prm_type`, `v_prm`, `lnk_prm`, `lnk_cat`) VALUES
(49, 47, 'Белый', NULL, NULL),
(51, 48, 'IOS', NULL, NULL),
(52, 48, 'Android 4.1', NULL, NULL),
(53, 47, '', 49, 45),
(54, 48, '', 51, 45),
(106, 48, 'Windows Mobile', NULL, NULL),
(108, 98, 'Apple', NULL, NULL),
(111, 47, 'Черный', NULL, NULL),
(152, 100, '', 156, 45),
(155, 100, '140', NULL, NULL),
(156, 100, '160', NULL, NULL),
(157, 100, '200', NULL, NULL),
(158, 100, '210', NULL, NULL),
(159, 100, '220', NULL, NULL),
(163, 47, '', 111, 46),
(164, 48, '', 51, 46),
(167, 100, '', 155, 46),
(168, 105, '1.6', NULL, 46),
(169, 101, '22500', NULL, 45),
(170, 47, 'Синий', NULL, NULL),
(171, 98, '', 108, 45),
(172, 98, 'Nokia', NULL, NULL),
(173, 43, 'HTC One X 32Gb', NULL, NULL),
(174, 43, 'HTC One X 32Gb', NULL, NULL),
(175, 43, '', NULL, NULL),
(176, 43, 'HTC One X 16Gb', NULL, NULL),
(177, 43, 'HTC One X 16Gb', NULL, NULL),
(179, 48, '', 52, 178),
(180, 98, 'HTC', NULL, NULL),
(181, 100, '', 157, 178),
(182, 98, '', 180, 178),
(183, 47, '', 170, 178),
(184, 105, '2', NULL, 178),
(185, 101, '10000', NULL, 178),
(186, 101, '18500', NULL, 46),
(188, 98, '', 108, 46);

-- --------------------------------------------------------

--
-- Структура таблицы `tc_prm_type`
--

CREATE TABLE IF NOT EXISTS `tc_prm_type` (
  `id_prm_type` int(11) NOT NULL,
  `n_prm_type` varchar(2000) CHARACTER SET cp1251 NOT NULL,
  `f_prm` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `tc_prm_type`
--

INSERT INTO `tc_prm_type` (`id_prm_type`, `n_prm_type`, `f_prm`) VALUES
(47, 'Цвет', 1),
(48, 'Операционная система', 1),
(98, 'Производитель', 1),
(100, 'Вес', 1),
(101, 'Цена', 0),
(102, 'ШхВхТ', 0),
(103, 'Экран', 0),
(105, 'Фотокамера', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `tc_seq`
--

CREATE TABLE IF NOT EXISTS `tc_seq` (
  `id_seq` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id_seq`),
  UNIQUE KEY `id_seq` (`id_seq`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=189 ;

--
-- Дамп данных таблицы `tc_seq`
--

INSERT INTO `tc_seq` (`id_seq`) VALUES
(4),
(5),
(6),
(7),
(8),
(9),
(10),
(11),
(12),
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20),
(21),
(22),
(23),
(24),
(25),
(26),
(27),
(28),
(29),
(30),
(31),
(32),
(33),
(34),
(35),
(36),
(37),
(38),
(39),
(40),
(41),
(42),
(43),
(44),
(45),
(46),
(47),
(48),
(49),
(50),
(51),
(52),
(53),
(54),
(55),
(56),
(57),
(58),
(59),
(60),
(61),
(62),
(63),
(64),
(65),
(66),
(67),
(68),
(69),
(70),
(71),
(72),
(73),
(74),
(75),
(76),
(77),
(78),
(79),
(80),
(81),
(82),
(83),
(84),
(85),
(86),
(87),
(88),
(89),
(90),
(91),
(92),
(93),
(94),
(95),
(96),
(97),
(98),
(99),
(100),
(101),
(102),
(103),
(104),
(105),
(106),
(107),
(108),
(109),
(110),
(111),
(112),
(113),
(114),
(115),
(116),
(117),
(118),
(119),
(120),
(121),
(122),
(123),
(124),
(125),
(126),
(127),
(128),
(129),
(130),
(131),
(132),
(133),
(134),
(135),
(136),
(137),
(138),
(139),
(140),
(141),
(142),
(143),
(144),
(145),
(146),
(147),
(148),
(149),
(150),
(151),
(152),
(153),
(154),
(155),
(156),
(157),
(158),
(159),
(160),
(161),
(162),
(163),
(164),
(165),
(166),
(167),
(168),
(169),
(170),
(171),
(172),
(173),
(174),
(175),
(176),
(177),
(178),
(179),
(180),
(181),
(182),
(183),
(184),
(185),
(186),
(187),
(188);

-- --------------------------------------------------------

--
-- Структура таблицы `tc_type_catalog`
--

CREATE TABLE IF NOT EXISTS `tc_type_catalog` (
  `id_type_cat` int(11) NOT NULL,
  `n_type_cat` varchar(2000) CHARACTER SET cp1251 COLLATE cp1251_general_cs NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Типы категорий каталога';

--
-- Дамп данных таблицы `tc_type_catalog`
--

INSERT INTO `tc_type_catalog` (`id_type_cat`, `n_type_cat`) VALUES
(43, 'Смартфоны');

-- --------------------------------------------------------

--
-- Структура таблицы `test`
--

CREATE TABLE IF NOT EXISTS `test` (
  `id` bigint(20) NOT NULL,
  `id_prm` bigint(20) NOT NULL,
  `v_prm` varchar(2000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Дамп данных таблицы `test`
--

INSERT INTO `test` (`id`, `id_prm`, `v_prm`) VALUES
(1, 1, 'zoo');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
