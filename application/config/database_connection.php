<?php

/**
 * Q. What is this? 
 * A. It is a file to hold your Prod Database connection details. 
 * 
 * Q. Why is this created manually and not part of the repo? 
 * A. Because when you pull from the repo, you will overwrite the your DB settings everytime, so we create a file that the repo doesnt know about.
 * 
 * Q. Do I need to ensure the file name is "database_connection.php"
 * A. Yes.
 * 
 * Q. Where does the file go?
 * A. /office-fussball/application/config/database_connection.php
 * 
 */
$hostname = 'localhost';
$dbname = 'fsbl';
$dbuser = 'root';
$dbpass = 'root';
$dbcharset = 'utf8';
