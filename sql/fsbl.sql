-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: db5000376810.hosting-data.io
-- Generation Time: Apr 23, 2020 at 01:36 PM
-- Server version: 5.7.28-log
-- PHP Version: 7.0.33-0+deb9u7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fsbl`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_area`
--

CREATE TABLE `admin_area` (
  `id` int(10) NOT NULL,
  `area_name` varchar(100) NOT NULL,
  `display_order` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_area`
--

INSERT INTO `admin_area` (`id`, `area_name`, `display_order`) VALUES
(1, 'Menus', 10),
(2, 'Divisions', 5),
(3, 'General', 10),
(4, 'Access', 100),
(5, 'Scoring', 4);

-- --------------------------------------------------------

--
-- Table structure for table `admin_controls`
--

CREATE TABLE `admin_controls` (
  `id` int(10) NOT NULL,
  `var` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `area_id` int(10) NOT NULL,
  `active` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `locked` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_by_user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_controls`
--

INSERT INTO `admin_controls` (`id`, `var`, `name`, `description`, `area_id`, `active`, `value`, `locked`, `last_updated`, `updated_by_user_id`) VALUES
(1, 'MENU_STATS', 'Stats Menu', 'Activates the Stats menu', 1, '1', '1', '0', '2016-08-07 17:49:46', 1),
(2, 'NUM_TEAMS_PROMOTED', 'Number Promoted Teams/Players', 'The number of rows to highlight at the top of the league table as being in a promotion position.', 2, '1', '1', '0', '2016-10-26 19:22:54', 1),
(3, 'MENU_SEASON', 'Season Menu', 'Activates the Season menu', 1, '1', '1', '1', '2016-08-07 17:49:49', 1),
(4, 'MENU_TEAMS', 'Team Menu', 'Activates the Teams menu', 1, '1', '1', '0', '2016-08-07 17:49:53', 1),
(5, 'MENU_HALL_OF_FAME', 'Hall of Fame Menu', 'Activates the Hall of Fame menu', 1, '1', '1', '0', '2016-08-07 17:51:36', 1),
(6, 'MENU_SPORTSBOOK', 'Sportsbook menu', 'Activates the Sportsbook menu', 1, '0', '0', '0', '2020-04-11 16:51:54', 1),
(7, 'MENU_LOGOUT', 'Log out Menu', 'Activates the Log out menu', 1, '1', '1', '0', '2016-08-07 17:49:50', 1),
(8, 'MENU_HALL_OF_FAME', 'Hall of Fame Menu', 'Activates the Hall of Fame menu', 1, '1', '1', '0', '2016-08-07 17:59:34', 1),
(9, 'MENU_SPORTSBOOK', 'Sportsbook menu', 'Activates the Sportsbook menu', 1, '1', '1', '0', '2016-08-07 17:49:55', 1),
(10, 'MENU_GALLERY', 'Gallery Menu', 'Activates the Gallery menu', 1, '1', '1', '0', '2016-08-07 17:49:54', 1),
(11, 'MENU_HELP', 'Help Menu', 'Activates the Help Menu', 1, '1', '1', '0', '2016-08-07 17:50:00', 1),
(12, 'MENU_ADMIN', 'Admin Menu', 'Activates the Admin menu', 1, '1', '1', '0', '2016-08-07 17:49:59', 1),
(13, 'MENU_FIXTURES', 'Fixtures menu', 'Activates the Fixtures menu', 1, '1', '1', '0', '2016-08-07 17:49:58', 1),
(14, 'MENU_RULES', 'Rules Menu', 'Activates the rules menu', 1, '1', '1', '0', '2016-08-07 17:50:02', 1),
(15, 'SESSION_TIMEOUT_IN_SECONDS', 'Session Timeout', 'The amount of seconds a user session is kept alive. If a user does not make any activity in this time they will be forced to log back in. \n1800 = 3 mins.\nThis must be active at all times, therefore, if you change to inactive, it will be still be \nactive.', 4, '1', '342225', '0', '2016-08-07 18:52:45', 1),
(16, 'SITE_NAME', 'Site Name', 'The name of the website. This will appear as the website title.', 3, '1', 'Office Fussball', '0', '2020-04-07 09:44:58', 1),
(17, 'NUM_TEAMS_RELEGATED', 'Number Relegated Teams/Players', 'The number of rows to highlight at the bottom of the league table as being in a relegation position.', 2, '1', '1', '0', '2016-10-26 19:22:54', 1),
(18, 'HOME_PAGE_RECENT_RESULTS', 'Homepage results', 'The number of homepage recent results to display', 3, '1', '3', '0', '2016-08-07 18:40:49', 1),
(19, 'LDAP_ACTIVE', 'LDAP Access', 'Activates the LDAP (login via Active Directory) setting', 4, '0', '0', '0', '2016-08-07 17:50:06', 1),
(20, 'FIRST_TO_GOALS', 'First to X goals', 'All scores are validated to ensure at least one side reaches this total.\r\n0 = deactivated', 5, '0', '10', '0', '2016-08-07 18:36:05', 1);

-- --------------------------------------------------------

--
-- Table structure for table `competitionType`
--

CREATE TABLE `competitionType` (
  `competitionId` int(11) NOT NULL,
  `competitionName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `competitionType`
--

INSERT INTO `competitionType` (`competitionId`, `competitionName`) VALUES
(1, 'League'),
(2, 'Cup');

-- --------------------------------------------------------

--
-- Table structure for table `division`
--

CREATE TABLE `division` (
  `divisionId` int(10) NOT NULL,
  `divisionName` varchar(255) NOT NULL,
  `divisionShortName` varchar(10) NOT NULL,
  `divisionOrder` int(3) NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `division`
--

INSERT INTO `division` (`divisionId`, `divisionName`, `divisionShortName`, `divisionOrder`) VALUES
(1, 'Premiership', 'Prem.', 1),
(2, 'Championship', 'Champ.', 10),
(3, 'Division 1', 'Div1.', 20),
(4, 'Division 2', 'Div 2', 30),
(5, 'National League', 'Nat L.', 40);

-- --------------------------------------------------------

--
-- Table structure for table `fixture`
--

CREATE TABLE `fixture` (
  `fixtureId` int(10) NOT NULL,
  `competitionId` int(10) NOT NULL,
  `homeTeamId` int(11) NOT NULL,
  `awayTeamId` int(10) NOT NULL,
  `statusId` int(1) NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `fixture`
--

INSERT INTO `fixture` (`fixtureId`, `competitionId`, `homeTeamId`, `awayTeamId`, `statusId`, `lastUpdated`) VALUES
(1, 1, 1, 2, 2, '2020-04-11 16:28:17'),
(2, 1, 1, 3, 2, '2020-04-11 16:28:22'),
(3, 1, 2, 3, 2, '2020-04-11 16:28:28'),
(4, 1, 2, 1, 2, '2020-04-11 16:28:34'),
(5, 1, 3, 1, 2, '2020-04-11 15:56:44'),
(6, 1, 3, 2, 2, '2020-04-11 16:29:00'),
(31, 1, 2, 1, 2, '2020-04-26 13:20:00'),
(32, 1, 2, 3, 2, '2020-04-26 13:20:07'),
(33, 1, 1, 3, 2, '2020-04-26 13:20:13'),
(34, 1, 1, 2, 2, '2020-04-11 16:50:16'),
(35, 1, 3, 2, 2, '2020-04-26 13:20:19'),
(36, 1, 3, 1, 2, '2020-04-26 13:20:27'),
(57, 1, 3, 1, 2, '2020-04-26 13:31:37'),
(58, 1, 52, 2, 2, '2020-04-26 13:31:45'),
(59, 1, 2, 3, 2, '2020-04-26 13:32:07'),
(60, 1, 52, 1, 2, '2020-04-26 13:32:14'),
(61, 1, 3, 52, 2, '2020-04-26 13:32:35'),
(62, 1, 2, 1, 2, '2020-04-26 13:34:03'),
(63, 1, 1, 3, 2, '2020-04-26 13:33:38'),
(64, 1, 2, 52, 2, '2020-04-26 13:33:15'),
(65, 1, 3, 2, 2, '2020-04-26 13:33:24'),
(66, 1, 1, 52, 2, '2020-04-26 13:34:41'),
(67, 1, 52, 3, 1, '2020-04-26 13:31:23'),
(68, 1, 1, 2, 2, '2020-04-26 13:35:54'),
(69, 1, 53, 56, 2, '2020-04-26 13:31:53'),
(70, 1, 55, 54, 2, '2020-04-26 13:32:01'),
(71, 1, 54, 53, 2, '2020-04-26 13:32:22'),
(72, 1, 55, 56, 2, '2020-04-26 13:32:28'),
(73, 1, 53, 55, 2, '2020-04-26 13:32:41'),
(74, 1, 54, 56, 2, '2020-04-26 13:33:07'),
(75, 1, 56, 53, 2, '2020-04-26 13:34:13'),
(76, 1, 54, 55, 2, '2020-04-26 13:33:31'),
(77, 1, 53, 54, 2, '2020-04-26 13:34:51'),
(78, 1, 56, 55, 2, '2020-04-26 13:35:17'),
(79, 1, 55, 53, 1, '2020-04-26 13:31:23'),
(80, 1, 56, 54, 1, '2020-04-26 13:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `fixtureTmp`
--

CREATE TABLE `fixtureTmp` (
  `fixtureId` int(10) NOT NULL,
  `competitionId` int(10) NOT NULL,
  `homeTeamId` int(10) NOT NULL,
  `awayTeamId` int(10) NOT NULL,
  `statusId` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `leagueFixture`
--

CREATE TABLE `leagueFixture` (
  `leagueFixtureId` int(10) NOT NULL,
  `seasonId` int(10) NOT NULL,
  `divisionId` int(3) NOT NULL,
  `fixtureId` int(10) NOT NULL,
  `gameweek` int(5) NOT NULL,
  `homeScore` int(2) DEFAULT NULL,
  `awayScore` int(2) DEFAULT NULL,
  `homeWinPts` int(1) NOT NULL DEFAULT '0',
  `awayWinPts` int(1) NOT NULL DEFAULT '0',
  `homeGrannyPts` int(1) NOT NULL DEFAULT '0',
  `awayGrannyPts` int(1) NOT NULL DEFAULT '0',
  `homeLosePts` int(1) NOT NULL DEFAULT '0',
  `awayLosePts` int(1) NOT NULL DEFAULT '0',
  `team_a_provisional_score` int(2) DEFAULT NULL,
  `team_b_provisional_score` int(2) DEFAULT NULL,
  `admin_verified` int(10) NOT NULL DEFAULT '0',
  `provisional_score_added_by_team_id` int(10) NOT NULL DEFAULT '0',
  `provisional_score_verified_by_team_id` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `leagueFixture`
--

INSERT INTO `leagueFixture` (`leagueFixtureId`, `seasonId`, `divisionId`, `fixtureId`, `gameweek`, `homeScore`, `awayScore`, `homeWinPts`, `awayWinPts`, `homeGrannyPts`, `awayGrannyPts`, `homeLosePts`, `awayLosePts`, `team_a_provisional_score`, `team_b_provisional_score`, `admin_verified`, `provisional_score_added_by_team_id`, `provisional_score_verified_by_team_id`) VALUES
(1, 1, 5, 1, 1, 10, 2, 3, 0, 0, 0, 0, 0, 1, 10, 1, 1, 0),
(2, 1, 5, 2, 2, 10, 9, 2, 0, 0, 0, 0, 1, NULL, NULL, 1, 0, 0),
(3, 1, 5, 3, 3, 10, 6, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(4, 1, 5, 4, 4, 9, 10, 0, 2, 0, 0, 1, 0, NULL, NULL, 1, 0, 0),
(5, 1, 5, 5, 5, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(6, 1, 5, 6, 6, 5, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(15, 2, 5, 31, 1, 3, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(16, 2, 5, 32, 2, 5, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(17, 2, 5, 33, 3, 10, 9, 2, 0, 0, 0, 0, 1, NULL, NULL, 1, 0, 0),
(18, 2, 5, 34, 4, 10, 9, 2, 0, 0, 0, 0, 1, NULL, NULL, 1, 0, 0),
(19, 2, 5, 35, 5, 10, 7, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(20, 2, 5, 36, 6, 9, 10, 0, 2, 0, 0, 1, 0, NULL, NULL, 1, 0, 0),
(21, 3, 1, 57, 1, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(22, 3, 1, 58, 1, 6, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(23, 3, 1, 59, 2, 10, 9, 2, 0, 0, 0, 0, 1, NULL, NULL, 1, 0, 0),
(24, 3, 1, 60, 2, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(25, 3, 1, 61, 3, 8, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(26, 3, 1, 62, 3, 6, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(27, 3, 1, 63, 4, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(28, 3, 1, 64, 4, 10, 0, 3, 0, 1, 0, 0, 0, NULL, NULL, 1, 0, 0),
(29, 3, 1, 65, 5, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(30, 3, 1, 66, 5, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(31, 3, 1, 67, 6, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0, 0),
(32, 3, 1, 68, 6, 9, 10, 0, 2, 0, 0, 1, 0, NULL, NULL, 1, 0, 0),
(33, 3, 2, 69, 1, 10, 7, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(34, 3, 2, 70, 1, 10, 9, 2, 0, 0, 0, 0, 1, NULL, NULL, 1, 0, 0),
(35, 3, 2, 71, 2, 10, 5, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(36, 3, 2, 72, 2, 10, 7, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(37, 3, 2, 73, 3, 9, 10, 0, 2, 0, 0, 1, 0, NULL, NULL, 1, 0, 0),
(38, 3, 2, 74, 3, 10, 7, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(39, 3, 2, 75, 4, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(40, 3, 2, 76, 4, 8, 10, 0, 3, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(41, 3, 2, 77, 5, 10, 8, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(42, 3, 2, 78, 5, 10, 6, 3, 0, 0, 0, 0, 0, NULL, NULL, 1, 0, 0),
(43, 3, 2, 79, 6, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0, 0),
(44, 3, 2, 80, 6, NULL, NULL, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `leagueFixtureTmp`
--

CREATE TABLE `leagueFixtureTmp` (
  `leagueFixtureId` int(10) NOT NULL,
  `seasonId` int(10) NOT NULL,
  `divisionId` int(3) NOT NULL,
  `fixtureId` int(10) NOT NULL,
  `gameweek` int(5) NOT NULL,
  `homeScore` int(2) DEFAULT NULL,
  `awayScore` int(2) DEFAULT NULL,
  `homeWinPts` int(1) NOT NULL DEFAULT '0',
  `awayWinPts` int(1) NOT NULL DEFAULT '0',
  `homeGrannyPts` int(1) NOT NULL DEFAULT '0',
  `awayGrannyPts` int(1) NOT NULL DEFAULT '0',
  `homeLosePts` int(1) NOT NULL DEFAULT '0',
  `awayLosePts` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `season`
--

CREATE TABLE `season` (
  `seasonId` int(5) NOT NULL,
  `seasonName` varchar(200) NOT NULL,
  `statusId` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `season`
--

INSERT INTO `season` (`seasonId`, `seasonName`, `statusId`) VALUES
(1, '1', 2),
(2, '2', 2),
(3, '3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusId` int(11) NOT NULL,
  `statusName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`statusId`, `statusName`) VALUES
(1, 'Open'),
(2, 'Closed'),
(3, 'Postponed'),
(4, 'Cancelled');

-- --------------------------------------------------------

--
-- Table structure for table `team`
--

CREATE TABLE `team` (
  `teamId` int(11) NOT NULL,
  `teamName` varchar(255) NOT NULL,
  `teamType` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamId`, `teamName`, `teamType`) VALUES
(1, 'Johnny', 1),
(2, 'Flash', 2),
(3, 'Steph', 3),
(52, 'Eddie', 1),
(53, 'Frankie', 1),
(54, 'Davey', 1),
(55, 'SallyB', 1),
(56, 'Neil', 1);

-- --------------------------------------------------------

--
-- Table structure for table `teamType`
--

CREATE TABLE `teamType` (
  `teamTypeId` int(11) NOT NULL,
  `teamTypeName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teamType`
--

INSERT INTO `teamType` (`teamTypeId`, `teamTypeName`) VALUES
(1, 'Singles'),
(2, 'Doubles');

-- --------------------------------------------------------

--
-- Table structure for table `teamUser`
--

CREATE TABLE `teamUser` (
  `teamUserId` int(11) NOT NULL,
  `teamId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teamUser`
--

INSERT INTO `teamUser` (`teamUserId`, `teamId`, `userId`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(52, 52, 62),
(53, 53, 63),
(54, 54, 64),
(55, 55, 65),
(56, 56, 66);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(5) NOT NULL,
  `ldapUsername` varchar(200) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstName` varchar(200) NOT NULL,
  `lastName` varchar(200) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `admin` enum('y','n') NOT NULL DEFAULT 'n',
  `committee` enum('y','n') NOT NULL DEFAULT 'n',
  `dateLastLoggedIn` datetime DEFAULT NULL,
  `waitingToJoin` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `ldapUsername`, `email`, `firstName`, `lastName`, `nickname`, `admin`, `committee`, `dateLastLoggedIn`, `waitingToJoin`) VALUES
(1, 'demouser', 'john@example.com', 'John', 'doe', 'DemoUser', 'y', 'y', '2020-04-26 14:18:35', 0),
(2, 'mcintste', 'steve@example.com', 'Steve', 'Smith', 'Flashman', 'y', 'y', '2016-07-26 23:38:11', 0),
(3, 'steffyj', 'steph@example.com', 'Steph', 'Little', 'Spiller', 'y', 'y', '2016-07-26 23:38:11', 0),
(62, 'eddiett', 'eddie@example.com', 'Edward', 'Smith', 'Eddie', 'y', 'n', NULL, 0),
(63, 'frankfur', 'frank@example.com', 'Frankie', 'Fish', 'Fishie', 'y', 'n', NULL, 0),
(64, 'davers', 'dave@example.com', 'Dave', 'Weathers', 'Davey', 'n', 'n', NULL, 0),
(65, 'bootsal', 'Sally@example.com', 'Sally', 'Boot', 'SallyB', 'n', 'n', NULL, 0),
(66, 'rampnei', 'neil@example.com', 'Neil', 'Ramps', 'Neil', 'n', 'n', NULL, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_area`
--
ALTER TABLE `admin_area`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_controls`
--
ALTER TABLE `admin_controls`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `competitionType`
--
ALTER TABLE `competitionType`
  ADD PRIMARY KEY (`competitionId`);

--
-- Indexes for table `division`
--
ALTER TABLE `division`
  ADD PRIMARY KEY (`divisionId`);

--
-- Indexes for table `fixture`
--
ALTER TABLE `fixture`
  ADD PRIMARY KEY (`fixtureId`);

--
-- Indexes for table `fixtureTmp`
--
ALTER TABLE `fixtureTmp`
  ADD PRIMARY KEY (`fixtureId`);

--
-- Indexes for table `leagueFixture`
--
ALTER TABLE `leagueFixture`
  ADD PRIMARY KEY (`leagueFixtureId`);

--
-- Indexes for table `leagueFixtureTmp`
--
ALTER TABLE `leagueFixtureTmp`
  ADD PRIMARY KEY (`leagueFixtureId`);

--
-- Indexes for table `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`seasonId`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`statusId`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`teamId`),
  ADD UNIQUE KEY `teamName` (`teamName`);

--
-- Indexes for table `teamType`
--
ALTER TABLE `teamType`
  ADD PRIMARY KEY (`teamTypeId`);

--
-- Indexes for table `teamUser`
--
ALTER TABLE `teamUser`
  ADD PRIMARY KEY (`teamUserId`),
  ADD UNIQUE KEY `teamId` (`teamId`,`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_area`
--
ALTER TABLE `admin_area`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `admin_controls`
--
ALTER TABLE `admin_controls`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `competitionType`
--
ALTER TABLE `competitionType`
  MODIFY `competitionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `division`
--
ALTER TABLE `division`
  MODIFY `divisionId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `fixture`
--
ALTER TABLE `fixture`
  MODIFY `fixtureId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `fixtureTmp`
--
ALTER TABLE `fixtureTmp`
  MODIFY `fixtureId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leagueFixture`
--
ALTER TABLE `leagueFixture`
  MODIFY `leagueFixtureId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `leagueFixtureTmp`
--
ALTER TABLE `leagueFixtureTmp`
  MODIFY `leagueFixtureId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `season`
--
ALTER TABLE `season`
  MODIFY `seasonId` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `teamId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `teamType`
--
ALTER TABLE `teamType`
  MODIFY `teamTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teamUser`
--
ALTER TABLE `teamUser`
  MODIFY `teamUserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
