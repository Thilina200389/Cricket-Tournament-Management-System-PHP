-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 28, 2026 at 06:34 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ctms_prov3`
--

-- --------------------------------------------------------

--
-- Table structure for table `match_history`
--

DROP TABLE IF EXISTS `match_history`;
CREATE TABLE IF NOT EXISTS `match_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `match_name` varchar(100) DEFAULT NULL,
  `team_a` varchar(50) DEFAULT NULL,
  `team_b` varchar(50) DEFAULT NULL,
  `scores` varchar(50) DEFAULT NULL,
  `result_desc` varchar(200) DEFAULT NULL,
  `played_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `match_history`
--

INSERT INTO `match_history` (`id`, `match_name`, `team_a`, `team_b`, `scores`, `result_desc`, `played_date`) VALUES
(1, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '60/1', 'Match Completed', '2026-01-24 15:33:43'),
(2, 'TEST MATCH', 'DAWGS', 'CHAMINATION', '1st: 73/5 | 2nd: 77/7', 'CHAMINATION WON by 3 Wickets', '2026-01-24 15:38:56'),
(3, 'Final Trophy Match', 'DAWGS', 'KALU SINHAYO', 'Runs: 0/0', 'No Result / Abandoned', '2026-01-24 15:42:42'),
(4, 'Final Trophy Match', 'DAWGS', 'POWER RANGERS', '0/0', 'Match Completed', '2026-01-24 16:03:14'),
(5, 'Final Trophy Match', 'DAWGS', 'POWER RANGERS', '49/3', 'Match Completed', '2026-01-24 16:06:50'),
(6, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '45/6', 'Match Completed', '2026-01-24 16:09:40'),
(7, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '56/4', 'Match Completed', '2026-01-24 17:16:10'),
(8, '2026', 'POWER RANGERS', 'DAWGS', '0/0', 'Match Completed', '2026-01-24 17:16:55'),
(9, 'Final Trophy Match', 'POWER RANGERS', 'DAWGS', '59/1', 'Match Completed', '2026-01-24 17:45:14'),
(10, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '43/9', 'Match Completed', '2026-01-24 17:48:48'),
(11, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '43/10', 'Match Completed', '2026-01-24 17:55:17'),
(12, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '47/7', 'Match Completed', '2026-01-24 17:57:53'),
(13, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '43/10', 'Match Completed', '2026-01-24 18:00:10'),
(14, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '38/10', 'Match Completed', '2026-01-24 18:09:49'),
(15, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '45/10', 'Match Completed', '2026-01-24 18:13:38'),
(16, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '51/8', 'Match Completed', '2026-01-24 18:16:39'),
(17, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '55/6', 'Match Completed', '2026-01-24 18:21:56'),
(18, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '38/4', 'Match Completed', '2026-01-24 18:24:55'),
(19, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '35/10', 'Match Completed', '2026-01-24 18:57:50'),
(20, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '47/1', 'Match Completed', '2026-01-24 22:28:23'),
(21, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '48/9', 'Match Completed', '2026-01-24 22:33:46'),
(22, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '46/7', 'Match Completed', '2026-01-24 22:39:33'),
(23, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '45/8', 'Match Completed', '2026-01-24 22:59:40'),
(24, 'Final Trophy Match', 'POWER RANGERS', 'CHAMINATION', '44/7', 'Match Completed', '2026-01-25 00:23:56'),
(25, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '47/4', 'Match Completed', '2026-01-25 00:28:13'),
(26, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '40/8', 'Match Completed', '2026-01-25 00:37:26'),
(27, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '46/6', 'Match Completed', '2026-01-25 00:55:50'),
(28, 'Final Trophy Match', 'CHAMINATION', 'POWER RANGERS', '8/10', 'Match Completed', '2026-01-25 01:09:37'),
(29, 'Inter-Provincial T10 Blast 2026', 'Colombo Strikers', 'Kandy Warriors', '45/9', 'Match Completed', '2026-01-26 17:54:14'),
(30, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '37/7', 'Match Completed', '2026-01-26 18:26:05'),
(31, 'TEST MATCH', 'Colombo Strikers', 'Kandy Warriors', '47/10', 'Match Completed', '2026-01-26 18:33:09'),
(32, 'TEST MATCH', 'Colombo Strikers', 'Kandy Warriors', '0/0', 'Match Completed', '2026-01-26 18:34:53'),
(33, 'TEST MATCH', 'Colombo Strikers', 'Kandy Warriors', '76/10', 'Match Completed', '2026-01-26 18:41:54'),
(34, 'TEST MATCH', 'Colombo Strikers', 'Kandy Warriors', '26/10', 'Match Completed', '2026-01-26 18:48:30'),
(35, 'TEST MATCH', 'Colombo Strikers', 'Kandy Warriors', '36/3', 'Match Completed', '2026-01-26 18:58:42'),
(36, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '37/5', 'Match Completed', '2026-01-26 19:50:15'),
(37, 'Final Trophy Match', 'Colombo Strikers', 'DAWGS', '19/7', 'Match Completed', '2026-01-26 20:00:19'),
(38, 'Final Trophy Match', 'DAWGS', 'Kandy Warriors', '37/10', 'Match Completed', '2026-01-26 20:06:11'),
(39, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '23/9', 'Match Completed', '2026-01-26 20:20:39'),
(40, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '37/6', 'Match Completed', '2026-01-26 20:37:19'),
(41, 'Final Trophy Match', 'Colombo Strikers', 'DAWGS', '24/3', 'Match Completed', '2026-01-26 20:51:34'),
(42, 'Final Trophy Match', 'Colombo Strikers', 'DAWGS', '50/3', 'Match Completed', '2026-01-26 21:00:07'),
(43, 'Final Trophy Match', 'CHAMINATION', 'Kandy Warriors', '34/5', 'Match Completed', '2026-01-26 21:07:55'),
(44, 'Final Trophy Match', 'DAWGS', 'CHAMINATION', '19/10', 'Match Completed', '2026-01-26 21:17:32'),
(45, 'Final Trophy Match', 'DAWGS', 'POWER RANGERS', '48/6', 'Match Completed', '2026-01-26 21:19:51'),
(48, 'Final Trophy Match', 'Colombo Strikers', 'CHAMINATION', '32/4', 'Match Completed', '2026-01-26 21:53:42'),
(47, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '36/4', 'Match Completed', '2026-01-26 21:30:44'),
(49, 'Final Trophy Match', 'DAWGS', 'Kandy Warriors', '35/10', 'Match Completed', '2026-01-26 22:18:13'),
(50, 'Final Trophy Match', 'Colombo Strikers', 'CHAMINATION', '36/8', 'Match Completed', '2026-01-26 22:38:49'),
(51, 'TEST MATCH', 'DAWGS', 'Colombo Strikers', '34/10', 'Match Completed', '2026-01-26 22:43:35'),
(52, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '61/10', 'Match Completed', '2026-01-26 22:46:39'),
(53, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '47/3', 'COLOMBO STRIKERS WON!', '2026-01-26 22:59:33'),
(54, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '28/10', 'COLOMBO STRIKERS WON!', '2026-01-26 23:02:30'),
(55, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '12/10', 'COLOMBO STRIKERS WON!', '2026-01-26 23:05:17'),
(56, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '45/2', 'KANDY WARRIORS WON!', '2026-01-26 23:28:09'),
(57, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '24/5', 'KANDY WARRIORS WON!', '2026-01-27 01:02:02'),
(58, 'Final Trophy Match', 'DAWGS', 'Kandy Warriors', '27/6', 'Kandy Warriors Won by 4 Wickets', '2026-01-27 01:13:53'),
(59, 'Final Trophy Match', 'DAWGS', 'Colombo Strikers', '52/6', 'Colombo Strikers Won by 4 Wickets (DLS)', '2026-01-27 01:16:48'),
(60, 'Inter-Provincial T10 Blast 2026', 'Colombo Strikers', 'Kandy Warriors', '41/5', 'Kandy Warriors Won by 5 Wickets (DLS)', '2026-01-27 01:30:49'),
(61, 'Final Trophy Match', 'Kandy Warriors', 'DAWGS', '49/9', 'Kandy Warriors Won by 2 Runs (DLS)', '2026-01-27 01:33:38'),
(62, '2026', 'Kandy Warriors', 'Colombo Strikers', '32/10', 'Kandy Warriors Won by 8 Runs (DLS)', '2026-01-27 01:38:30'),
(63, 'Final Trophy Match', 'Kandy Warriors', 'CHAMINATION', '18/10', 'Kandy Warriors Won by 13 Runs', '2026-01-27 01:40:38'),
(64, 'TEST MATCH', 'CHAMINATION', 'DAWGS', '39/6', 'DAWGS Won by 4 Wickets (DLS)', '2026-01-27 01:49:04'),
(65, 'Final Trophy Match', 'POWER RANGERS', 'DAWGS', '43/10', 'POWER RANGERS Won by 24 Runs', '2026-01-27 01:56:23'),
(66, 'Final Trophy Match', 'Kandy Warriors', 'CHAMINATION', '43/10', 'Kandy Warriors Won by 2 Runs', '2026-01-27 11:20:07'),
(67, 'Final Trophy Match', 'DAWGS', 'Kandy Warriors', '26/4', 'Kandy Warriors Won by 6 Wickets', '2026-01-27 11:22:01'),
(68, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '60/6', 'Colombo Strikers Won by 4 Wickets (DLS)', '2026-01-27 11:24:27'),
(69, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '31/5', 'Colombo Strikers Won by 5 Wickets', '2026-01-27 12:27:53'),
(70, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '43/10', 'Kandy Warriors Won by 9 Runs (DLS)', '2026-01-27 12:33:06'),
(71, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '21/10', 'Kandy Warriors Won by 32 Runs (DLS)', '2026-01-27 12:37:34'),
(72, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '34/3', 'Match Completed', '2026-01-27 12:38:42'),
(73, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '26/10', 'Colombo Strikers Won by 17 Runs (DLS)', '2026-01-27 12:47:00'),
(74, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '38/8', 'Kandy Warriors Won by 2 Wickets', '2026-01-27 12:53:50'),
(75, 'Final Trophy Match', 'DAWGS', 'POWER RANGERS', '48/7', 'DAWGS Won by 11 Runs (DLS)', '2026-01-27 13:03:36'),
(76, 'Final Trophy Match', 'Kandy Warriors', 'DAWGS', '36/10', 'Kandy Warriors Won by 11 Runs', '2026-01-27 13:05:58'),
(77, 'Final Trophy Match', 'Kandy Warriors', 'POWER RANGERS', '26/10', 'Kandy Warriors Won by 15 Runs', '2026-01-27 17:29:26'),
(78, 'Final Trophy Match', 'DAWGS', 'CHAMINATION', '48/5', 'CHAMINATION Won by 5 Wickets (DLS)', '2026-01-27 17:37:53'),
(79, 'Final Trophy Match', 'DAWGS', 'POWER RANGERS', '48/5', 'POWER RANGERS Won by 5 Wickets (DLS)', '2026-01-27 17:39:48'),
(81, 'Final Trophy Match', 'Kandy Warriors', 'POWER RANGERS', '47/4', 'POWER RANGERS Won by 6 Wickets (DLS)', '2026-01-27 18:18:20'),
(82, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '19/1', 'Kandy Warriors Won by 9 Wickets (DLS)', '2026-01-27 18:20:53'),
(83, 'Final Trophy Match', 'Kandy Warriors', 'Colombo Strikers', '25/1', 'Kandy Warriors Won by 15 Runs', '2026-01-27 18:22:42'),
(84, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '38/10', 'Colombo Strikers Won by 7 Runs', '2026-01-27 18:52:48'),
(85, 'Postman Final', 'Colombo Strikers', 'Kandy Warriors', '30/3', 'Kandy Warriors Won by 7 Wickets', '2026-01-27 21:59:32'),
(86, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '48/7', 'Colombo Strikers Won by 3 Wickets (DLS)', '2026-01-27 22:21:10'),
(87, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '29/1', 'Kandy Warriors Won by 9 Wickets (DLS)', '2026-01-27 23:32:09'),
(88, 'Final Trophy Match', 'DAWGS', 'CHAMINATION', '44/7', 'DAWGS Won by 3 Wickets (DLS)', '2026-01-27 23:35:08'),
(89, 'Final Trophy Match', 'DAWGS', 'CHAMINATION', '44/6', 'CHAMINATION Won by 4 Wickets (DLS)', '2026-01-27 23:37:49'),
(90, 'Final Trophy Match', 'Colombo Strikers', 'Kandy Warriors', '40/9', 'Kandy Warriors Won by 1 Wickets (DLS)', '2026-01-27 23:40:46');

-- --------------------------------------------------------

--
-- Table structure for table `match_live`
--

DROP TABLE IF EXISTS `match_live`;
CREATE TABLE IF NOT EXISTS `match_live` (
  `match_id` int NOT NULL,
  `team_a_id` int DEFAULT NULL,
  `team_b_id` int DEFAULT NULL,
  `total_runs` int DEFAULT '0',
  `wickets` int DEFAULT '0',
  `overs` decimal(4,1) DEFAULT '0.0',
  `striker_id` int DEFAULT NULL,
  `non_striker_id` int DEFAULT NULL,
  `status` varchar(20) DEFAULT 'SCHEDULED',
  `result` varchar(255) DEFAULT NULL,
  `match_name` varchar(100) DEFAULT 'Friendly Match',
  `batting_team_id` int DEFAULT NULL,
  `bowling_team_id` int DEFAULT NULL,
  `current_bowler_id` int DEFAULT NULL,
  `extras` int DEFAULT '0',
  `total_legal_balls` int DEFAULT '0',
  `last_bowler_id` int DEFAULT NULL,
  `innings_no` int DEFAULT '1',
  `target` int DEFAULT '0',
  `inn1_runs` int DEFAULT '0',
  `inn1_wickets` int DEFAULT '0',
  `inn1_overs` decimal(4,1) DEFAULT '0.0',
  `is_saved` int DEFAULT '0',
  `is_dls` int DEFAULT '0',
  PRIMARY KEY (`match_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `match_live`
