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
             $("#create_event_div").hide();
              $( ".datepicker" ).datepicker({dateFormat: "yy-mm-dd" });
              
		$('.flip').click(function(){
			console.log("clicked");
			$(this).parent().closest('.flipper').toggleClass('flipped');
		});
                
                $("#create_event_button").click(function() {
                    $("#create_event_div").show();
                    $("#create_event_button").hide(); 
                    return false;
                });
                
                $("#cancel_event").click(function() {
                     $(this).closest('form').find("input[type=text], textarea").val("");
                    $("#create_event_div").hide();
                    $("#create_event").show(); 
                    return false;
                });
                
                $("#add_event").click(function() {
                    var event_name = $("#event_name").val();
                    var event_desc = $("#event_desc").val();
                    var event_date = $(".datepicker").val();
                    var event_type_id = $("#event_type").val();
                    var event_scope = $("#event_scope").val();
                    var venue_name = $("#venue_name").val();
                    var venue_address = $("#venue_address").val();
                    var event_zipcode = $("#event_zipcode").val();
                    
                     if(event_name == '' || event_desc == '' || event_date == '' || venue_name == '' || venue_address == '' || event_zipcode == '')
                        {
                                        //here, we change the html content of all divs with class="error" and show them
                                        //there should be only 1 such div but the code would affect multiple if they were present in the page
                                        $('.error').fadeIn(400).show().html('Please complete all fields.').fadeOut(6000); 
                        }
                        else
                        {
                    
                            var datastring = "event_name=" +event_name+ "&event_desc=" +event_desc+ "&event_date=" +event_date+ "&event_type_id=" +event_type_id+ "&event_scope=" +event_scope+ "&venue_name=" +venue_name+ "&venue_address="+venue_address+ "&event_zipcode="+event_zipcode;
                          console.log(datastring);
                             $.ajax(
                                       {
                                               type: "POST",
                                               url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=add_event", 
                                               data: datastring,
                                               success: function()
                                               {
                                                    $('.success').fadeIn(2000).show().html('Event created successfully!').fadeOut(6000); //Show, then hide success msg
                                                   $('.error').fadeOut(2000).hide(); //If showing error, fade out   
                                                   $(':input','#create_event_form').not(':button, :submit, :reset, :hidden')
                                                    .val('')
                                                    .removeAttr('checked')
                                                    .removeAttr('selected');
                                                    window.location.reload();
                                               }
                                       }
                               );
                       }

                       return false;
                    
                });
                
                
                
	})
</script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();
$user_id = $_SESSION['user_id'];


if($_POST and $_GET){
	if ($_GET['cmd'] == 'update_event'){
		
		$event_name = filter($_POST['event_name']);
		$event_date =filter($_POST['event_date']);
		$event_desc = filter($_POST['event_desc']);               
		$event_scope = filter($_POST['event_scope']);
                $e_type_id = filter($_POST['event_type']);
                $venue_name = filter($_POST['venue_name']);
                $venue_address = filter($_POST['venue_address']);
                $event_zipcode = filter($_POST['event_zipcode']); 
		$e_recurring_id = 1;
		$event_id = $_POST['event_id'];
		
		// function to update an event 
		if (update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_name, $venue_address,$event_zipcode, $e_recurring_id, $event_id)) {
			// add something here to display success/failure?
			 $msg="Event updated successfully";
		}
		else {
			$err = "Oops!. sorry, could not update your event, Please try again";
		}
	}

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
            echo "1";
            print_r($_FILES);
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
	
	// to do: create form that calls this code
	if ($_GET['cmd'] == 'add_event'){
		$event_name = filter($_POST['event_name']);
		$event_date =filter($_POST['event_date']);
		$event_desc = filter($_POST['event_desc']);               
		$event_scope = filter($_POST['event_scope']);
                $e_type_id = filter($_POST['event_type_id']);
                $venue_name = filter($_POST['venue_name']);
                $venue_address = filter($_POST['venue_address']);
                $event_zipcode = filter($_POST['event_zipcode']);   
		$e_recurring_id = 1;
                $community_id = 1;
		
		$msg = NULL;
                $err=NULL;
		// function to add an event 
		if (add_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $user_id, $venue_name,$venue_address,$event_zipcode, $community_id, $e_recurring_id)) {
			 
			 $msg="Event is created successfully";
		}
		else {
			 $err = "Oops!. sorry, could not create an event, Please try again";
		} 
	}
	
	if ($_GET['cmd'] == 'delete_event'){
		$event_id = $_POST['event_id'];
		
		// function to add an event 
		if (delete_event($event_id)) {
			$msg = "Event updated successfully!";
		}
		else {
			$err = "Oops!. sorry, could not update this event, Please try again";
		}
	}
	
}

