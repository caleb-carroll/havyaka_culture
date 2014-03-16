<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);
/* This file contains variables defining the database for the Havyaka culture site and functions to manipulate the database. */

// information about the SQL database -- make sure the database on your end matches the dataase name, the user and the password
define('DB_HOST', "localhost");
define('DB_USER', "hci573");
define('DB_PASS', "hci573");
define('DB_NAME', "hci573");

include_once 'includes/constants/dbc.php';
//base in operating system
define ("SITE_ROOT", $_SERVER['DOCUMENT_ROOT'] . "/Havyaka_community_project/havyaka_culture");

//base URL of site
define ("SITE_BASE", "http://".$_SERVER['HTTP_HOST']."/Havyaka_community_project/havyaka_culture");

//tables
define ("PSTORE_TABLE","pstore_nivi");
define ("USERS", "user");

define ("LOCATION", "location");
define("COMMUNITY_TYPE", "community_type");

define ("GLOBAL_EMAIL", "connect.community.culture@gmail.com");
define("REQUIRE_ACTIVIATION","1");
// connect to the SQL server and select the database - we can now use $link and $db in pages that include this page
$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Couldn't make connection:" . mysqli_error() );
$db = mysqli_select_db($link, DB_NAME) or die("Couldn't select database:" . mysqli_error() );


//our keys -- ideally, those would be stored on a separate machine or server
$salt = "ae4bca65f3283fe26a6d3b10b85c3a308";
global $salt;

$passsalt = "f576c07dbe00e8f07d463bc14dede9e492";
global $passsalt;

$password_store_key = sha1("dsf4dgfd5s2");
global $password_store_key;


/*Function to super sanitize anything going near our DBs*/
function filter($data){
	$data = trim(htmlentities(strip_tags($data)));

	if (get_magic_quotes_gpc())
	{
		$data = stripslashes($data);
	}

	$data = mysql_real_escape_string($data);
	return $data;
}

/*Function to easily output all our css, js, etc...*/
function return_meta($title = NULL, $keywords = NULL, $description = NULL){
	if(is_null($title))
	{
		$title = "Community Connect - Havyaka Community";
	}

	$meta = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                 <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
	<title>'.$title.'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="'.$keywords.'" />
	<meta name="description" content="'.$description.'" />
	<meta name="language" content="en-us" />
	<meta name="robots" content="index,follow" />
	<meta name="googlebot" content="index,follow" />
	<meta name="msnbot" content="index,follow" />
	<meta name="revisit-after" content="7 Days" />
	<meta name="url" content="'.BASE.'" />
	<meta name="copyright" content="Copyright '.date("Y").' Community Connect. All rights reserved." />
	<meta name="author" content="Your site name here" />
	<link rel="shortcut icon" type="image/x-icon" href="images/favicon.ico" />
	<link rel="stylesheet" type="text/css" media="all" href="'.BASE.'/includes/styles/styles.css" />
	
	';

	echo $meta;
}

