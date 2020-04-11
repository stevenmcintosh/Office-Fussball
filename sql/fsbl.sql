-- phpMyAdmin SQL Dump
-- version 4.9.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Apr 07, 2020 at 11:52 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET GLOBAL sql_mode = "";
--
-- Database: `fsbl`
--
CREATE DATABASE IF NOT EXISTS `fsbl` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fsbl`;

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
  `homeTeamId` int(11) DEFAULT NULL,
  `awayTeamId` int(10) NOT NULL,
  `statusId` int(1) NOT NULL,
  `lastUpdated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `fixture`
--



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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;


-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `statusId` int(11) NOT NULL,
  `statusName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

--
-- Dumping data for table `team`
--

INSERT INTO `team` (`teamId`, `teamName`, `teamType`) VALUES
(1, 'Johnny United', 1),
(2, 'Flasher United', 2),
(3, 'Spiller United', 3);

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
(3, 3, 3);

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
(1, 'adminusr', 'test1@test.com', 'John', 'doe', 'Johnny', 'y', 'y', '2016-07-26 23:38:11', 0),
(2, 'mcintste', 'test2@test.com', 'Steve', 'McIntosh', 'Flashman', 'y', 'y', '2016-07-26 23:38:11', 0),
(3, 'steffyj', 'test3@test.com', 'Steph', 'McIntosh', 'Spiller', 'y', 'y', '2016-07-26 23:38:11', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `competitionType`
--
ALTER TABLE `competitionType` ADD PRIMARY KEY (`competitionId`);

--
-- Indexes for table `division`
--
ALTER TABLE `division` ADD PRIMARY KEY (`divisionId`);

--
-- Indexes for table `fixture`
--
ALTER TABLE `fixture` ADD PRIMARY KEY (`fixtureId`);
-- ALTER TABLE `fixture` FORCE
--
-- Indexes for table `fixtureTmp`
--
ALTER TABLE `fixtureTmp` ADD PRIMARY KEY (`fixtureId`);

--
-- Indexes for table `leagueFixture`
--
ALTER TABLE `leagueFixture` ADD PRIMARY KEY (`leagueFixtureId`);

--
-- Indexes for table `leagueFixtureTmp`
--
ALTER TABLE `leagueFixtureTmp` ADD PRIMARY KEY (`leagueFixtureId`);

--
-- Indexes for table `season`
--
ALTER TABLE `season` ADD PRIMARY KEY (`seasonId`);

--
-- Indexes for table `status`
--
ALTER TABLE `status` ADD PRIMARY KEY (`statusId`);

--
-- Indexes for table `team`
--
ALTER TABLE `team` ADD PRIMARY KEY (`teamId`), ADD UNIQUE KEY `unique_teamName` (`teamName`);

--
-- Indexes for table `teamType`
--
ALTER TABLE `teamType` ADD PRIMARY KEY (`teamTypeId`);

--
-- Indexes for table `teamUser`
--
ALTER TABLE `teamUser` ADD PRIMARY KEY (`teamUserId`), ADD UNIQUE KEY `teamId` (`teamId`,`userId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user` ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

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
  MODIFY `fixtureId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `fixtureTmp`
--
ALTER TABLE `fixtureTmp`
  MODIFY `fixtureId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leagueFixture`
--
ALTER TABLE `leagueFixture`
  MODIFY `leagueFixtureId` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- AUTO_INCREMENT for table `leagueFixtureTmp`
--
ALTER TABLE `leagueFixtureTmp`
  MODIFY `leagueFixtureId` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `season`
--
ALTER TABLE `season`
  MODIFY `seasonId` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `status`
--
ALTER TABLE `status`
  MODIFY `statusId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `team`
--
ALTER TABLE `team`
  MODIFY `teamId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `teamType`
--
ALTER TABLE `teamType`
  MODIFY `teamTypeId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `teamUser`
--
ALTER TABLE `teamUser`
  MODIFY `teamUserId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

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
  `var` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `area_id` int(10) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `value` varchar(255) DEFAULT '0',
  `locked` enum('0','1') NOT NULL DEFAULT '0',
  `last_updated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_by_user_id` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `admin_controls`
