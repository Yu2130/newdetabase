-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2024-04-20 15:23:48
-- 伺服器版本： 10.4.32-MariaDB
-- PHP 版本： 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `course selection`
--

-- --------------------------------------------------------

--
-- 資料表結構 `class`
--

CREATE TABLE `class` (
  `id` int(10) NOT NULL,
  `title` varchar(20) NOT NULL,
  `credits` int(1) NOT NULL,
  `c_dep` varchar(20) NOT NULL,
  `req` varchar(5) NOT NULL,
  `capacity` int(2) NOT NULL,
  `select_people` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `class`
--

INSERT INTO `class` (`id`, `title`, `credits`, `c_dep`, `req`, `capacity`, `select_people`) VALUES
(1311, '班級活動', 0, '資訊系', '二下乙班', 60, 1),
(1312, '系統程式', 3, '資訊系', '二下乙班', 60, 1),
(1313, '資料庫系統', 3, '資訊系', '二下乙班', 60, 1),
(1314, '機率與統計', 3, '資訊系', '二下乙班', 60, 1),
(1323, '互連網路', 3, '資訊系', '選修', 60, 1),
(1324, 'Web程式設計', 3, '資訊系', '選修', 60, 0),
(1326, '系統分析與設計', 3, '資訊系', '選修', 60, 0),
(1328, '多媒體系統', 3, '資訊系', '選修', 60, 0),
(1334, 'UNIX應用與實務', 2, '資訊系', '選修	', 60, 0),
(2392, '班級活動', 0, '應數系', '二下乙班', 60, 1),
(2393, '統計學', 3, '應數系', '二下乙班', 60, 1),
(2394, '統計學實習', 0, '應數系', '二下乙班', 60, 1),
(2397, '數值分析(一)', 3, '應數系', '二下乙班', 60, 1),
(2398, '電腦視覺', 3, '應數系', '選修', 60, 0),
(2959, '人生哲學', 2, '通識中心', '選修', 60, 0),
(2960, '人生哲學', 2, '通識中心', '選修', 1, 0);

-- --------------------------------------------------------

--
-- 資料表結構 `date`
--

CREATE TABLE `date` (
  `id` int(10) NOT NULL,
  `date` int(10) NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `date`
--

INSERT INTO `date` (`id`, `date`, `start_time`, `end_time`) VALUES
(1313, 1, 8, 9),
(1313, 2, 3, 3),
(2393, 2, 7, 7),
(2393, 4, 6, 7),
(2398, 2, 1, 1),
(2398, 3, 6, 7),
(2959, 4, 6, 7),
(2960, 4, 8, 9),
(1312, 1, 3, 4),
(1312, 3, 4, 4),
(1314, 1, 6, 7),
(1314, 2, 4, 4),
(1334, 5, 6, 7),
(1324, 3, 1, 3),
(1328, 2, 6, 8),
(2392, 4, 9, 9),
(2394, 2, 8, 9),
(2397, 2, 2, 2),
(2397, 5, 3, 4),
(1311, 2, 9, 9),
(1323, 3, 6, 8),
(1326, 3, 3, 3),
(1326, 5, 3, 4);

-- --------------------------------------------------------

--
-- 資料表結構 `selected_courses`
--

CREATE TABLE `selected_courses` (
  `s_id` varchar(10) NOT NULL,
  `class_id` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `selected_courses`
--

INSERT INTO `selected_courses` (`s_id`, `class_id`) VALUES
('D1149785', 2392),
('D1149785', 2393),
('D1149785', 2394),
('D1149785', 2397),
('D1185306', 1311),
('D1185306', 1313),
('D1185306', 1314),
('D1185306', 1312),
('D1185306', 1323);

-- --------------------------------------------------------

--
-- 資料表結構 `student`
--

CREATE TABLE `student` (
  `s_id` varchar(10) NOT NULL,
  `name` varchar(20) NOT NULL,
  `s_dep` varchar(20) NOT NULL,
  `grade` varchar(10) NOT NULL,
  `total_cred` int(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `student`
--

INSERT INTO `student` (`s_id`, `name`, `s_dep`, `grade`, `total_cred`) VALUES
('D1149785', '謝浩禹', '應數系', '二下乙班', 6),
('D1185306', '李冕旭', '資訊系', '二下乙班', 12);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`s_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
