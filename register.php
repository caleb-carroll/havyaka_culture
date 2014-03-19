<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="home" content="index, follow" />
        <link rel="stylesheet" type="text/css" href="includes/styles/styles.css" media="screen" />
        <script src="includes/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
        
        <script>
$(document).ready(function(){
           $('#username').focus();
           
           
});
        function refresh_content() 
        {
            $("#eventcol").fadeIn(450).show().load('public_event.php');
        }
        setInterval( refresh_content, 6000 );
        </script>
      
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
        $community_type = $_POST['community_type'];
        echo "community type selected is: ".$community_type;

          $err = array();
           //defined in config.inc.php
           $err = add_user($firstname,$username,$password,$confirm_pass,$email,$city,$state,$zipcode,$date,$user_ip,$activation_code,$community_type );

         if ( count($err) == 0){
		$msg = "Registration successful!";
		$meta_title = "Registration successful!";
            }
}

return_meta($meta_title);

?>

   <link rel="stylesheet" href="includes/styles/styles.css"/>
    </head>
   
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
             <h1>Login to your account</h1>
            <?php
                 
                 include_once 'login.php';
              ?>
           </div>
           
           <div class="login_event_section" id = "eventcol">
                 <div class="public_event_display_header">
                                
                 </div> 
                   <?php include_once 'public_event.php'; ?>                            
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
                            <label> Username: </label> <input class="account" id="username" type="text" name="username" placeholder="John123" value ="<?php echo stripslashes($username); ?>"   required="required">

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
                            <label> Zip Code:</label> <input class="account" id ="zipcode" type="number" name="zipcode" value="<?php echo stripslashes($zipcode); ?>" placeholder="52403">   
                        </li>
                        <li>
                            <label> Community Type:</label> <select name='community_type' class='account' id='c_type'>
                                <option value="" selected></option>
                                <?php 
                                    $val = $_POST['community_type']?:'';
                                    $q = "SELECT community_name,community_id from ".COMMUNITY_TYPE. " WHERE 1";
                                    echo $q;
                                    $query = mysqli_query($link,$q);
                                   
                                       while ($row = mysqli_fetch_assoc($query))
                                       {
                                           $selected = ($val == $row['community_name'] ? 'selected="selected"' : '');
                                           echo '<option value ="' . $row['community_name'] . '" '. $selected .'>' . $row['community_name'] . '</option>';
                                       }
                                ?>
                            </select>
                           
                        </li>
                        <li>
                            <p><button id ="signup" type="submit" name="Signup">Sign Up</button> </p>

                        </li>
                
                </ul>
            </form>
         </div> 
        
        
    </body>