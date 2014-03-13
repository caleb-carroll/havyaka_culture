<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="home" content="index, follow" />
        <link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
</head>
  <?php 
  
  require 'includes/constants/sql_constants.php';

  include_once 'includes/swift/lib/swift_required.php';

$meta_title = "Get Started";

$firstname = NULL;
$username = NULL;
$password = NULL;
$city= NULL;
$zipcode = NULL;
$email = NULL;
$pass2 = NULL;
$msg = NULL;
$err = array();


//Check if the user signed up, add user to the user table.
if(isset($_POST['Signup']))
{
	$firstname = filter($_POST['firstname']);
	$username = filter($_POST['username']);
	$password = filter($_POST['pass1']);
        $confirm_pass = filter($_POST['pass2']);
        $city = filter($_POST['city']);
        $email = filter($_POST['email']);
        $state = filter($_POST['state']);
        $zipcode = intval(filter($_POST['zipcode']));
	$date = date('Y-m-d');
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$activation_code = rand(1000,9999);

          $err = array();
           //defined in config.inc.php
           $err = add_user($firstname,$username,$password,$confirm_pass,$email,$city,$state,$zipcode,$date,$user_ip,$activation_code);

         if ( count($err) == 0){
		$msg = "Registration successful!";
		$meta_title = "Registration successful!";
            }
}

return_meta($meta_title);

?>

   <link rel="stylesheet" href="includes/styles/style.css"/>
    </head>
    <title> My Account -Sign Up </title>
    <body>
        <div class = "login_event_section">
          
        <?php
          	//Show message if isset
          	if(isset($msg))
          	{
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
     
    
    
    
    <body id = "register_body">
           <h1>Community Connect</h1> 
         <div class="login_event_section" id="logincol">
             <h1>Login!</h1>
            <?php if(!isset($msg)) // if the user already registered, ask him to login.
             {
                 
                 include_once 'login.php';
             } ?>
           </div>
           
           <div class="login_event_section" id = "eventcol">
                 <div class="public_event_display_header">
                        <p>Happening Events! </p>
                       <?php  include_once 'public_event.php' ?>
                 </div>
                     display events randomly
           </div>          
           <div class="login_event_section" id="infocol">
               information
           </div>
           
           <div class="login_event_section" id="registercol">
               <div class="register_header">
               <h1>Sign Up Now!</h1>
              </div>
               
               <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="register_form">
                    <ul>
                        <li>
                            <label> Username: </label> <input class="account" type="text" name="username" placeholder="John123" value ="<?php echo stripslashes($username); ?>"  required="required">

                        </li>
                        <li>
                            <label> Password: </label> <input class="account" type="password" name="pass1" value="" placeholder="mypassword" required="required">

                        </li>
                         <li>
                            <label> Password: </label> <input class="account" type="password" name="pass2" value="" placeholder="mypassword" required="required">

                        </li>
                        <li>
                            <label> First Name:</label> <input class="account" type="text" name="firstname" value="<?php echo stripslashes($firstname); ?>" placeholder="John" required="required">
                        </li> 
                         <li>
                             <label> Email: </label><input class="account" type="email" name="email" value="<?php echo stripslashes($email); ?>" placeholder="jobhnm@aol.com" required="required">
                        </li>
                         <li>
                            <label> City: </label><input class="account" type="text" name="city" value="<?php echo stripslashes($city); ?>" placeholder="Cedar rapids">
                        </li>
                         <li>
                            <label> State: </label><input class="account" type="text" name="state" value="" placeholder="Iowa">
                        </li>
                        <li>
                            <label> Zip Code:</label> <input class="account" type="number" name="zipcode" value="<?php echo stripslashes($zipcode); ?>" placeholder="52403">   
                        </li>
                        <li>
                            <p><button id ="signup" type="submit" name="Signup">Sign Up</button> </p>

                        </li>
                
                </ul>
            </form>
         </div> 
        
        
    </body>