<?php
/* This file contains variables defining the database for the Havyaka culture site and functions to manipulate the database. */
ini_set('display_errors', 'On');
error_reporting(E_ALL | E_STRICT);

// information about the SQL database -- make sure the database on your end matches the dataase name, the user and the password
define('DB_HOST', "localhost");
define('DB_USER', "hci573");
define('DB_PASS', "hci573");
define('DB_NAME', "hci573");

include_once 'includes/constants/dbc.php';
//base in operating system

//tables
define ("PSTORE_TABLE","pstore_nivi");

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

define ("GLOBAL_EMAIL", "connect.community.culture@gmail.com");
define("REQUIRE_ACTIVIATION","1");

$file_location = "../pictures";
global $file_location;

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

	if (get_magic_quotes_gpc()) {
		$data = stripslashes($data);
	}

	$data = mysql_real_escape_string($data);
	return $data;
}

/*Function to easily output all our css, js, etc...*/
function return_meta($title = NULL, $keywords = NULL, $description = NULL){
	if(is_null($title)) {
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
	<link rel="stylesheet" type="text/css" media="all" href="'.BASE.'/includes/styles/style.css" />
	
	';

	echo $meta;
}

/*Function to validate email addresses*/
function check_email($email) {
		return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

/*Function to update user details*/
function hash_pass($pass) {
	global $passsalt;
	$hashed = md5(sha1($pass));
	$hashed = crypt($hashed, $passsalt);
	$hashed = sha1(md5($hashed));
	return $hashed;
}

/*Function to logout users securely*/
function logout($lm = NULL)
{
    global $link;
    
	if(!isset($_SESSION))
	{
		session_start();
	}

	//If the user is 'partially' set for some reason, we'll want to unset the db session vars
	if(isset($_SESSION['user_id']))
	{
		global $db;
		mysqli_query($link,"UPDATE ".USERS." SET ckey= '', ctime= '' WHERE user_id='".$_SESSION['user_id']."'") or die(mysqli_error($link));
		unset($_SESSION['user_id']);
	}
		unset($_SESSION['user_name']);
		unset($_SESSION['user_level']);
		unset($_SESSION['HTTP_USER_AGENT']);
		unset($_SESSION['stamp']);
		unset($_SESSION['key']);
		unset($_SESSION['fullname']);
		unset($_SESSION['logged']);
		session_unset();
		session_destroy();

	if(isset($lm))
	{
		header("Location: ".BASE."/index.php?msg=".$lm);
	}
	else
	{
		header("Location: ".BASE."/index.php");
	}
}

 function error_check($firstname,$username,$password,$confirm_pass,$email,$zipcode)
 {
     $error= array();
	if(empty($firstname)) {
		$error[] = "You must enter your name";
	}
	
	if(strlen($firstname) < 2) {
		$error[] = "You must enter your real name";
	}
	
	if(empty($username)) {
		$error[] = "You must enter a username";
	}

	if(strlen($username) < 4) {
		$error[] = "username must be minimum of 4 letters";
	}
	
	if(empty($password) || strlen($password) < 4) {
		$error[] = "You must enter a password";
	}
	
	if(empty($email) || !check_email($email)) {
		$error[] = "Please enter a valid email address.";
	}

	if($password != $confirm_pass) {
		$error[] = "Password and confirm password do not match!";
	}
        if(strlen($zipcode)<5)
        {
            $error[] = "Please enter the right zipcode";
        }
        return $error;
}

/* Function to retrieve a user's information */
function get_user_info($user_id) {
	// select * from user where user_id = 1;
	global $link;
	global $salt;
	
	// to do: return user profile picture
	$select = "SELECT first_name, last_name, AES_DECRYPT(email,'$salt') as email, phone";
	
	$from = " FROM " . USERS;
	
	$where = " where user_id=" . $user_id;
	
	// build the query
	$q = $select . $from . $where . ";";
	
	// execute the query
	if($event_query = mysqli_query($link,$q)) {
		$results[] = mysqli_fetch_assoc($event_query);
	}
	
	return $results;
}
/* ---------- functions related to local chef----------------------*/
/* Function to retrieve a user's information */
function get_chef_info($user_id) {
	// select * from user where user_id = 1;
	global $link;
	global $salt;
	
	// to do: return user profile picture
	$select = "SELECT about_chef, pickup_available, contact_time_preference, payments_accepted, delivery_available, taking_offline_order";
	
	$from = " FROM " . USERS . " as t1 LEFT JOIN " . CHEF . " as t2 ON t1.user_id = t2.user_id";
	
	// will always return events that are active
	$where = " where t1.user_id=" . $user_id;
		
	// build the query
	$q = $select . $from . $where . ";";
	
	// execute the query
	if($event_query = mysqli_query($link,$q)) {
		$results[] = mysqli_fetch_assoc($event_query);
	}
	
	return $results;
}

/* display chef details and food details on the card based on the logged in user's locaiton*/

function get_localchef_details($user_id)
{
    global $link;

    //get the logged in user's location
     $e_loc_id= get_loggedin_user_location($user_id);
 
    //build the query
    $sql = "SELECT t1.chef_id,t1.about_chef,t1.contact_time_preference,t1.delivery_available,t1.payments_accepted,t1.pickup_available,t1.taking_offline_order,t2.first_name,t2.last_name,t2.user_id,t2.email,t2.phone,t2.profile_picture,t6.food_id,t6.food_name,t6.food_picture,t6.food_description,t4.city,t4.zipcode,t4.state FROM chef as t1
              right join user as t2 on t2.user_id=t1.user_id 
              right join location as t4 on t2.e_loc_id = t4.e_loc_id 
              right join venue as t3 on t4.e_loc_id=t3.e_loc_id
              right join food_chef_details as t5 on t5.chef_id=t1.chef_id
              right join food as t6 on t5.food_id = t6.food_id
              and t4.e_loc_id = 1";                
              //$e_loc_id;
    
    if($chef_query = mysqli_query($link,$sql)) 
    {
       while($row = mysqli_fetch_assoc($chef_query))
       {
           $results[] = $row;
       }
        
    }
    return $results;
    
}

/* Function to add new users to the database */
function add_user($firstname,$username,$password,$confirm_pass,$email,$zipcode,$date,$user_ip,$activation_code,$community_type) {
 
	$msg = NULL;
	$err = array();
	global $salt;
	global $link;
      
           
        $err =error_check($firstname,$username,$password,$confirm_pass,$email,$zipcode);

	if($stmt = mysqli_prepare($link, "SELECT username, email FROM ".USERS." WHERE username = '$username' OR email = AES_ENCRYPT('$email', '$salt')") or die(mysqli_error($link))) {
		//execute the query
		mysqli_stmt_execute($stmt);
		//store the result
		mysqli_stmt_store_result($stmt);
		
		if(mysqli_stmt_num_rows($stmt) > 0) {
			$err[] = "User already exists";
		}
		
		mysqli_stmt_close($stmt);
	}

	if(empty($err)) {
              //check if the zipcode is already in the table, if not insert into the table.
                if($loc_query = mysqli_query($link,"SELECT e_loc_id from ".LOCATION. " WHERE zipcode = ".$zipcode. " LIMIT 1") or die(mysqli_error($link)))
                {
                      if(mysqli_num_rows($loc_query) == 0)
                      {
                        $q_loc = mysqli_query($link, "INSERT INTO ".LOCATION. " (zipcode) VALUES ('$zipcode')") or die(mysqli_error($link));		
                         //get the last inserted id from the location table
                        $e_loc_id = mysqli_insert_id($link);
                      } else
                      {
                          $row = mysqli_fetch_assoc($loc_query);
                            $e_loc_id = $row['e_loc_id'];
                      }
                 } 
                 
		//get the community id based on the community name
		$q = "SELECT community_id from ".COMMUNITY_TYPE. " WHERE community_name = '".$community_type. "' LIMIT 1";

		$query = mysqli_query($link,$q) or (die(mysqli_error($link)));
		$row = mysqli_fetch_assoc($query);
		$community_id = $row['community_id'];

		$password = hash_pass($password);

		$query = "INSERT INTO ".USERS." (first_name, username, e_loc_id, user_password, email, registration_date, user_ip, activation_code,community_id) VALUES ('$firstname', '$username', '$e_loc_id', '$password', AES_ENCRYPT('$email', '$salt'), '$date', '$user_ip', '$activation_code',$community_id)";

		if ($q1 = mysqli_query($link,$query)) {
			//Generate rough hash based on user id from above insertion statement
			$user_id = mysqli_insert_id($link); //get the id of the last inserted item

			$md5_id = md5($user_id);
                       
			mysqli_query($link, "UPDATE ".USERS." SET md5_id='$md5_id' WHERE id='$user_id'");
		

		if(REQUIRE_ACTIVIATION != 1) {
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
			if($result) {
				echo "message sent";
			}
			else {
				echo "message is not sent";
			}
		}
		else {
			//activate user by default
			// set the approved field to 1 to activate the account
			
			$rs_activ = mysqli_query($link, "UPDATE ".USERS." SET approved='1' WHERE
			user_id='". $user_id. "'") or die(mysqli_error($link));
		}
             }
             else {
			$err[] ="Something happened!, please try again!";
                }
	}
	return $err;
}


/* Function to send an email message to a user */
function send_message($firstname, $username, $email, $activation_code,$msg_subject, $message) {
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
function secure_page() {
	session_start();
	global $db;
        global $link;

        //Secure against Session Hijacking by checking user agent
	if(isset($_SESSION['HTTP_USER_AGENT'])) {
		//Make sure values match!
		if($_SESSION['HTTP_USER_AGENT'] != md5($_SERVER['HTTP_USER_AGENT']) or $_SESSION['logged'] != true)
		{
			logout();
			exit;
		}
		//We can only check the DB IF the session has specified a user id
		if(isset($_SESSION['user_id'])) {
			$details = mysqli_query($link,"SELECT ckey, ctime FROM ".USERS." WHERE user_id ='".$_SESSION['user_id']."'") or die(mysqli_error($link));
			list($ckey, $ctime) = mysqli_fetch_row($details);

			//We know that we've declared the variables below, so if they aren't set, or don't match the DB values, force exit
			if(!isset($_SESSION['stamp']) && $_SESSION['stamp'] != $ctime || !isset($_SESSION['key']) && $_SESSION['key'] != $ckey) {
				logout();
				exit;
			}
		}
	}
	//if we get to this, then the $_SESSION['HTTP_USER_AGENT'] was not set and the user cannot be validated
	else {
		logout();
		exit;
	}
}

/*Function to generate key for login.php*/
function generate_key($length = 7) {
	$password = "";
	$possible = "0123456789abcdefghijkmnopqrstuvwxyz";

	$i = 0;
	while ($i < $length) {
		$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
		if (!strstr($password, $char)) {
			$password .= $char;
			$i++;
		}
	}
	
	return $password;
}

/* Function to create an event */
function add_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $user_id, $venue_id, $community_id, $e_recurring_id){
	global $link;
	
	$q = "INSERT INTO " . EVENT . "(event_name, event_date, event_desc, event_scope, e_type_id, user_id, venue_id, community_id, e_recurring_id) VALUES ('" . $event_name . "', '" . $event_date . "', '" . $event_desc . "', '" . $event_scope . "', '" . $e_type_id . "', '" . $user_id . "', '" . $venue_id . "', '" . $community_id . "', '" . $e_recurring_id . "')";
	
	if (mysqli_query($link,$q)){
		echo "Event added successfully";
	}
	else {
		echo "Event failed to add";
	}
	
}

/* Function to update events */
function update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_id, $e_recurring_id, $event_id){
	global $link;
	
	$q = "UPDATE " . EVENT . " SET event_name='" . $event_name . "', event_date='" . $event_date . "', event_desc='" . $event_desc . "', event_scope='" . $event_scope . "', e_type_id='" . $e_type_id . "', venue_id='" . $venue_id . "', e_recurring_id='" . $e_recurring_id . "' WHERE event_id = " . $event_id;
	
	echo $q;
	echo "<br>";
	
	if (mysqli_query($link,$q)){
		echo "Event updated successfully";
	}
	else {
		echo "Event update failed";
	}
}

/* Function to delete events */
function delete_event($event_id) {
	global $link;
	$q = "DELETE FROM 'event' WHERE event_id = " . $event_id;
	
	if (mysqli_query($link,$q)){
		echo "Event deleted successfully";
	}
	else {
		echo "Event deletion failed";
	}
}
//retrieve event based on user's location.
function retrieve_future_event($user_id) 
{
    global $link;
    global $salt;
    $err = array();
    $results = NULL;
    /* 
            * step 1: Get the logged in user's location , city, zip code or state
            * step 2: based on the location id, get the venue details
            * step 3: fetch the events based on venue- location
            * select * from event where venue_id in (select venue_id from venue where 
                fk_venue_location = 1);
            * if no events are found in his location, display all events.

            *             */
         $e_loc_id= get_loggedin_user_location($user_id);

            $q2 = "SELECT t1.event_date, t1.event_desc, t1.event_id, t1.event_name, t5.first_name, AES_DECRYPT(t5.email, '$salt') as email, t5.phone, t5.last_name, t3.venue_address, t3.venue_name, t4.city, t4.zipcode, t4.state
                    FROM event AS t1
                    LEFT JOIN event_type AS t2 ON t1.e_type_id = t2.e_type_id
                    LEFT JOIN venue AS t3 ON t1.venue_id = t3.venue_id
                    LEFT JOIN location AS t4 ON t3.e_loc_id = t4.e_loc_id
                    LEFT JOIN user AS t5 ON t1.user_id = t5.user_id
                    WHERE event_status =1
                    AND t1.event_date > CURDATE( )
                    AND t3.e_loc_id =".$location_id;
            
              if($event_query = mysqli_query($link,$q2))
              {
                  if(mysqli_num_rows($event_query) > 0)
                  {
                   while ($row = mysqli_fetch_assoc($event_query))
                     {
                            $results[] =$row;

                     }   
                  }
              }
                 return $results;
}

/* Function to retrieve events information. Accepts arguments for visibility and user_id */
function get_events($user_id = NULL, $visibility = NULL){
	global $link;
	
	// to do: return picture
	// to do: return if the event is editable by the current user
	// set up query with all of the tables tied together 
	$select = "SELECT event_name, t3.venue_name, t3.venue_address, t4.city, t4.state, t4.zipcode, t2.event_type, event_date, event_desc, t1.user_id";
	
	$from = " FROM " . EVENT . " as t1 
	LEFT JOIN " . EVENT_TYPE . " as t2 ON t1.e_type_id = t2.e_type_id
	LEFT JOIN " . VENUE . " as t3 ON t1.venue_id = t3.venue_id
	LEFT JOIN " . LOCATION . " as t4 ON t3.e_loc_id = t4.e_loc_id 
	LEFT JOIN " . USERS . " as t5 ON t1.user_id = t5.user_id ";
	
	// will always return events that are active
	$where = " where event_status=1";
	
	// if visibility is specified, add it to the query
	if (!is_null($visibility)){
		$where .= " and event_scope = '" . $visibility . "'";
	}
	
	// if user is provided, add it to the query
	if (!is_null($user_id)){
		$where .= " and t5.user_id = " . $user_id;
	}
	
	// build the query
	$q = $select . $from . $where . ";";
	
	// execute the query
	if($event_query = mysqli_query($link,$q)) {
		while ($row = mysqli_fetch_assoc($event_query)) {
			$results[] =$row;
		}
	}
	
	return $results;
}
function get_loggedin_user_location($user_id)
{
    global $link;
    $q1 = "SELECT e_loc_id FROM ".USERS. " WHERE  user_id = ".$user_id;
            $query = mysqli_query($link,$q1) or (die(mysqli_error($link)));

            $row = mysqli_fetch_assoc($query);
            $location_id = $row['e_loc_id'];
            
        return $location_id;
}

?>