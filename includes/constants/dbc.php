
<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// information about the SQL database -- make sure the database on your end matches the dataase name, the user and the password
define('DB_HOST', "localhost");
define('DB_USER', "hci573");
define('DB_PASS', "hci573");
define('DB_NAME', "hci573");

//include_once '/includes/constants/dbc.php';
//base in operating system
define ("ROOT", $_SERVER['DOCUMENT_ROOT'] . "/havyaka_culture");

//base URL of site
define ("BASE", "http://".$_SERVER['HTTP_HOST']."/havyaka_culture");
//base in operating system

//tables
define ("PSTORE","pstore");

define ("CHEF", "chef");
define ("COMMUNITY_TYPE", "community");
define ("EVENT", "event");
define ("EVENT_PICTURE", "event_picture");
define ("EVENT_TYPE", "event_type");
define ("FOOD", "food");
define ("FOOD_CHEF_DETAILS", "food_chef_details");
define ("LOCATION", "location");
define ("USERS", "user");
define ("USER_SAVED_INFO", "user_saved_info");
define ("VENUE", "venue");
define ("ATTENDENCE","event_attendance");
define ("EVENT_RECURRENCE", "event_recurrence");

define ("GLOBAL_EMAIL", "connect.community.culture@gmail.com");
define("REQUIRE_ACTIVIATION","0");

//our keys -- ideally, those would be stored on a separate machine or server
$salt = "ae4bca65f3283fe26a6d3b10b85c3a308";
global $salt;

$passsalt = "f576c07dbe00e8f07d463bc14dede9e492";
global $passsalt;

$password_store_key = sha1("dsf4dgfd5s2");
global $password_store_key;

//  password :,connectcommunity1;
?>