$user_info = get_user_info($user_id);
$profile_pic = $user_info[0]['profile_picture'];
 $profile_pic_loc = htmlspecialchars($profile_pic);
  $profile_pic_loc = BASE.$profile_pic_loc;
list($width, $height, $type, $attr)= getimagesize($profile_pic_loc);


$chef_info = get_chef_info($user_id);

//get the event types
$event_types = get_event_types();


?>

<head>
	<title>Community Connect</title>
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
                    
                     
			<!-- Middle column start -->
			<div class="card">
			<div class="front">
				<p>User Profile</p>
					
					<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_user" method="post">
						First name: <input type="text" class="input_box" name="first_name" value="<?php echo $user_info[0]['first_name'];?>"><br><br>
						Last name: <input type="text" class="input_box" name="last_name" value="<?php echo $user_info[0]['last_name'];?>"><br><br>
						Phone: <input type="text" class="input_box" name="phone" value="<?php echo $user_info[0]['phone'];?>"><br><br>
						Email: <input type="text" class="input_box" name="email" value="<?php echo $user_info[0]['email'];?>"><br><br>    
						<input type="checkbox" value="public_info">Allow others to see my contact info
						<button type="submit">Save Changes</button>
					</form>
					
					<p>Upload Picture</p>
					<img id="profile_picture" src="<?php echo $profile_pic_loc;?>" />
					<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_picture" method="post" enctype="multipart/form-data">
						<label for="file">Filename:</label>
						<input type="file" name="file" id="file"><br>
						<input type="submit" name="submit" value="Submit">
					</form>
				
			</div>
			</div>
			
			<div class="card">
				<div class="front">
				<p>Chef Profile</p>
					Phone: <input type="text" class="input_box" name="phone"><br><br>
					Email: <input type="text" class="input_box" name="email"><br><br>
					Contact Hours: <input type="text" class="input_box" name="lname" value="<?php echo $chef_info[0]['contact_time_preference'];?>"><br><br>
					
					<!-- marks these checkboxes as checked or unchecked based on what we find in the DB -->
					<input type="checkbox" value="pickup" <?php if($chef_info[0]['pickup_available'] == "Yes") echo "checked"; else echo "unchecked";?>>Offer pickup?
					<input type="checkbox" value="offline" <?php if($chef_info[0]['taking_offline_order'] == "Yes") echo "checked"; else echo "unchecked";?>>Take offline orders?
					<input type="checkbox" value="delivery" <?php if($chef_info[0]['delivery_available'] == "Yes") echo "checked"; else echo "unchecked";?>>Offer delivery?
					
					<!-- not sure what we are doing with this dropdown  -->
					<select class="dropdown">
						<option selected value="default">Please Select a Food Type</option>
						<option value="test1">Test1</option>
						<option value="test2">Test2</option>
						<option value="test3">Test3</option>
					</select>
					
					<button type="button" id="food_request">Request new Food Type</button>
					
				</div>
			</div>

			<div id="event_holder">
                            
                            <br></br> <button name="create_event" id="create_event_button">Create an event</button>
                            <div class="card" id="create_event_div">
                               
                                <h3>Create a new event!</h3>
                                <div class="front">
                                     <div class="success" style="display:none;"></div>
                                     <div class="error" style="display:none;">Please enter some text</div>
                                     <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" id="create_event_form" onsubmit="return false;" method="post">
						<table>
							<tr><td width="25%">Event Name</td><td><input type="text" id="event_name" name="event_name"></td></tr>
							<tr><td>Event Venue Name</td><td><input type="text" id="venue_name" name="event_venue" ></td></tr>
                                                        <tr><td>Event Location Address</td><td><input type="text" id="venue_address" name="venue_address"></td></tr>
                                                        <tr><td>Zipcode</td><td><input type="text" id="event_zipcode" name="event_zipcode" ></td></tr>
                                                        <tr><td>Event Date</td><td><input type="text" class="datepicker" value="" name="event_date"></td></tr>                                                        
                                                        <tr><td>Event Type</td>
                                                            <td> 
                                                              <select id="event_type">
                                                                <?php
                                                                  foreach($event_types as $r) 
                                                                  {
                                                                  ?>
                                                                    <option value="<?php echo $r['e_type_id']; ?>"><?php echo $r['event_type']; ?></option>
                                                                      
                                                                  <?php } ?>
                                                                </select> </td></tr>
							<tr><td>Event Scope</td>
                                                            <td>
                                                                <select id="event_scope">
                                                                    <option value="public">Public</option>
                                                                    <option value="private">Private</option>
                                                                </select>                                                                
                                                            </td></tr>
							<tr><td>Event Details</td><td><textarea id="event_desc" name="event_desc"></textarea><td></tr>
						</table>                                                                                   
                                                  <button type="submit" id="add_event">Add Event</button> &nbsp; <button type="submit" id="cancel_event">Cancel Event</button>	
                                        </form>					
                                </div>
                            </div>
                               
				<?php
				
				// get_events function defined in sql_constants.php
                           $results = get_events($user_id);

                            // add each event returned to an event card
                           if($results) 
                           {
				foreach ($results as $r) {
                                    //get event_picture
                                    $event_id = $r['event_id'];
                                    $event_image = get_event_picture($event_id);
                                        $event_image_loc = htmlspecialchars($event_image);
                                        $event_image_loc = BASE.$event_image_loc;
                                        list($width, $height, $type, $attr)= getimagesize($event_image_loc);
                                        
                                       // foreach of the event, get the number of attendance
                                        
                                        $event_attendace = get_attendance_count_list($event_id);

						
				?>
				<div class="card flipper">
					<div class="front">
						<button class="flip">Cancel</button>
						<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_event" method="post">
						<input style="display:none" type="text" name="event_id" value="<?php echo $r['event_id']?>">
						<table>
							<tr><td width="25%">Event Name</td><td><input type="text" name="event_name" value="<?php echo $r['event_name']; ?>"></td></tr>
                                                        <tr><td>Event Venue Name</td><td><input type="text" name="venue_name" value="<?php echo $r['venue_name']?>" ></td></tr>
                                                        <tr><td>Event Location Address</td><td><input type="text" name="venue_address" value="<?php echo $r['venue_address']?>"></td></tr>
                                                        <tr><td>Zipcode</td><td><input type="text" id="event_zipcode" name="event_zipcode" value="<?php echo $r['zipcode']?>" ></td></tr>
							<tr><td>Event Type</td>
                                                            <td> 
                                                              <select name="event_type" id="get_event_type">
                                                                <?php
                                                                  foreach($event_types as $row) 
                                                                  {
                                                                      echo $row['event_type'];
                                                                  ?>
                                                                  <option value="<?php echo $row['e_type_id']; ?>" ><?php echo $row['event_type']; ?></option>
                                                                      
                                                                  <?php } ?>
                                                              </select> </td>
                                                        </tr>
                                                        <tr><td>Event Scope</td>
                                                            <td>
                                                                <select name="event_scope" id="event_scope">
                                                                    <option value="public">Public</option>
                                                                    <option value="private">Private</option>
                                                                </select>                                                                
                                                            </td></tr>
							<tr><td>Event Date</td><td><input type="text" class="datepicker" name="event_date" value="<?php echo $r['event_date']?>"></td></tr>
							<tr><td>Event Details</td><td><textarea name="event_desc"><?php echo $r['event_desc']?></textarea><td></tr>
						</table>
                                                   
						<img class="event_picture" src="<?php echo $event_image_loc; ?>" /> 
                                                <button type="submit">Save Changes</button> &nbsp;
						</form>
						<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=delete_event" method="post">
							<input style="display:none" type="text" name="event_id" value="<?php echo $r['event_id']?>">
							<button type="submit">Delete Event</button>
						</form>
						
						<p>Upload a Picture</p>
						<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_event_picture" method="post" enctype="multipart/form-data">
                                                    <input style="display:none" type="text" name="event_id" value="<?php echo $r['event_id']?>">
							<label for="file">Filename:</label>
						<input type="file" name="file" id="file_event"><br>
						<input type="submit" name="submit" value="Update">
						</form>
						
					</div>
					<div class="back">
						<button class="flip">Edit Event</button>
						<table>
                                                    <tr><td width="25%">Event Name</td><td><?php echo $r['event_name']; ?></td><img class="event_picture" src="<?php echo $event_image_loc; ?>" /> <td</tr>
							<tr><td>Event Location</td><td><?php echo $r['venue_name'] . "<br>" . $r['venue_address'] . "<br>" . $r['city'] . ", " . $r['state'] . " " . $r['zipcode']?></td></tr>
							<tr><td>Event Type</td><td><?php echo $r['event_type']?></td></tr>
                                                        <tr><td>Event Scope</td><td><?php echo $r['event_scope']?></td></tr>
							<tr><td>Event Date</td><td><?php echo $r['event_date']; ?></td></tr>
							<tr><td>Event Details</td><td><?php echo $r['event_desc']; ?><td></tr>
                                                        <?php
                                                        if($event_attendace !=NULL)
                                                        {
                                                         $count=$event_attendace;
                                                        } else
                                                        {
                                                            $count = "No attandance!";
                                                        }
                                                        ?>
                                                        <tr><td>Attendance count</td><td><?php echo $count; ?><td></tr>
                                                        
						</table>
					</div>
				</div>
				<?php }
                           } else
                           {
                               echo "<h2> No events found!. Add one now!</h2>";
                           }
                                
                                ?>
			</div>
			<!-- Center column end -->
			
		</div>
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>

</html>
