<?php
include_once 'includes/constants/sql_constants.php';

//Pre-assign our variables to avoid undefined indexes
$username = NULL;
$pass2 = NULL;
$msg = NULL;
$email = NULL;
//$err = array();

//See if form was submitted, if so, execute...
if(isset($_POST['login']))
{

	//Assigning vars and sanitizing user input
	$username = filter($_POST['user']);
	$pass2 = filter($_POST['pass']);       

	if(empty($username) || strlen($username) < 4)
	{
		$err[] = "Please enter your username";
	}
	if(empty($pass2) || strlen($pass2) < 4)
	{
		$err[] = "You seem to have forgotten your password.";
	}
        
        $user_name_check = mysqli_query($link,"SELECT username from ".USERS. " WHERE username ='$username'") or die(mysqli_error($link));
        if(mysqli_num_rows($user_name_check) != 0)
        {

            $sql = "SELECT user_password, user_id, approved FROM ".USERS." WHERE username = '$username' OR email = AES_ENCRYPT('$email', '$salt');";

            //Select only ONE password from the db table if the username = username, or the user input email (after being encrypted) matches an encrypted email in the db
            $results = mysqli_query ($link,$sql);
            //Select only the password if a user matched
            $row = mysqli_fetch_array($results, MYSQLI_ASSOC);
            $pass = $row['user_password'];
            $userid = $row['user_id'];
            $approved = $row['approved'];
	
            if($approved == 0)
            {
                    $err[] = "You must activate your account, and may do so <a href=\"users/activate.php\">here</a>";
            }
        } else {
            $err[] = "Invalid username. Please check again!";
        }
	if(empty($err))
	{
		//If someone was found, check to see if passwords match
		if(mysqli_num_rows($results) > 0)
		{
			if(hash_pass($pass2) === $pass)
			{

					$user_info = mysqli_query($link,"SELECT user_id, first_name, username, user_level FROM ".USERS." WHERE user_id = '$userid' LIMIT 1") or die("Unable to get user info");
					list($id, $firstname, $username, $level) = mysqli_fetch_row($user_info);
					
					session_start();
					//REALLY start new session (wipes all prior data)
					session_regenerate_id(true);

					//update the timestamp and key for session verification
					$stamp = time();
					$ckey = generate_key();
					mysqli_query($link,"UPDATE ".USERS." SET `ctime`='$stamp', `ckey` = '$ckey', `num_logins` = num_logins+1, `last_login` = now() WHERE user_id='$id'") or die(mysqli_error($link));

					//Assign session variables to information specific to user
					$_SESSION['user_id'] = $id;
					$_SESSION['firstname'] = $firstname;
					$_SESSION['user_name'] = $username;
					$_SESSION['user_level'] = $level;
					$_SESSION['stamp'] = $stamp;
					$_SESSION['key'] = $ckey;
					$_SESSION['logged'] = true;
					//And some added encryption for session security
					$_SESSION['HTTP_USER_AGENT'] = md5($_SERVER['HTTP_USER_AGENT']);

					//Build a message for display where we want it
					$msg = "Logged in successfully!";


					header("Location: ".BASE."/home.php");
			} //end passwords matched
			else
			{
				//Passwords don't match, issue an error
				$err[] = "Invalid User";
                                header("Location: ".BASE."/index.php");
			}

		}
        } 
	else
	{
		//$err[] = "Invalid username. Please check again!";
	}

}
?>

            <?php 
            if(isset($msg))
                {
                        echo '<div class="success" >'.$msg.'</div>';
                } elseif (isset($err))
                {
                    if(!empty($err))
                     {
                        echo '<div class="err">';
                        foreach($err as $e)
                        {
                        echo $e.'<br />';
                        }
                        echo '</div>';
                    }
                }
            ?>
                <div class="card " id="user_profile_div">
                    
                      <span class="success" style="display:none;"></span>
                      <span class="error" style="display:none;">Please enter some text</span>
                      
                      <div class="front">

                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="login_form">
                                    <p><a href = 'password_reset.php'>Forgot password?</a></p>
                                    <label for="user">Username</label>
                                    <input type="text" name="user" value="<?php echo stripslashes($username); ?>" class="required" />
                                    <label for="pass">Password</label>
                                    <input type="password" name="pass" value="<?php echo stripslashes($pass2); ?>" class="required" />
                                    <br>
                                    <button id ="login" name="login" type="submit">Login</button> 
                            </form>
                      </div>
                      
            </div>
</body>
</html>