/*Function to validate email addresses*/
function check_email($email){
		return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

/*Function to update user details*/
function hash_pass($pass){
	global $passsalt;
	$hashed = md5(sha1($pass));
	$hashed = crypt($hashed, $passsalt);
	$hashed = sha1(md5($hashed));
	return $hashed;
}


function add_user($firstname,$username,$password,$confirm_pass,$email,$city,$state,$zipcode,$date,$user_ip,$activation_code,$community_type)
{

  $msg = NULL;
  $err = array();
  global $salt;
  global $link;

         if(empty($firstname))
	{
		$err[] = "You must enter your name";
	} 
        if(strlen($firstname) < 2) $err[] = "You must enter your real name";
	if(empty($username))
	{
		$err[] = "You must enter a username";
	}
        if(strlen($username) < 4) $err[] = "username must be minimum of 4 letters";
	if(empty($password) || strlen($password) < 4)
	{
		$err[] = "You must enter a password";
	}
	if(empty($email) || !check_email($email))
	{
		$err[] = "Please enter a valid email address.";
	}
        if($password != $confirm_pass)
        {
            $err[] = "Password and confirm password do not match!";
        }

	if($stmt = mysqli_prepare($link, "SELECT username, email FROM ".USERS." WHERE username = '$username' OR email = AES_ENCRYPT('$email', '$salt')") or die(mysqli_error($link)))
        {
             
         //execute the query
            mysqli_stmt_execute($stmt);
            //store the result
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) > 0)
            {
                    $err[] = "User already exists";
            }
              mysqli_stmt_close($stmt);
        }

	if(empty($err))
	{
            
                //First insert the city, zipcode,state into location table.
                $q_loc = mysqli_query($link, "INSERT INTO ".LOCATION. " (city,zipcode,state) VALUES ('$city','$zipcode','$state')") or die(mysqli_error($link));
               //get the last inserted id from the location table
                $e_loc_id = mysqli_insert_id($link);
                 
                 //get the community id based on the community name
                $q = "SELECT community_id from ".COMMUNITY_TYPE. " WHERE community_name = '".$community_type. "' LIMIT 1";
               
                $query = mysqli_query($link,$q) or (die(mysqli_error($link)));
                $row = mysqli_fetch_assoc($query);
                $community_id = $row['community_id'];
                    
		$password = hash_pass($password);

		$query = "INSERT INTO ".USERS." (first_name, username, e_loc_id, user_password, email, registration_date, user_ip, activation_code,community_id) VALUES ('$firstname', '$username', $e_loc_id, '$password', AES_ENCRYPT('$email', '$salt'), '$date', '$user_ip', '$activation_code',$community_id)";
 
                if ($q1 = mysqli_query($link,$query))
                {
                
                    //Generate rough hash based on user id from above insertion statement
                    $user_id = mysqli_insert_id($link); //get the id of the last inserted item

                    $md5_id = md5($user_id);
                    mysqli_query($link, "UPDATE ".USERS." SET md5_id='$md5_id' WHERE id='$user_id'");

                } else 
                {
                    $err[] ="Something happened!, please try again!";
                }

		if(REQUIRE_ACTIVIATION != 1)
                {
                    echo "activation " .REQUIRE_ACTIVIATION;
                    //Build a message to email for confirmation
                     $message = "<p>Hi ".$firstname."!</p>
                                     <p>Thank you for registering with us. Here are your login details...<br />

                                     User ID: ".$username."<br />
                                     Email: ".$email."<br />
                                     Password: ".$_POST['password']."</p>

                                     <p>You must activate your account before you can actually do anything:<br />
                                     ".BASE."/users/activate.php?user=".$md5_id."&activ_code=".$activation_code."</p>

                                     <p>Thank You<br />

                                     Administrator<br />
                                     ".BASE."</p>";

                     //activate user by only through activation
                     // set the approved field to 0 to activate the account

                     $rs_activ = mysqli_query($link, "UPDATE ".USERS." SET approved='0' WHERE
                     md5_id='". $md5_id. "' AND activation_code = '" . $activation_code ."' ") or die(mysql_error());

            $result = send_message($firstname,$username,$email,$activation_code,$msg,$message);
            if($result)
            {
              echo "message sent";
            } else {
               echo "message is not sent";
            }
         } else {
                     //activate user by default
                     // set the approved field to 1 to activate the account
                 echo "activation " .REQUIRE_ACTIVIATION;
            $rs_activ = mysqli_query($link, "UPDATE ".USERS." SET approved='1' WHERE
            user_id='". $user_id. "'") or die(mysqli_error($link));
            
         }
    }
    return $err;
}

function send_message($firstname, $username, $email, $activation_code,$msg_subject, $message){
       global $password_store_key;

       $key = $password_store_key;

		$result = mysql_query("SELECT AES_DECRYPT(p_pass,'$key') AS password FROM pstore_nivi WHERE p_email=AES_ENCRYPT('".GLOBAL_EMAIL."',connectcommunity1, '$key')") or die(mysql_error());
		$row = mysql_fetch_assoc($result);

		$pw = $row['password'];

		//instead, we use swift's email function
		$email_to = $email; $email_from=GLOBAL_EMAIL;$password = $pw; $subj = $msg_subject;
		$transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, "ssl")
		  ->setUsername($email_from)
		  ->setPassword($password);

		$mailer = Swift_Mailer::newInstance($transport);

		$message = Swift_Message::newInstance($subj)
		  ->setFrom(array($email_from => 'Nivedita'))
		  ->setTo(array($email_to))
		  ->setBody($message);

		$result = $mailer->send($message);
        return $result;
}


/*Function to secure pages and check users*/
function secure_page(){
	session_start();
	global $db;

	//Secure against Session Hijacking by checking user agent
	if(isset($_SESSION['HTTP_USER_AGENT']))
	{
		//Make sure values match!
		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']) or $_SESSION['logged'] != true)
		{
			logout();
			exit;
		}

		//We can only check the DB IF the session has specified a user id
		if(isset($_SESSION['user_id']))
		{
			$details = mysql_query("SELECT ckey, ctime FROM ".USERS." WHERE id ='".$_SESSION['user_id']."'") or die(mysql_error());
			list($ckey, $ctime) = mysql_fetch_row($details);

			//We know that we've declared the variables below, so if they aren't set, or don't match the DB values, force exit
			if(!isset($_SESSION['stamp']) && $_SESSION['stamp'] != $ctime || !isset($_SESSION['key']) && $_SESSION['key'] != $ckey)
			{
				logout();
				exit;
			}
		}
	}
	//if we get to this, then the $_SESSION['HTTP_USER_AGENT'] was not set and the user cannot be validated
	else
	{
		logout();
		exit;
	}
}

/*Function to generate key for login.php*/
function generate_key($length = 7)
{
	$password = "";
	$possible = "0123456789abcdefghijkmnopqrstuvwxyz";

	$i = 0;
	while ($i < $length)
	{
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char))
		{
			$password .= $char;
			$i++;
		}
	}
	return $password;
}
?>