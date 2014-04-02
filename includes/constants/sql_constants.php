<?php
/* This file contains variables defining the database for the Havyaka culture site and functions to manipulate the database. */
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
$max_file_size = 5000000;
global $max_file_size;


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
function filter($data) {
	$data = trim(htmlentities(strip_tags($data)));

	if (get_magic_quotes_gpc()) {
		$data = stripslashes($data);
	}

	$data = mysql_real_escape_string($data);
	return $data;
}

/*Function to easily output all our css, js, etc...*/
function return_meta($title = NULL, $keywords = NULL, $description = NULL) {
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
function logout($lm = NULL) {
	global $link;
	
	if(!isset($_SESSION)) {
		session_start();
	}

	//If the user is 'partially' set for some reason, we'll want to unset the db session vars
	if(isset($_SESSION['user_id'])) {
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

	if(isset($lm)) {
		header("Location: ".BASE."/index.php?msg=".$lm);
	}
	else {
		header("Location: ".BASE."/index.php");
	}
}

/* Function to check for errors */
function error_check($firstname,$username,$password,$confirm_pass,$email,$zipcode) {
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

/* ---------- functions related to local chef----------------------*/
/* Function to retrieve a user's information */
/* function get_chef_info($user_id) {
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
} */

/* Function to retrieve info for a specific chef */
function get_chef_info($chef_id) {
	global $link;
	
	// SELECT * from CHEF as t1 LEFT JOIN FOOD_CHEF_DETAILS as t2 ON t1.chef_id = t2.chef_id LEFT JOIN FOOD as t3 ON t2.food_id = t3.food_id WHERE t2.food_id = 1;
	$q = "SELECT t1.chef_id, t1.about_chef, t1.contact_time_preference, t1.delivery_available, t1.payments_accepted, t1.pickup_available, t1.taking_offline_order, t4.first_name, t4.last_name, t4.user_id, t4.email, t4.phone, t4.profile_picture 
	FROM chef as t1 
	LEFT JOIN " . FOOD_CHEF_DETAILS . " as t2 ON t1.chef_id = t2.chef_id 
	LEFT JOIN " . FOOD . " as t3 ON t2.food_id = t3.food_id 
	LEFT JOIN " . USERS . " as t4 on t4.user_id = t1.user_id 
	WHERE t1.chef_id = $chef_id;";
	
	// uncomment this to debug
	/* echo "<br> get_chef_info query is: <br>";
	echo $q;
	echo "<br>"; */
	
	// execute the query
	if($query = mysqli_query($link,$q)) {
		$results = mysqli_fetch_assoc($query);
	}
	
	return $results;
}

/* Function to retrieve all chefs that cook a certain type of food */
function get_chefs_by_food($food_type_id) {
	global $link;
	
	// SELECT * from CHEF as t1 LEFT JOIN FOOD_CHEF_DETAILS as t2 ON t1.chef_id = t2.chef_id LEFT JOIN FOOD as t3 ON t2.food_id = t3.food_id WHERE t2.food_id = 1;
	$q = "SELECT t1.chef_id, t1.about_chef, t1.contact_time_preference, t1.delivery_available, t1.payments_accepted, t1.pickup_available, t1.taking_offline_order, t4.first_name, t4.last_name, t4.user_id, t4.email, t4.phone, t4.profile_picture 
	FROM chef as t1 
	LEFT JOIN " . FOOD_CHEF_DETAILS . " as t2 ON t1.chef_id = t2.chef_id 
	LEFT JOIN " . FOOD . " as t3 ON t2.food_id = t3.food_id 
	LEFT JOIN " . USERS . " as t4 on t4.user_id = t1.user_id 
	WHERE t3.food_id = $food_type_id;";
	
	// uncomment this to debug
	/* echo "<br> get_chefs_by_food query is: <br>";
	echo $q;
	echo "<br>"; */
	
	// execute the query
	if($food_query = mysqli_query($link,$q)) {
		while ($row = mysqli_fetch_assoc($food_query)) {
			$results[] =$row;
		}
	}
	
	return $results;
}

/* Function to print a chef card - accepts an array of chef information to print */
function print_chef_card($chef_info_array) {
	$chef_id = $chef_info_array['chef_id'];
	$first_name = $chef_info_array['first_name'];
	$last_name = $chef_info_array['last_name'];
	$about_chef = $chef_info_array['about_chef'];
	$email = $chef_info_array['email'];
	$phone = $chef_info_array['phone'];
	$contact_time_preference = $chef_info_array['contact_time_preference'];
	$delivery_available = $chef_info_array['delivery_available'];
	$pickup_available = $chef_info_array['pickup_available'];
	$payments_accepted = $chef_info_array['payments_accepted'];
	$taking_offline_order = $chef_info_array['taking_offline_order'];
	// $zipcode = $chef_info_array['zipcode'];

	//get the chef's profile picture
	$profile_picture = $chef_info_array['profile_picture']; 
	$media_loc_profile = htmlspecialchars($profile_picture);
	$media_loc_profile = BASE.$media_loc_profile;
	list($width, $height, $type, $attr)= getimagesize($media_loc_profile);

?>
<div class ="card flipper">
	<div class="back">
		<input type="hidden" value=<?php echo $chef_id; ?> ></input>
		<table>
			<tr>
				<td> <?php echo $first_name; ?>&nbsp;<?php echo $last_name; ?><br></br><?php echo $about_chef; ?>&nbsp;</td>
				<td>Chef contact details: <br>Contact hour:</br></td>
				<td><?php echo $email; ?><br><?php echo $phone; ?></br><?php echo $contact_time_preference; ?></td>
			</tr>
			
			<tr>
				<th style="text-align:center;font-size: 100%;">Good at preparing:</th>
			</tr>
			
			<tr>
				<?php 
				// print foods for the selected chef
				$asdf = get_foods_by_chef($chef_id);
				
				// uncomment below to debug
				/*	echo "<br> get_foods_by_chef array is: <br>";
				print_r($asdf);
				echo "<br>"; */
				
				
				foreach ($asdf as $row_food) {
					
					$food_id = $row_food['food_id'];
					
					$food_picture = $row_food['food_picture'];
					$media_loc = htmlspecialchars($food_picture);
					$media_loc = BASE.$media_loc;
					list($width, $height, $type, $attr)= getimagesize($media_loc);  
					?>
					
					<tr>
						<td>
						Food Name:<br><br>
						Description:<br><br>
						Price:<br>
						</td>
						
						<td>
						<?php echo $row_food['food_name']; ?><br><br>
						<?php echo $row_food['food_description']; ?><br><br>
						<?php echo $row_food['food_price']; ?><br><br>
						<td><img class="gridimg2" src="<?php echo $media_loc;?>" style="width:10em" /></td>
						</td>
					</tr>
				<?php } ?>
			</tr>

			<tr>
				<td><button class = "save_chef" rel="<?php echo $chef_id; ?>" id= "<?php //echo $save_chef;?>" type="submit" name="save_chef">Save</button></td>
				<td><label class="flip">Flip</label></td>
			</tr>
		</table>

	</div>                           

	<div class="front">
		<table>
			<tr>
				<td><?php echo $first_name; ?> &nbsp;<?php echo $last_name; ?> <br><br><?php echo $about_chef; ?></br></td>
				<td><img class="gridimg2" src="<?php echo $media_loc_profile;?>" style="width:10em" /></td>
			</tr>
			
			<tr>
				<td><th>Delivery available:</th></td> 
				<td><?php echo $delivery_available; ?></td> 
			</tr>
			
			<tr>
				<td><th>Pickup available:</th></td>
				<td><?php echo $pickup_available; ?></td>
			</tr>
			
			<tr>
				<td><th>Payment method:</th></td>
				<td><?php echo $payments_accepted; ?></td>
			</tr>
			
			<tr>
				<td><th>takes offline order?:</th></td>
				<td><?php echo $taking_offline_order; ?></td>
			</tr>

			<tr>
				<td><label class="flip">Flip</label></td>
			</tr>
		</table>
	</div>
</div>
<?php 
}

/* display chef details and food details on the card based on the logged in user's location*/
function get_localchef_details($user_id) {
	global $link;

	//get the logged in user's location
	$e_loc_id= get_loggedin_user_location($user_id);

	$get_city_state = mysqli_query($link, "SELECT city, state from " . LOCATION . " WHERE e_loc_id = $e_loc_id;") or die(mysqli_error($link));
	
	list($city, $state) = mysqli_fetch_row($get_city_state);
	
	//query to get the chef details based on the logged in user's location
	$get_chef = "SELECT t1.chef_id, t1.about_chef, t1.contact_time_preference, t1.delivery_available, t1.payments_accepted, t1.pickup_available, t1.taking_offline_order, t2.first_name, t2.last_name, t2.user_id, t2.email, t2.phone, t2.profile_picture, t4.city, t4.zipcode, t4.state FROM chef as t1
		left join user as t2 on t2.user_id = t1.user_id 
		left join location as t4 on t2.e_loc_id = t4.e_loc_id 
		left join venue as t3 on t4.e_loc_id = t3.e_loc_id
		WHERE  (t4.city = '$city' OR t4.state = '$state');";

	if($chef_query = mysqli_query($link, $get_chef)) {
		while($row = mysqli_fetch_assoc($chef_query)) {
			$results[] = $row;
		}
	}
	
	return $results;
}

/* Function to retrieve a user's information */
function get_user_info($user_id) {
	// select * from user where user_id = 1;
	global $link;
	global $salt;
	
	// to do: return user profile picture
	$select = "SELECT first_name, last_name, AES_DECRYPT(email,'$salt') as email, phone, profile_picture";
	// $select = "SELECT first_name, last_name, email as email, phone";
	
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
               
            $e_loc_id = insert_zipcode_location($zipcode);
            
		//get the community id based on the community name
		$q = "SELECT community_id from ".COMMUNITY_TYPE. " WHERE community_name = '$community_type' LIMIT 1";

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

/* Function to update users */
function update_user_info($user_id, $first_name, $last_name, $email, $phone, $profile_picture=NULL) {
	global $link;
	global $salt;
	
	$q = "UPDATE " . USERS . " SET ";
	
	// adds first name if specified
	if (!is_null($first_name)){
		$q .= "first_name='$first_name'";
	}
	
		// adds last name if specified
	if (!is_null($last_name)){
		if (strpos($q,'=') !== false) {
			$q .= ", ";
		}
		$q .= "last_name='$last_name'";
	}
	
		// adds email if specified
	if (!is_null($email)){
		if (strpos($q,'=') !== false) {
			$q .= ", ";
		}
		$q .= "email=AES_ENCRYPT('$email','$salt')";
	}
	
		// adds phone if specified
	if (!is_null($phone)){
		if (strpos($q,'=') !== false) {
			$q .= ", ";
		}
		$q .= "phone='$phone'";
	}
	
	// adds profile picture if specified
	if (!is_null($profile_picture)){
		if (strpos($q,'=') !== false) {
			$q .= ", ";
		}
		$q .= "profile_picture='$profile_picture'";
	}
	
	$q .= " WHERE user_id = $user_id";
	
	echo $q;
	// Uncomment below to debug query
	// echo $q;
	// echo "<br>";
	
	if (mysqli_query($link,$q)){
		return true;
		// echo "User updated successfully";
	}
	else {
		return false;
		// echo "User update failed";
	}
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
function add_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $user_id, $venue_name,$venue_address,$event_zipcode, $community_id, $e_recurring_id) {
	global $link;
	// check if the zipcode already in the location table, if not insert and get the e_loc_id
        // insert the venue name, address, e_loc_id into venue table and get the last inserted venue_id
        //then insert the event details into event table.
        
        //get the e_loc_id
        $e_loc_id = insert_zipcode_location($event_zipcode);
        
        //insert venue details into venue table
        //I am not sure why we need to store venue phone, email and owner name. may be we can skip these info,if we both agree
        $q_venue = mysqli_query($link,"INSERT INTO " .VENUE. " (venue_name,venue_address,e_loc_id) VALUES ('$venue_name','$venue_address',$e_loc_id)") or die(mysqli_error($link));
        
         $venue_id = mysqli_insert_id($link);
           echo $venue_id;     
        
	$q = "INSERT INTO " . EVENT . "(event_name, event_date, event_desc, event_scope, e_type_id,event_status, user_id, venue_id, community_id, e_recurring_id) VALUES ('$event_name', '$event_date', '$event_desc', '$event_scope', '$e_type_id','1', '$user_id', '$venue_id', '$community_id', '$e_recurring_id')";
	echo $q;
	if (mysqli_query($link,$q)){
		echo "Event added successfully";
                return true;
	}
	else {
		echo "Event failed to add";
                return false;
	}
	
}

/* Function to update events */
function update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_id, $e_recurring_id, $event_id){
	global $link;
	
	$q = "UPDATE " . EVENT . " SET event_name='$event_name', event_date='$event_date', event_desc='$event_desc', event_scope='$event_scope', e_type_id='$e_type_id', venue_id='$venue_id', e_recurring_id='$e_recurring_id' WHERE event_id = $event_id";
	
	// Uncomment below to debug query
	// echo $q;
	// echo "<br>";
	
	if (mysqli_query($link,$q)){
		return true;
		// echo "Event updated successfully";
	}
	else {
		return false;
		// echo "Event update failed";
	}
}

<<<<<<< HEAD
function get_event_types()
{
=======
//get list of event_types
function event_type() {
>>>>>>> 8fe9a9c81f5566eb898f6f737c09f3921f393847
    global $link;
    $q_e_type = mysqli_query($link,"SELECT * FROM " .EVENT_TYPE) or die(mysqli_error($link));
    
    $row = array();
    while($q_event = mysqli_fetch_array($q_e_type)) 
    {
        $row[]=$q_event;       
    }
    return $row;
}

/* Function to delete events */
function delete_event($event_id) {
	global $link;
	$q = "DELETE FROM " . EVENT . " WHERE event_id = $event_id";
	
	// uncomment below to debug
	// echo $q; 
	
	if (mysqli_query($link,$q)){
		return true;
	}
	else {
		return false;
	}
}

//retrieve event based on user's location.
function retrieve_future_event($user_id) {
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
        
        $get_city_state = mysqli_query($link,"SELECT city,state from ".LOCATION. " WHERE e_loc_id= ".$e_loc_id. ";") or die(mysqli_error($link));
            list($city,$state) = mysqli_fetch_row($get_city_state);

        
            $q2 = "SELECT t1.event_date, t1.event_desc, t1.event_id, t1.event_name, t5.first_name, AES_DECRYPT(t5.email, '$salt') as email, t5.phone, t5.last_name, t3.venue_address, t3.venue_name, t4.city, t4.zipcode, t4.state
                    FROM event AS t1
                    LEFT JOIN event_type AS t2 ON t1.e_type_id = t2.e_type_id
                    LEFT JOIN venue AS t3 ON t1.venue_id = t3.venue_id
                    LEFT JOIN location AS t4 ON t3.e_loc_id = t4.e_loc_id
                    LEFT JOIN user AS t5 ON t1.user_id = t5.user_id
                    WHERE event_status =1
                    AND t1.event_date > CURDATE( )
                    AND (t4.city = '".$city."' OR t4.state = '".$state. "');";
      
              if($event_query = mysqli_query($link,$q2))
              {
                  if(mysqli_num_rows($event_query) > 0)
                  {
                   while ($row = mysqli_fetch_assoc($event_query))
                     {
                            $results[] =$row;

                     }   
                  } else { //if no events found at his exact location, extend the search to different location in his state
                      
                      
                  }
              }
                 return $results;
}

/* Function to retrieve events information. Accepts arguments for visibility and user_id */
function get_events($user_id = NULL, $visibility = NULL) {
	global $link;
	
	// to do: return picture
	// to do: return if the event is editable by the current user
	// set up query with all of the tables tied together 
	$select = "SELECT t1.event_id, event_name, t3.venue_name, t3.venue_address, t4.city, t4.state, t4.zipcode, t2.event_type, event_date, event_desc, t1.user_id";
	
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

/*  */
function get_loggedin_user_location($user_id) {
    global $link;
    $q1 = "SELECT e_loc_id FROM ".USERS. " WHERE  user_id = ".$user_id;
            $query = mysqli_query($link,$q1) or (die(mysqli_error($link)));

            $row = mysqli_fetch_assoc($query);
            $location_id = $row['e_loc_id'];
            
        return $location_id;
}

/* Function to save things to user profiles. Function can specify event, chef, or contact to save */
function save_info($info_type, $user_id, $info_id) {
	global $link;
	
	$q = "INSERT INTO " . USER_SAVED_INFO . " (user_id, event_id, chef_id, contact_id) VALUES ('" . $user_id . "', ";
	
	// builds the query based on the info type supplied
	switch ($info_type) {
	case "event":
		$q .= "'" . $info_id . "', NULL, NULL)";
		break;
	case "chef":
		$q .= "NULL, '" . $info_id . "', NULL)";
		break;
	case "contact":
		$q .= "NULL, NULL, '" . $info_id . "')";
		break;
	default:
		echo "Error, please supply valid info type to update";
		break;
	}
	
	if (mysqli_query($link,$q)){
		echo $info_type . " added successfully";
	}
	else {
		echo $q . "<br>";
		echo $info_type . " failed to add";
	}
}

/* Function to store images in the database */
function store_image($file_handler) {
	global $link;
	global $max_file_size;
	
	$allowedExts = array("gif", "jpeg", "jpg", "png","JPEG","JPG","PNG","GIF");

	$temp = explode(".", $file_handler["name"]);

	$extension = end($temp);

	if ((($file_handler["type"] == "image/gif")
		|| ($file_handler["type"] == "image/jpeg")
		|| ($file_handler["type"] == "image/jpg")
		|| ($file_handler["type"] == "image/pjpeg")
		|| ($file_handler["type"] == "image/x-png")
		|| ($file_handler["type"] == "image/png"))
		&& ($file_handler["size"] < $max_file_size)
		&& in_array($extension, $allowedExts)) {
		if ($file_handler["error"] > 0) {
			echo "Return Code: " . $file_handler["error"] . "<br>";
			// return false;
		}
		else {
/*			Uncomment to debug
			echo "Upload: " . $file_handler["name"] . "<br>";
			echo "Type: " . $file_handler["type"] . "<br>";
			echo "Size: " . ($file_handler["size"] / 1024) . " kB<br>";
			echo "Temp file: " . $file_handler["tmp_name"] . "<br>"; */

			if (file_exists("pictures/" . $file_handler["name"])) {
				echo $file_handler["name"] . " already exists. ";
				// return false;
			}
			else {
				$new_file_location = "pictures/" . $file_handler["name"];
				move_uploaded_file($file_handler["tmp_name"], $new_file_location);
				// echo "Stored in: " . $new_file_location;
				return $new_file_location;
			}
		}
	}
	else {
		echo "Invalid file";
		// return false;
	}
}

function get_foods_by_chef($chef_id) {
	global $link;
	// SELECT t1.food_id, t1.food_name, t1.food_description, t1.availability, t1.food_picture, t1.community_id, t2.food_price
	// FROM food AS t1 LEFT JOIN food_chef_details AS t2 ON t1.food_id = t2.food_id
	// WHERE t2.chef_id = $chef_id
	
	$select = "SELECT t1.food_id, t1.food_name, t1.food_description, t1.availability, t1.food_picture, t1.community_id, t2.food_price";
	
	$from = " FROM " . FOOD . " AS t1 LEFT JOIN " . FOOD_CHEF_DETAILS . " AS t2 ON t1.food_id = t2.food_id";
	
	$where = " WHERE t2.chef_id = $chef_id;";
	
	$q = $select . $from . $where;
	
	// uncomment this to debug
	/* echo "<br> get_foods_by_chef query is: <br>";
	echo $q;
	echo "<br>"; */
	
	// execute the query
	if($query = mysqli_query($link,$q)) {
		while ($row = mysqli_fetch_assoc($query)) {
			$results[] =$row;
		}
	}
	
	return $results;
}


// get the zipcode and check if the zipcode already exists in the location table, or insert into it
function insert_zipcode_location ($zipcode) {
    global $link;
    
     if($loc_query = mysqli_query($link,"SELECT e_loc_id from ".LOCATION. " WHERE zipcode = $zipcode LIMIT 1") or die(mysqli_error($link)))
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
         return $e_loc_id;
}

?>