<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<script type="text/javascript" src="includes\js\scripts.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
 
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


function get_city_state(zipcode) {

            var zip = zipcode;
            var country = 'United States';
            var lat;
            var lng;
            var geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'address': zipcode + ',' + country }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    geocoder.geocode({'latLng': results[0].geometry.location}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        if (results[1]) {

                            var loc = getCityState(results,zipcode);
                        }
                 }
            });
        }
    }); 
 }


function getCityState(results,zipcode)
{

        var a = results[0].address_components;
        var city, state;
        for(i = 0; i <  a.length; ++i)
        {
           var t = a[i].types;
           if(compIsType(t, 'administrative_area_level_1'))
              state = a[i].long_name; //store the state
           else if(compIsType(t, 'locality'))
              city = a[i].long_name; //store the city
        }
        var datastring = "zipcode= "+zipcode+ "&city= " +city+"&state= "+state;
        alert(datastring);
         $.ajax(
              {
                        type: "POST",
                        url: "updateaddress.php?cmd=updatecitystate", 
                        data: datastring,                        
                }
         );
          return false;
    }

function compIsType(t, s) { 
       for(z = 0; z < t.length; ++z) 
          if(t[z] === s)
             return true;
       return false;
    }
    
$(function(){
             $("#create_event_div").hide();
             $("#saved_event_div").hide();
             $("#saved_chef_div").hide();
             $("#close_card_event_chef").hide();
             $("#change_food_pic_form").hide();
             
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
                    $("#create_event_button").show(); 
                    return false;
                });
                
                $("#saved_event_button").click(function() {
                    $("#saved_event_div").show();
                    $("#close_card_event_chef").show();
                    
                });
                 $("#saved_chef_button").click(function() {
                    $("#saved_chef_div").show();
                    $("#close_card_event_chef").show();
                   
                });
                $("#close_card_event_chef").click(function() {
                    $("#saved_event_div").hide();
                 $("#saved_chef_div").hide();
                 $("#close_card_event_chef").hide(); 
                  $("#user_profile_div").hide();
                });
                
                $("#change_food_pic").click(function() {
                    $("#change_food_pic_form").show();
                     $("#change_food_pic").hide();                    
                });
                $("#user_profile_button").click(function() {
                    $("#user_profile_div").show();
                    $("#close_card_event_chef").show(); 
                });
                
                
              /*  $("#add_event").click(function() {
                    var event_name = $("#event_name").val();
                    var event_desc = $("#event_desc").val();
                    var event_date = $(".datepicker").val();
                    var event_type = $("#event_type").val();
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
                    
                            var datastring = "event_name=" +event_name+ "&event_desc=" +event_desc+ "&event_date=" +event_date+ "&event_type_id=" +event_type+ "&event_scope=" +event_scope+ "&venue_name=" +venue_name+ "&venue_address="+venue_address+ "&event_zipcode="+event_zipcode;
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
                                                    //window.location.reload();
                                               }
                                       }
                               );
                       }

                                               
                       return false;
                    
                }); */
})

</script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();
$user_id = $_SESSION['user_id'];

		$msg = NULL;
                $err=NULL;

if($_POST and $_GET){
	if ($_GET['cmd'] == 'update_event'){
		echo "inside update_event";
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
                   ?>
                       <script>
                         get_city_state('<?php echo $event_zipcode;?>');
                        </script>  
                   <?php 
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
                $e_type_id = filter($_POST['event_type']);
                $venue_name = filter($_POST['venue_name']);
                $venue_address = filter($_POST['venue_address']);
                $event_zipcode = filter($_POST['event_zipcode']);   
		$e_recurring_id = 1;
                $community_id = 1;
                
		// function to add an event 
		if (add_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $user_id, $venue_name,$venue_address,$event_zipcode, $community_id, $e_recurring_id)) {
			
	           ?>
                       <script>
                         get_city_state(<?php echo $event_zipcode;?>);
                        </script>  
 
                      <?php 
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

//Get the chef details of the logged in user if exists
$chef_info = get_chef_details_logged_in_user($user_id);

$chef_id =$chef_info[0]['chef_id'];
$about_chef = $chef_info[0]['about_chef'];
$contact_time_preference = $chef_info[0]['contact_time_preference'];
$pickup_available = $chef_info[0]['pickup_available'];
echo $about_chef."<br>".$contact_time_preference."<br>".$pickup_available;

//Get the foods that the chef is preparing.
$food_chef = get_foods_of_chef($chef_id);

//get the event types
$event_types = get_event_types();

 $results = get_events($user_id);
?>

<head>
	<title>Manage Events</title>
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
                 <div id="event_holder">
                            
                    <br></br> <button name="create_event" id="create_event_button">Create an event</button>
                    <div class="card flipper" id="create_event_div" >

                        
                        <div class="back">
                            <h3>Create a new event!</h3>
                             <div class="success" style="display:none;"></div>
                             <div class="error" style="display:none;">Please enter some text</div>
                             <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_event" id="create_event_form" method="post">
                                        <table>
                                                <tr><td width="25%">Event Name</td><td><input type="text" id="event_name" name="event_name"></td></tr>
                                                <tr><td>Event Venue Name</td><td><input type="text" id="venue_name" name="venue_name" ></td></tr>
                                                <tr><td>Event Location Address</td><td><input type="text" id="venue_address" name="venue_address"></td></tr>
                                                <tr><td>Zipcode</td><td><input type="text" id="event_zipcode" name="event_zipcode" ></td></tr>
                                                <tr><td>Event Date</td><td><input type="text" class="datepicker" value="" name="event_date"></td></tr>                                                        
                                                <tr><td>Event Type</td>
                                                    <td> 
                                                      <select name ="event_type" id="event_type">
                                                        <?php
                                                          foreach($event_types as $r) 
                                                          {
                                                          ?>
                                                            <option value="<?php echo $r['e_type_id']; ?>"><?php echo $r['event_type']; ?></option>

                                                          <?php } ?>
                                                        </select> </td></tr>
                                                <tr><td>Event Scope</td>
                                                    <td>
                                                        <select name="event_scope" id="event_scope">
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
							<tr><td>Event Location</td><td><?php echo $r['venue_name'] . "<br>" . $r['venue_address'] . "<br>" . $r['city'] . ", " . $r['state'] . " - " . $r['zipcode']?></td></tr>
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
