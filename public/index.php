<?php

/**
 * MINI - an extremely simple naked PHP application.
 *
 * @package mini
 * @author Panique
 * @link http://www.php-mini.com
 * @link https://github.com/panique/mini/
 * @license http://opensource.org/licenses/MIT MIT License
 */

// TODO get rid of this and work with namespaces + composer's autoloader

// set a constant that holds the project's folder path, like "/var/www/".
// DIRECTORY_SEPARATOR adds a slash to the end of the path
define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);
// set a constant that holds the project's "application" folder, like "/var/www/application".
define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

// This is the (totally optional) auto-loader for Composer-dependencies (to load tools into your project).
// If you have no idea what this means: Don't worry, you don't need it, simply leave it like it is.
if (file_exists(APP . 'config/autoload.php')) {
   require APP . 'config/autoload.php';
}

// load application config (error reporting etc.)
if($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1' || $_SERVER['REMOTE_ADDR'] == 'localhost') {
    require APP . 'config/config_localhost.php';
    //require APP . 'config/admin_settings_localhost.php';
} elseif($_SERVER['VirtualServerName'] == 'dev.csr.pstars' || $_SERVER['ServerName'] == 'dev.csr.pstars') {
    require APP . 'config/config.php';
    //require APP . 'config/admin_settings.php';
} else {
    require APP . 'config/config.php';
    
}



// FOR DEVELOPMENT: this loads PDO-debug, a simple function that shows the SQL query (when using PDO).
// If you want to load pdoDebug via Composer, then have a look here: https://github.com/panique/pdo-debug
require APP . 'libs/helper.php';

// load application class
require APP . 'core/application.php';
require APP . 'core/controller.php';
//require APP . 'config/admin_settings.php';

// start the application
$app = new Application();
