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

// load DB connection if the file exists
if (file_exists(APP . 'config/database_connection.php')) {
    require APP . 'config/database_connection.php';
} else {
    exit('You must manually create a database_connection.php file. See the instructions on the GIT. \n\n
    https://github.com/stevenmcintosh/Office-Fussball');
}

define('DB_TYPE', 'mysql');
define('DB_HOST', $hostname);
define('DB_NAME', $dbname);
define('DB_USER', $dbuser);
define('DB_PASS', $dbpass);
define('DB_CHARSET', $dbcharset);

define('SESSION_TIMEOUT_IN_SECONDS', 360000); //1800 = 3 mins

define('HOME_PAGE_RECENT_RESULTS', 25);
//define('FIRST_TO_GOALS', 10); // The num of goals a player must reach to win
define('LEAGUE_WIN_GRANNY_PTS', 1);
define('LEAGUE_WIN_PTS', 3);
define('LEAGUE_WIN_PTS_CLOSE_GAME', 2);
define('LEAGUE_CLOSE_LOSE_PTS', 1);
define('CLOSE_GAME_GOALS', 1); // num of goals diff to be considered a close game

// load localhost if the file exists
if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == 'localhost') {
    require APP . 'config/config_localhost.php';
}