--

INSERT INTO `match_live` (`match_id`, `team_a_id`, `team_b_id`, `total_runs`, `wickets`, `overs`, `striker_id`, `non_striker_id`, `status`, `result`, `match_name`, `batting_team_id`, `bowling_team_id`, `current_bowler_id`, `extras`, `total_legal_balls`, `last_bowler_id`, `innings_no`, `target`, `inn1_runs`, `inn1_wickets`, `inn1_overs`, `is_saved`, `is_dls`) VALUES
(1, 4, 5, 37, 7, 0.0, 37, 35, 'COMPLETED', 'DAWGS WON!', 'Final Trophy Match', 5, 4, 27, 9, 49, NULL, 2, 36, 45, 7, 0.0, 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `match_players`
--

DROP TABLE IF EXISTS `match_players`;
CREATE TABLE IF NOT EXISTS `match_players` (
  `match_id` int DEFAULT NULL,
  `player_id` int DEFAULT NULL,
  `team_id` int DEFAULT NULL,
  `is_captain` tinyint DEFAULT '0',
  `is_keeper` tinyint DEFAULT '0',
  `status` varchar(20) DEFAULT 'Yet to Bat',
  `runs_scored` int DEFAULT '0',
  `balls_faced` int DEFAULT '0',
  `fours` int DEFAULT '0',
  `sixes` int DEFAULT '0',
  `wickets_taken` int DEFAULT '0',
  `overs_bowled` decimal(4,1) DEFAULT '0.0',
  `runs_conceded` int DEFAULT '0',
  `how_out` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `match_players`
--

INSERT INTO `match_players` (`match_id`, `player_id`, `team_id`, `is_captain`, `is_keeper`, `status`, `runs_scored`, `balls_faced`, `fours`, `sixes`, `wickets_taken`, `overs_bowled`, `runs_conceded`, `how_out`) VALUES
(1, 18, 4, 0, 0, 'Out', 1, 6, 0, 0, 2, 2.0, 5, 'LBW'),
(1, 19, 4, 0, 0, 'Out', 5, 8, 1, 0, 2, 2.0, 12, 'Caught'),
(1, 20, 4, 0, 0, 'Out', 0, 3, 0, 0, 0, 0.0, 0, 'Caught'),
(1, 21, 4, 0, 0, 'Out', 4, 2, 1, 0, 0, 1.0, 2, 'Caught'),
(1, 22, 4, 0, 0, 'Out', 2, 8, 0, 0, 0, 0.0, 0, 'Stumped'),
(1, 23, 4, 0, 0, 'Batting', 13, 9, 1, 0, 0, 0.0, 0, NULL),
(1, 24, 4, 0, 0, 'Out', 1, 6, 0, 0, 0, 0.0, 0, 'Caught'),
(1, 25, 4, 0, 0, 'Out', 7, 16, 0, 0, 3, 2.0, 4, 'Bowled'),
(1, 26, 4, 0, 0, 'Batting', 5, 2, 1, 0, 0, 0.0, 0, NULL),
(1, 27, 4, 0, 0, 'Yet to Bat', 0, 0, 0, 0, 0, 0.1, 0, NULL),
(1, 28, 4, 0, 0, 'Yet to Bat', 0, 0, 0, 0, 0, 1.0, 5, NULL),
(1, 29, 5, 0, 0, 'Out', 1, 4, 0, 0, 0, 1.0, 2, 'Bowled'),
(1, 30, 5, 0, 0, 'Out', 0, 1, 0, 0, 2, 2.0, 8, 'Stumped'),
(1, 31, 5, 0, 0, 'Out', 10, 12, 2, 0, 0, 1.0, 8, 'Stumped'),
(1, 32, 5, 0, 0, 'Out', 7, 16, 0, 0, 0, 1.0, 1, 'Caught'),
(1, 33, 5, 0, 0, 'Out', 4, 5, 0, 0, 0, 0.0, 0, 'Caught'),
(1, 34, 5, 0, 0, 'Out', 5, 4, 1, 0, 2, 2.0, 4, 'Caught'),
(1, 35, 5, 0, 0, 'Batting', 0, 1, 0, 0, 1, 1.0, 8, NULL),
(1, 36, 5, 0, 0, 'Out', 0, 4, 0, 0, 0, 0.0, 0, 'Caught'),
(1, 37, 5, 0, 0, 'Batting', 1, 2, 0, 0, 2, 2.0, 7, NULL),
(1, 38, 5, 0, 0, 'Yet to Bat', 0, 0, 0, 0, 0, 0.0, 0, NULL),
(1, 39, 5, 0, 0, 'Yet to Bat', 0, 0, 0, 0, 0, 0.0, 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

DROP TABLE IF EXISTS `players`;
CREATE TABLE IF NOT EXISTS `players` (
  `player_id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `team_id` int DEFAULT NULL,
  `role` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`player_id`),
  KEY `team_id` (`team_id`)
) ENGINE=MyISAM AUTO_INCREMENT=77 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`player_id`, `name`, `team_id`, `role`) VALUES
(1, 'KS1', 1, 'Batsman'),
(2, 'KS2', 1, 'Batsman'),
(3, 'KS3', 1, 'Batsman'),
(4, 'SP1', 2, 'Batsman'),
(5, 'SP2', 2, 'Batsman'),
(6, 'SP3', 2, 'Batsman'),
(7, 'CN1', 3, 'All-Rounder'),
(8, 'CN2', 3, 'All-Rounder'),
(9, 'CN3', 3, 'All-Rounder'),
(10, 'CN4', 3, 'All-Rounder'),
(11, 'CN5', 3, 'All-Rounder'),
(12, 'CN6', 3, 'All-Rounder'),
(13, 'CN7', 3, 'All-Rounder'),
(14, 'CN8', 3, 'All-Rounder'),
(15, 'CN9', 3, 'All-Rounder'),
(16, 'CN10', 3, 'All-Rounder'),
(17, 'CN11', 3, 'All-Rounder'),
(18, 'PR1', 4, 'Batsman'),
(19, 'PR2', 4, 'Batsman'),
(20, 'PR3', 4, 'Batsman'),
(21, 'PR4', 4, 'Batsman'),
(22, 'PR5', 4, 'Batsman'),
(23, 'PR6', 4, 'Batsman'),
(24, 'PR7', 4, 'Batsman'),
(25, 'PR8', 4, 'Batsman'),
(26, 'PR9', 4, 'Batsman'),
(27, 'PR10', 4, 'Batsman'),
(28, 'PR11', 4, 'Batsman'),
(29, 'DAW1', 5, 'All-Rounder'),
(30, 'DAW2', 5, 'All-Rounder'),
(31, 'DAW3', 5, 'All-Rounder'),
(32, 'DAW4', 5, 'All-Rounder'),
(33, 'DAW5', 5, 'All-Rounder'),
(34, 'DAW6', 5, 'All-Rounder'),
(35, 'DAW7', 5, 'All-Rounder'),
(36, 'DAW8', 5, 'All-Rounder'),
(37, 'DAW9', 5, 'All-Rounder'),
(38, 'DAW10', 5, 'All-Rounder'),
(39, 'DAW11', 5, 'All-Rounder'),
(40, 'Pathum Nissanka', 6, 'All-Rounder'),
(41, 'Kusal Mendis', 6, 'All-Rounder'),
(42, 'Sadeera Samarawickrama', 6, 'All-Rounder'),
(43, 'Charith Asalanka', 6, 'All-Rounder'),
(44, 'Angelo Mathews', 6, 'All-Rounder'),
(45, 'Dasun Shanaka', 6, 'All-Rounder'),
(46, 'Wanindu Hasaranga', 6, 'All-Rounder'),
(47, 'Maheesh Theekshana', 6, 'All-Rounder'),
(48, 'Binura Fernando', 6, 'All-Rounder'),
(49, 'Asitha Fernando', 6, 'All-Rounder'),
(50, 'Dilshan Madushanka', 6, 'All-Rounder'),
(51, 'Avishka Fernando', 7, 'All-Rounder'),
(52, 'Dimuth Karunaratne', 7, 'All-Rounder'),
(53, 'Kusal Perera', 7, 'All-Rounder'),
(54, 'Dinesh Chandimal', 7, 'All-Rounder'),
(55, 'Kamindu Mendis', 7, 'All-Rounder'),
(56, 'Dhananjaya de Silva', 7, 'All-Rounder'),
(57, 'Dunith Wellalage', 7, 'All-Rounder'),
(58, 'Chamika Karunaratne', 7, 'All-Rounder'),
(59, 'Dushmantha Chameera', 7, 'All-Rounder'),
(60, 'Lahiru Kumara', 7, 'All-Rounder'),
(61, 'Jeffrey Vandersay', 7, 'All-Rounder'),
(62, 'test', 14, 'All-Rounder'),
(63, 'Test Player 1', 9, 'Batsman'),
(64, 'DNC 1', 9, 'All-Rounder'),
(65, 'DNC 2', 9, 'All-Rounder'),
(66, 'DNC 3', 9, 'All-Rounder'),
(67, 'DNC 4', 9, 'All-Rounder'),
(68, 'DNC 5', 9, 'All-Rounder'),
(69, 'DNC 6', 9, 'All-Rounder'),
(70, 'DNC 6', 9, 'All-Rounder'),
(71, 'DNC 7', 9, 'All-Rounder'),
(72, 'DNC 8', 9, 'All-Rounder'),
(73, 'Ishan', 9, 'All-Rounder'),
(74, 'Thisaru', 9, 'All-Rounder'),
(75, 'Charith', 9, 'All-Rounder'),
(76, 'Sagala', 9, 'All-Rounder');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `team_id` int NOT NULL AUTO_INCREMENT,
  `team_name` varchar(50) NOT NULL,
  `short_code` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`team_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`team_id`, `team_name`, `short_code`) VALUES
(1, 'KALU SINHAYO', 'KS'),
(2, 'SUDU PUSO', 'SP'),
(3, 'CHAMINATION', 'CN'),
(4, 'POWER RANGERS', 'PR'),
(5, 'DAWGS', 'DG'),
(6, 'Colombo Strikers', 'CS'),
(7, 'Kandy Warriors', 'KW'),
(8, 'GAN KABARAYO', 'GK'),
(9, 'DNC', 'DN'),
(10, 'UoVT', 'Uo'),
(25, 'Colombo Kings', 'CK');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
