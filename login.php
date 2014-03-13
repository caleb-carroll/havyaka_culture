<?php
require_once 'includes/constants/sql_constants.php';

//Pre-assign our variables to avoid undefined indexes
$username = NULL;
$pass2 = NULL;
$msg = NULL;
$err = array();

//See if form was submitted, if so, execute...
if(isset($_POST['login']))
{

	//Assigning vars and sanitizing user input
	$username = filter($_POST['user']);
	$pass2 = filter($_POST['pass']);

	if(empty($username) || strlen($username) < 4)
	{
		$err[] = "You must enter a username";
	}
	if(empty($pass2) || strlen($pass2) < 4)
	{
		$err[] = "You seem to have forgotten your password.";
	}

    $sql = "SELECT usr_pwd, id, approved FROM ".USERS." WHERE user_name = '$username' OR usr_email = AES_ENCRYPT('$username', '$salt');";


   //Select only ONE password from the db table if the username = username, or the user input email (after being encrypted) matches an encrypted email in the db
	$q = mysql_query($sql,$link) or die(mysql_error());

	//Select only the password if a user matched
	list($pass, $userid, $approved) = mysql_fetch_row($q);

	if($approved == 0)
	{
		$err[] = "You must activate your account, and may do so <a href=\"users/activate.php\">here</a>";
	}

	if(empty($err))
	{
		//If someone was found, check to see if passwords match
		if(mysql_num_rows($q) > 0)
		{
			if(hash_pass($pass2) === $pass)
			{

				$user_info = mysql_query("SELECT id, firstname,lastname, user_name, user_level FROM ".USERS." WHERE id = '$userid' LIMIT 1") or die("Unable to get user info");
				list($id, $firstname,$lastname, $username, $level) = mysql_fetch_row($user_info);
                  echo "Firstname: lastname:".$firstname.$lastname;
				session_start();
				//REALLY start new session (wipes all prior data)
	   			session_regenerate_id(true);

				//update the timestamp and key for session verification
				$stamp = time();
				$ckey = generate_key();
				mysql_query("UPDATE ".USERS." SET `ctime`='$stamp', `ckey` = '$ckey', `num_logins` = num_logins+1, `last_login` = now() WHERE id='$id'") or die(mysql_error());

				//Assign session variables to information specific to user
				$_SESSION['user_id'] = $id;
				$_SESSION['firstname'] = $firstname;
                $_SESSION['lastname'] = $lastname;
				$_SESSION['user_name'] = $username;
				$_SESSION['user_level'] = $level;
				$_SESSION['stamp'] = $stamp;
				$_SESSION['key'] = $ckey;
				$_SESSION['logged'] = true;
				//And some added encryption for session security
				$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

				//Build a message for display where we want it
				$msg = "Logged in successfully!";


				header("Location: ".SITE_BASE."/users/profile.php");
			} //end passwords matched
			else
			{
				//Passwords don't match, issue an error
				$err[] = "Invalid User";
			}
		} //end if user found
		else
		{
			//No rows found in DB matching username or email, issue error
			$err[] = "This user was not found in the database.";
		}
	} //end if no error
}  //end form posted

return_meta("Log in to your account");
?>
<script>
/*$(document).ready(function(){
	$("#login_form").validate();
});  */
</script>
</head>
<body>
<div id="register_body">

	<?php
	//Show message if isset
	if(isset($msg) || !empty($_GET['msg']))
	{
		if(!empty($_GET['msg']))
		{
			$msg = $_GET['msg'];
		}
		echo '<div class="success">'.$msg.'</div>';
	}
	//Show error message if isset
	if(!empty($err))
	{
		echo '<div class="err">';
		foreach($err as $e)
		{
		echo $e.'<br />';
		}
		echo '</div>';
	}
	?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login_form">
            <ul>
                <li>
                    <label>Username/Email:</label><input type="text" name="user" value="<?php echo stripslashes($username); ?>" class="required" />
                </li>
                <li>
                    <label> Password:</label><input type="text" name="pass" value="<?php echo stripslashes($pass2); ?>" class="required" />
                </li>
                <li>   
                    <p><button id ="login" type="submit" name="login">Login</button> <span> <a href = 'password_reset.php'>Forgot password?</a></p> 
                    
                </li>              
                
            </ul>
	</form>

</div>
</body>
</html>