-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2025-02-27 23:08:44
-- 伺服器版本： 8.0.33
-- PHP 版本： 8.2.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `library`
--

-- --------------------------------------------------------

--
-- 資料表結構 `book`
--

CREATE TABLE `book` (
  `ISBN` text NOT NULL,
  `Sys_no` varchar(50) NOT NULL,
  `Name` text NOT NULL,
  `Author` text NOT NULL,
  `Version` text NOT NULL,
  `R_id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `Borrow_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 傾印資料表的資料 `book`
--

INSERT INTO `book` (`ISBN`, `Sys_no`, `Name`, `Author`, `Version`, `R_id`, `Borrow_date`) VALUES
('9789571846257', '000955555', '古典吉他小品集', '蘇昭興', '2015', '1', '2023-09-28 13:12:58'),
('9578290276 ', '001044802', '飲食男女', '殷登國', '2012', NULL, '2023-06-07 16:59:21'),
('9789572157251', '001115552', '計算機結構', '劉若英', '2020', NULL, '2023-06-07 16:58:22'),
('9789574835959', '001565133', '離散數學', '張筱涵', '2016', NULL, NULL),
('9789574835959', '001567120', '離散數學', '張筱涵', '2016', NULL, NULL),
('9789573613253', '001613750', '文學賞析與文學家', '張健', '2016', NULL, NULL),
('9789864340255', '001695935', '圖解組合語言', '徐偉智', '2019', NULL, NULL),
('9789869553056', '002134768', '七把刀弄懂微積分', '王富祥', '2017', NULL, NULL),
('9789869553056', '002134981', '七把刀弄懂微積分', '王富祥', '2017', NULL, NULL),
('9789578567504', '002251443', '理想的簡單飲食', '張小辰', '2017', NULL, NULL),
('9789865501693', '002284062', '資料結構', '劉逸', '2022', NULL, NULL),
('9789860629217', '002286097', '極簡設計美學', '陳昱銘', '2021', NULL, NULL),
('9789865076733 ', '002308788', '健身基礎教學', '陳昱帆', '2023', NULL, NULL),
('9789869298384', '002316015', 'TOEIC測驗核心單字書', 'Edward', '2013', NULL, NULL),
('9786263234482', '002321275', '教育心理學', '喬伊', '2022', NULL, NULL),
('9786263332188', '002322474', '作業系統', '林宥嘉', '2023', NULL, NULL),
('9786263332188', '002322475', '作業系統', '林宥嘉', '2023', NULL, NULL),
('9786269656936', '002329224', '本草綱目養生智慧全書', '趙靜濤', '2023', NULL, NULL),
('9786263234482', '002329753', '教育心理學', '喬伊', '2022', NULL, NULL),
('9789577849632', '002329853', '管理學:觀光休閒服務管理導向', '邱繼弘', '2022', NULL, NULL),
('9798426817807', '002341153', 'Software Engineering', 'Parker, Colby B.', '2022', '110916023', '2023-06-07 17:03:04');

-- --------------------------------------------------------

--
-- 資料表結構 `manager_account`
--

CREATE TABLE `manager_account` (
  `Password` text NOT NULL,
  `Account` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 傾印資料表的資料 `manager_account`
--

INSERT INTO `manager_account` (`Password`, `Account`) VALUES
('testPassword', 'evankuo2023@gmail.com'),
('testPassword2', 'kevin2023@gmail.com');

-- --------------------------------------------------------

--
-- 資料表結構 `reader`
--

CREATE TABLE `reader` (
  `Id` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `Name` text NOT NULL,
  `Sex` tinyint(1) NOT NULL,
  `Contacts` text NOT NULL,
  `Birth_date` date NOT NULL,
  `Overdue` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 傾印資料表的資料 `reader`
--

INSERT INTO `reader` (`Id`, `Name`, `Sex`, `Contacts`, `Birth_date`, `Overdue`) VALUES
('1', '1', 1, '1', '2002-01-02', 1),
('110802016', '加里歐', 1, '0932105762', '1999-01-23', 0),
('110916012', '邱裕翔', 1, '0962132752', '2002-04-29', 0),
('110916023', '郭逸凡', 1, '0942781538', '2002-07-16', 0),
('111015002', '路西恩', 1, '0938100562', '2003-06-10', 0),
('TE1056', '張佑華', 1, '0926612763', '2000-01-27', 0),
('TE1072', '林曉燕', 0, '0926357159', '1992-07-12', 0);

-- --------------------------------------------------------

--
-- 資料表結構 `red_account`
--

CREATE TABLE `red_account` (
  `Account` text NOT NULL,
  `Password` text NOT NULL,
  `Id` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 傾印資料表的資料 `red_account`
--

INSERT INTO `red_account` (`Account`, `Password`, `Id`) VALUES
('1', '1', '1'),
('chuma', 'c1234', '110916012'),
('Evan', 'e1234', '110916023'),
('Galio', 'g1234', '110802016'),
('Johnny', 'j1234', 'TE1056'),
('Lucian', 'l1234', '111015002'),
('Mary', 'm1234', 'TE1072');

-- --------------------------------------------------------

--
-- 資料表結構 `reservation`
--

CREATE TABLE `reservation` (
  `R_id` varchar(50) NOT NULL,
  `B_no` varchar(50) NOT NULL,
  `date` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- 傾印資料表的資料 `reservation`
--

INSERT INTO `reservation` (`R_id`, `B_no`, `date`) VALUES
('110802016', '002341153', '2023-06-07 17:03:47');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`Sys_no`),
  ADD KEY `BORROWS` (`R_id`);

--
-- 資料表索引 `manager_account`
--
ALTER TABLE `manager_account`
  ADD PRIMARY KEY (`Account`(20));

--
-- 資料表索引 `reader`
--
ALTER TABLE `reader`
  ADD PRIMARY KEY (`Id`);

--
-- 資料表索引 `red_account`
--
ALTER TABLE `red_account`
  ADD PRIMARY KEY (`Account`(20)),
  ADD KEY `HAS` (`Id`);

--
-- 資料表索引 `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`R_id`,`B_no`),
  ADD KEY `reserve` (`B_no`);

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `BORROWS` FOREIGN KEY (`R_id`) REFERENCES `reader` (`Id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- 資料表的限制式 `red_account`
--
ALTER TABLE `red_account`
  ADD CONSTRAINT `HAS` FOREIGN KEY (`Id`) REFERENCES `reader` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表的限制式 `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reserve` FOREIGN KEY (`B_no`) REFERENCES `book` (`Sys_no`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reserveBy` FOREIGN KEY (`R_id`) REFERENCES `reader` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
