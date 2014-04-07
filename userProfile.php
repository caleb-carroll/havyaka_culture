<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<script type="text/javascript" src="includes\js\scripts.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 
<script>
	function doesCSS(p){
		var s = ( document.body || document.documentElement).style;
		return !!$.grep(['','-moz-', '-webkit-'],function(v){
			return  typeof s[v+p] === 'string'
		}).length
	}

	$('html')
		.toggleClass('transform',doesCSS('transform'))
		.toggleClass('no-transform',!doesCSS('transform'));

$(function(){
      $('.flip').click(function(){
              console.log("clicked");
              $(this).parent().closest('.flipper').toggleClass('flipped');
      });

                
});

</script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();
$user_id = $_SESSION['user_id'];

		$msg = NULL;
                $err=NULL;

if($_POST and $_GET){
	
	if ($_GET['cmd'] == 'update_user'){
		 
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$profile_picture = NULL;
		
		// function to update an event 
		if (update_user_info($user_id, $first_name, $last_name, $email, $phone, $profile_picture)) {
			// add something here to display success/failure?
			 $msg="Profile updated successfully";
		}
		else {
			$err = "Oops!. sorry, could not update your profile, Please try again";
		}
	}
	
	// if the user is adding a picture, add it to the file system and reference in user table
	if ($_GET['cmd'] == 'add_picture' || $_GET['cmd'] == 'add_event_picture'){
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		}
		else {
			$file_handler = $_FILES["file"];
			$picture = store_image($file_handler);
                        $picture_loc = "/".$picture;
                        if($_GET['cmd'] == 'add_picture') {
                            // $user_info[0]['profile_picture'] = $profile_picture;
                            update_user_info($user_id, NULL, NULL, NULL, NULL, $profile_picture_loc);
                        } 
                        elseif ($_GET['cmd'] == 'add_event_picture') 
                        {
                            echo "coming inside add_event-picture";
                            $event_id = $_POST['event_id'];
                            update_event_picture($picture_loc,$event_id);
                        }
		}
	}
   }

    $user_info = get_user_info($user_id);
    $profile_pic = $user_info[0]['profile_picture'];
    $profile_pic_loc = htmlspecialchars($profile_pic);
    $profile_pic_loc = BASE.$profile_pic_loc;
    list($width, $height, $type, $attr)= getimagesize($profile_pic_loc);

    //Get the chef details of the logged in user if exists
    $chef_info_ret = get_chef_details_logged_in_user($user_id);
    $chef_info = array_filter($chef_info_ret);

    if(!empty($chef_info)) {
        $chef_id =$chef_info[0]['chef_id'];
        $about_chef = $chef_info[0]['about_chef'];
        $contact_time_preference = $chef_info[0]['contact_time_preference'];
        $pickup_available = $chef_info[0]['pickup_available'];
        //echo "about chef".$about_chef."<br>".$contact_time_preference."<br>".$pickup_available;

        if($chef_id !=NULL){
          $food_chef = get_foods_of_chef($chef_id);
        }
    }
//Get the foods that the chef is preparing.

//get the event types
$event_types = get_event_types();
$results = get_events($user_id);
?>

<head>
	<title>My Profile</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>
</head>
<body>

<div id="header">
	<h1>Community Connect</h1>	
	<?php include('includes/navigation.inc.php'); ?>	
</div>
	
<div class="content leftmenu">
    <div class="colright">
	<div class="col1">
            <!-- Left Column start -->
            <?php include('includes/left_column.inc.php'); ?>
            <!-- Left Column end -->
		</div>
        <div class="col2">
            <?php 
            if(isset($msg))
                {
                        echo '<div class="success" >'.$msg.'</div>';
                } elseif (isset($err))
                {
                    echo '<div class="error">'.$err.'</div>';
                }
            ?>
                <div class="dashboard_sub_section">  
                    <?php include('includes/subnavigation.inc.php'); ?>
                 </div>
                   
			<!-- Middle column start -->
                <div class="card " id="user_profile_div"  style="width: 65%;height: 70%; overflow-y: scroll;">
                    <div class="front">
                        <?php 
                        if(empty($results))
                        { ?>
                             <a href="manageEvents.php" name="create_events">Create an Event</a>&nbsp;&nbsp;
                       <?php } else
                        { ?>
                            <a href="manageEvents.php" name="manage_events">Manage your Events</a>&nbsp;&nbsp;
                      <?php  }
                        if($chef_info == NULL)
                        {
                        ?>
                            <h4>Become and chef and show off your cooking skill!</h4>
                            <a href="chefProfile.php" name="create_chef_profile">Create a Chef Profile</a>&nbsp;&nbsp;
                        <?php 
                        } else {?>
                             <a href="chefProfile.php" name="edit_chef_profile">Edit your Chef Profile</a>&nbsp;&nbsp;
                       <?php } ?>
                              
                        <p><h2>Hello &nbsp;<?php echo $user_info[0]['first_name'];?>,</h2>&nbsp;&nbsp;<br><br><img style="margin-left: 10em; width: 15em;height: 15em;" id="profile_picture" src="<?php echo $profile_pic_loc;?>" /><br><br><h3>Edit your profile here:</h3></br></p>

                        <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_user" method="post">
                                First name: <input type="text" class="input_box" name="first_name" value="<?php echo $user_info[0]['first_name'];?>"><br><br>
                                Last name: <input type="text" class="input_box" name="last_name" value="<?php echo $user_info[0]['last_name'];?>"><br><br>
                                Phone: <input type="text" class="input_box" name="phone" value="<?php echo $user_info[0]['phone'];?>"><br><br>
                                Email: <input type="text" class="input_box" name="email" value="<?php echo $user_info[0]['email'];?>"><br><br>    
                                       <input type="checkbox" value="public_info">Allow others to see my contact info<br></br>
                                <button type="submit">Save Changes</button>
                        </form>
                        <p>Upload a Picture</p>
                        <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_picture" method="post" enctype="multipart/form-data">					
                                <input type="file" name="file" id="file"><br>
                                <input type="submit" name="submit" value="Submit">
                        </form>
		</div>
	   </div>       
       <!-- Center column end -->
			
       </div>
    </div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>

</html>
