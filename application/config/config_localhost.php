<?php

/**
 * Configuration
 *
 * For more info about constants please @see http://php.net/manual/en/function.define.php
 */

/**
 * Configuration for: Error reporting
 * Useful to show every little problem during development, but only show hard errors in production
 */
error_reporting(E_ALL);
ini_set("display_errors", 1);
ini_set('max_execution_time', 300);
/**
 * Set timezone
 */
date_default_timezone_set('Europe/Isle_of_Man');

define('URL_PUBLIC_FOLDER', 'public');
define('URL_PROTOCOL', 'http://');
define('URL_DOMAIN', $_SERVER['HTTP_HOST']);
define('URL_SUB_FOLDER', str_replace(URL_PUBLIC_FOLDER, '', dirname($_SERVER['SCRIPT_NAME'])));
define('URL', URL_PROTOCOL . URL_DOMAIN . URL_SUB_FOLDER);

/**
 * Configuration for: Folders
 * Here you define where your folders are. Unless you have renamed them, there's no need to change this.
 */
define('LIBS_PATH', APP . 'libs/');
define('CONTROLLER_PATH', APP . 'controllers/');
define('MODELS_PATH', APP . 'model/');
define('DB_BACKUP_PATH', APP . 'db_backups/');

/**
 * Configuration for: Database
 * This is the place where you define your database credentials, database type etc.
 */

define('DB_TYPE', 'mysql');
define('DB_HOST', 'localhost');
define('DB_NAME', 'officeplay');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8');
/*
define('DB_TYPE', 'mysql');
define('DB_HOST', 'xxxx');
define('DB_NAME', 'xxxx');
define('DB_USER', 'xxxx');
define('DB_PASS', 'xxxx');
define('DB_CHARSET', 'utf8');
*/
//define('LDAP_ON',false);
//define('SESSION_TIMEOUT_IN_SECONDS', 360000); //1800 = 3 mins

//define('HOME_PAGE_RECENT_RESULTS', 25);
define('FIRST_TO_GOALS', 10); // The num of goals a player must reach to win
define('LEAGUE_WIN_GRANNY_PTS', 1);
define('LEAGUE_WIN_PTS', 3);
define('LEAGUE_WIN_PTS_CLOSE_GAME', 2);
define('LEAGUE_CLOSE_LOSE_PTS', 1);
define('CLOSE_GAME_GOALS', 1); // num of goals diff to be considered a close game

/*
 * DIVISIONS
 */
//define('NUM_TEAMS_PROMOTED', 2);
//define('NUM_TEAMS_RELEGATED', 2);

/* 
 * MENU ITEMS
 */
//define('MENU_ADMIN', 1);
//define('MENU_FIXTURES', 1);
//define('MENU_HELP', 1);
//define('MENU_GALLERY', 1);
//define('MENU_RULES', 1);
//define('MENU_LOGOUT', 1);
//define('MENU_SPORTSBOOK', 0);
//define('MENU_HALL_OF_FAME', 1);
//define('MENU_STATS', 1);
//define('MENU_SEASON', 0);
//define('MENU_TEAMS', 0);