--

INSERT INTO `admin_controls` (`id`, `var`, `name`, `description`, `area_id`, `active`, `value`, `locked`, `last_updated`, `updated_by_user_id`) VALUES
(1, 'MENU_STATS', 'Stats Menu', 'Activates the Stats menu', 1, '1', '1', '0', '2016-08-07 19:49:46', 1),
(2, 'NUM_TEAMS_PROMOTED', 'Number Promoted Teams/Players', 'The number of rows to highlight at the top of the league table as being in a promotion position.', 2, '1', '1', '0', '2016-10-26 21:22:54', 1),
(3, 'MENU_SEASON', 'Season Menu', 'Activates the Season menu', 1, '1', '1', '1', '2016-08-07 19:49:49', 1),
(4, 'MENU_TEAMS', 'Team Menu', 'Activates the Teams menu', 1, '1', '1', '0', '2016-08-07 19:49:53', 1),
(5, 'MENU_HALL_OF_FAME', 'Hall of Fame Menu', 'Activates the Hall of Fame menu', 1, '1', '1', '0', '2016-08-07 19:51:36', 1),
(6, 'MENU_SPORTSBOOK', 'Sportsbook menu', 'Activates the Sportsbook menu', 1, '1', '1', '0', '2020-04-06 22:10:14', 1),
(7, 'MENU_LOGOUT', 'Log out Menu', 'Activates the Log out menu', 1, '1', '1', '0', '2016-08-07 19:49:50', 1),
(8, 'MENU_HALL_OF_FAME', 'Hall of Fame Menu', 'Activates the Hall of Fame menu', 1, '1', '1', '0', '2016-08-07 19:59:34', 1),
(9, 'MENU_SPORTSBOOK', 'Sportsbook menu', 'Activates the Sportsbook menu', 1, '1', '1', '0', '2016-08-07 19:49:55', 1),
(10, 'MENU_GALLERY', 'Gallery Menu', 'Activates the Gallery menu', 1, '1', '1', '0', '2016-08-07 19:49:54', 1),
(11, 'MENU_HELP', 'Help Menu', 'Activates the Help Menu', 1, '1', '1', '0', '2016-08-07 19:50:00', 1),
(12, 'MENU_ADMIN', 'Admin Menu', 'Activates the Admin menu', 1, '1', '1', '0', '2016-08-07 19:49:59', 1),
(13, 'MENU_FIXTURES', 'Fixtures menu', 'Activates the Fixtures menu', 1, '1', '1', '0', '2016-08-07 19:49:58', 1),
(14, 'MENU_RULES', 'Rules Menu', 'Activates the rules menu', 1, '1', '1', '0', '2016-08-07 19:50:02', 1),
(15, 'SESSION_TIMEOUT_IN_SECONDS', 'Session Timeout', 'The amount of seconds a user session is kept alive. If a user does not make any activity in this time they will be forced to log back in. \n1800 = 3 mins.\nThis must be active at all times, therefore, if you change to inactive, it will be still be \nactive.', 4, '1', '342225', '0', '2016-08-07 20:52:45', 1),
(16, 'SITE_NAME', 'Site Name', 'The name of the website. This will appear as the website title.', 3, '1', 'Office Fussball', '0', '2020-04-07 11:44:58', 1),
(17, 'NUM_TEAMS_RELEGATED', 'Number Relegated Teams/Players', 'The number of rows to highlight at the bottom of the league table as being in a relegation position.', 2, '1', '1', '0', '2016-10-26 21:22:54', 1),
(18, 'HOME_PAGE_RECENT_RESULTS', 'Homepage results', 'The number of homepage recent results to display', 3, '1', '3', '0', '2016-08-07 20:40:49', 1),
(19, 'LDAP_ACTIVE', 'LDAP Access', 'Activates the LDAP (login via Active Directory) setting', 4, '0', '0', '0', '2016-08-07 19:50:06', 1),
(20, 'FIRST_TO_GOALS', 'First to X goals', 'All scores are validated to ensure at least one side reaches this total.\r\n0 = deactivated', 5, '0', '10', '0', '2016-08-07 20:36:05', 1);

-- --------------------------------------------------------
