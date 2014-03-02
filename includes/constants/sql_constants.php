<?php

/* This file contains variables defining the database for the Havyaka culture site and functions to manipulate the database. */

//information about the SQL database -- make sure the database on your end matches the dataase name, the user and the password
define('DB_HOST', "localhost");
define('DB_USER', "hci573");
define('DB_PASS', "hci573");
define('DB_NAME', "hci573");

//table names
$users_table = "users_table_havyaka_culture";
$messages_table = "messages_table_havyaka_culture";


//connect to the SQL server and select the database - we can now use $link and $db in pages that include this page
$link = mysql_connect(DB_HOST, DB_USER, DB_PASS) or die("Couldn't make connection:" . mysql_error() );
$db = mysql_select_db(DB_NAME, $link) or die("Couldn't select database:" . mysql_error() );

?>