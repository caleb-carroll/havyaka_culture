<head>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<?php 
require_once 'includes/constants/sql_constants.php';
secure_page();
$user_id = $_SESSION['user_id'];
?>
<script>
// this function and the html statements below are used to initialize card flipping functionality
function doesCSS(p){
	var s = ( document.body || document.documentElement).style;
	return !!$.grep(['','-moz-', '-webkit-'],function(v){
		return typeof s[v+p] === 'string'
	}).length
}
	
$('html')
	.toggleClass('transform',doesCSS('transform'))
	.toggleClass('no-transform',!doesCSS('transform'));

// function to get the city and state from Google, then pass them to getCityState in order to update it in the DB
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

// function to update the city and state for a zipcode in the DB
function getCityState(results,zipcode) {
	var a = results[0].address_components;
	var city, state;
	for(i = 0; i <  a.length; ++i) {
		var t = a[i].types;
		if(compIsType(t, 'administrative_area_level_1'))
			state = a[i].long_name; //store the state
		else if(compIsType(t, 'locality'))
			city = a[i].long_name; //store the city
	}
	
	
	var datastring = "zipcode="+zipcode+ "&city=" +city+"&state="+state;
	 $.ajax({
		type: "POST",
		url: "updateaddress.php?cmd=updatecitystate", 
		data: datastring,
		success: function(response){
			console.log(response);
		}
	});
	
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

	// creates a jquery datepicker on all editable date areas
	$( '.datepicker').datepicker({dateFormat: "yy-mm-dd" });
	
	// flips cards over to edit/cancel editing
	$('.flip').click(function(){
		$(this).parent().closest('.flipper').toggleClass('flipped');
	});
	
	$('.manage_event').show('slide', {direction: "left"}, 400);
	
	$("#create_event_button").click(function() {
		$("#create_event_button").fadeOut(700); 
		$('#create_event_div').show('slide', {direction: "up"}, 900);
	});
	
	$("#cancel_add_event").click(function() {
		$(this).closest('form').find("input[type=text], textarea").val("");
		$("#create_event_button").fadeIn(700);
		$('#create_event_div').hide('slide', {direction: "up"}, 900);
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
	
	$('.delete_event_button').click(function() {
		var card_id = $(this).closest('.flipper').attr('id');
		var datastring = $('#' + card_id).find('input:text[name=event_id]').val()
		
		datastring = "event_id=" + datastring;
		console.log(datastring);
		
		// ajax call to delete the event
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/event_interactions.php?cmd=delete_event", 
			data: datastring,
			success: function(response){
				// results will return with success=true or false
				// to be implemented later
				console.log(response);
				var results = JSON.parse(response);
				
				// Show a message at the top of the page that the event was deleted.
				$('.success').animate({opacity:1}, 2000).html("Event deleted! ").animate({opacity:0}, 6000); //Show, then hide success msg
				
				// a jquery effect to visually indicate that the card has been deleted
				$("#" + card_id).effect('shake', { direction: "down", distance: "15", times: "2"}, function(){
					$("#" + card_id).toggle('slide', 900);
				});
				
			}
		});
		return false;
	});
	
	// button for updating events
	$('.update_event_button').click(function() {
		// gets the closest event card in order to know which one to update
		var card_id = $(this).closest('.flipper').attr('id');
		
		// datastring for ajax call
		var datastring = $("#" + card_id).find(".update_event_form").serialize();
		console.log("datastring is: " + datastring);
		
		// The below function makes an asyncronous call to google to get the city and state associated with the provided zip code. It then updates the database when a match is found.
		get_city_state($("#" + card_id).find('.get_event_zipcode').val());
		
		// this call inserts the updated values in the DB
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/event_interactions.php?cmd=update_event", 
			data: datastring,
			success: function(response){
				
				// Show a message at the top of the page that the event was updated.
				$('.success').animate({opacity:1}, 2000).html("Event updated! ").animate({opacity:0}, 6000); //Show, then hide success msg
				
				// a jquery effect to visually indicate that the changes have been saved
				$("#" + card_id).effect('shake', { direction: "down", distance: "15", times: "2"}, function(){
					$("#" + card_id).toggleClass('flipped');
				});
				
				// parses JSON response
				console.log(response);
				updated_event = JSON.parse(response);
				
				// accesses the first/only array within the parsed JSON response and assigns it a variable
				updated_event = updated_event[0];
				// sets the read-only fields on the other side of the card to their updated values
 				$("#" + card_id).find('.event_name').html(updated_event.event_name);
				$("#" + card_id).find('.event_date').html("on: " + updated_event.event_date);
				$("#" + card_id).find('.venue_location').html(updated_event.venue_name + "<br>" + updated_event.venue_address + "<br>" + updated_event.city + ", " + updated_event.state + " " + updated_event.zipcode);
				$("#" + card_id).find('.event_type').html("Event Type: " + updated_event.event_type);
				$("#" + card_id).find('.event_scope').html("Event Scope: " + updated_event.event_scope);
				$("#" + card_id).find('.event_desc').html(updated_event.event_desc);
				
			}
		}); 
		
		return false;
	});
	
	$("#add_event").click(function() {
		var datastring = $("#create_event_form").serialize();
		console.log(datastring);
		
		get_city_state($("#create_event_div").find('.get_event_zipcode').val());
		
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/event_interactions.php?cmd=add_event", 
			data: datastring,
			success: function(response){
				// console.log(response);
				var parsed_response = JSON.parse(response || "null");
				
				$('.success').fadeIn(2000).show().html("event added! ").fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); //If showing error, fade out   
				$('#create_event_div').hide('slide', {direction: "up"}, 900);
				$("#create_event_button").fadeIn(700);
				$(':input','#create_event_form').not(':button, :submit, :reset, :hidden')
					.val('')
					.removeAttr('checked')
					.removeAttr('selected');
					//window.location.reload();
			}
		}); 
		
		return false;
	});
})

</script>

<?php


$msg = NULL;
$err=NULL;

if($_POST and $_GET){
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


<title>Manage Events</title>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta name="robots" content="index, follow" />
<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>
</head>

<body>
  <?php
          include_once ('includes/header.inc.php');
        include('includes/navigation.inc.php'); ?>
	
<div class="content leftmenu">
	<div class="colright">
		<div class="col1">
			<!-- Left Column start -->
			<?php include('includes/left_column.inc.php'); ?>
			<!-- Left Column end -->
		</div>
		<div class="col2">
			<?php 
			if(isset($msg)) {
				echo '<div class="success" >'.$msg.'</div>';
			} 
			elseif (isset($err)) {
				echo '<div class="error">'.$err.'</div>';
			}
			?>
			
			<div class="dashboard_sub_section">  
				<?php include('includes/subnavigation.inc.php'); ?>
			</div>
			<div id="event_holder">
			
	<!-- begin add event card -->
			<button name="create_event" id="create_event_button" style="display:block">Create an event</button>
			<div class="card flipper" id="create_event_div" style="display:none">
				<div class="back">
					<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_event" id="create_event_form" method="post">
							<div class="event_edit_left">
							<h3>Create a new event!</h3>
								<input style="display:none" type="text" name="user_id" value="<?php echo $user_id ?>">
								
								<label for="event_name">Event Name</label>
								<input type="text" name="event_name" class="get_event_name" value="">
									
								<label for="event_date">Event Date</label>
								<input type="text" class="datepicker" class="get_event_date" name="event_date" value="">
									
								<label for="venue_name">Venue Name</label>
								<input type="text" name="venue_name" class="get_venue_name" value="" >
								
								<label for="venue_address">Venue Street Address</label>
								<input type="text" name="venue_address" class="get_venue_address" value="">
								
								<label for="venue_city">Venue City</label>
								<input type="text" name="venue_city" class="get_venue_city" value="">
								
								<label for="venue_address">Venue State</label>
								<input type="text" name="venue_state" class="get_venue_state" value="">
								
								<label for="event_zipcode">Venue Zipcode</label>
								<input type="text" name="event_zipcode" class="get_event_zipcode" value="" >
							</div>
							<div class="event_edit_right">
								
								<label for="event_type">Event Type</label>
								<select name="event_type" class="get_event_type">
								<?php
									foreach($event_types as $row) {
									echo $row['event_type'];
									?>
									<option value="<?php echo $row['e_type_id']; ?>" ><?php echo $row['event_type']; ?></option>
								<?php } ?>
								</select>
										
								<label for="event_scope">Event Scope</label>
								<select name="event_scope" id="get_event_scope">
									<option value="public">Public</option>
									<option value="private">Private</option>
								</select>
								<label for="event_desc">Event Description</label>
								<textarea name="event_desc" class="get_event_desc" cols=20 rows=3></textarea>
							</div>
							<div class="event_edit_bottom">
								<button type="button" name="cancel_add" id="cancel_add_event">Cancel</button>
								<button type="button" name="add_event" id="add_event">Add Event</button>
							</div>
					</form>
				</div>
			</div>
	<!-- END Add Events Card -->

		<!-- begin existing events cards -->
			<?php
			if($results) {
				foreach ($results as $r) {
					//get event_picture
					$event_id = $r['event_id'];
					$event_name = $r['event_name'];
					$venue_name = $r['venue_name'];
					$venue_address = $r['venue_address'];
					$zip_code = $r['zipcode'];
					$event_date = $r['event_date'];
					$event_desc = $r['event_desc'];
					$city = $r['city'];
					$state = $r['state'];
					$zipcode = $r['zipcode'];
					$event_type = $r['event_type'];
					$event_scope = $r['event_scope'];
					
					
					$event_image = get_event_picture($event_id);
					$event_image_loc = htmlspecialchars($event_image);
					$event_image_loc = BASE.$event_image_loc;
					list($width, $height, $type, $attr)= getimagesize($event_image_loc);

					// foreach of the event, get the number of attendance

					$event_attendance = get_attendance_count_list($event_id);
					
					if($event_attendance !=NULL) {
						$count=$event_attendance;
					} 
					else {
						$count = "No attandance!";
					}
					?>
					
					<div class="card flipper manage_event" id="event_<?php echo $event_id; ?>">
			<!-- Event editing section below -->
						<div class="front">
							<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_event" method="post" class='update_event_form'>
							<div class="event_edit_left">
								<input style="display:none" type="text" name="event_id" value="<?php echo $event_id ?>">
								
								<label for="event_name">Event Name</label>
								<input type="text" name="event_name" class="get_event_name" value="<?php echo $event_name; ?>">
									
								<label for="event_date">Event Date</label>
								<input type="text" class="datepicker" class="get_event_date" name="event_date" value="<?php echo $event_date ?>">
									
								<label for="venue_name">Venue Name</label>
								<input type="text" name="venue_name" class="get_venue_name" value="<?php echo $venue_name ?>" >
								
								<label for="venue_address">Venue Street Address</label>
								<input type="text" name="venue_address" class="get_venue_address" value="<?php echo $venue_address ?>">
								
								<label for="venue_city">Venue City</label>
								<input type="text" name="venue_city" class="get_venue_city" value="<?php echo $city ?>">
								
								<label for="venue_address">Venue State</label>
								<input type="text" name="venue_state" class="get_venue_state" value="<?php echo $state ?>">
								
								<label for="event_zipcode">Venue Zipcode</label>
								<input type="text" name="event_zipcode" class="get_event_zipcode" value="<?php echo $zipcode ?>" >
							</div>
							<div class="event_edit_right">
								<p class="image_holder"><img class="event_image" style="max-height:20%" src="<?php echo $event_image_loc; ?>" /></p>
									<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_event_picture" method="post" enctype="multipart/form-data">
										<input style="display:none" type="text" name="event_id" value="<?php echo $event_id ?>">
										<label for="file">Filename:</label>
										<input type="file" name="file" id="file_event" style="max-width:100%">
										<input type="submit" name="submit" value="Update">
									</form>
								
								<label for="event_type">Event Type</label>
								<select name="event_type" class="get_event_type">
								<?php
									foreach($event_types as $row) {
									echo $row['event_type'];
									?>
									<option value="<?php echo $row['e_type_id']; ?>" ><?php echo $row['event_type']; ?></option>
								<?php } ?>
								</select>
										
								<label for="event_scope">Event Scope</label>
								<select name="event_scope" id="get_event_scope">
									<option value="public">Public</option>
									<option value="private">Private</option>
								</select>
								<label for="event_desc">Event Description</label>
								<textarea name="event_desc" class="get_event_desc" cols=20 rows=3><?php echo $event_desc ?></textarea>
							</div>
							<div class="event_edit_bottom">
								<button type="button" class="delete_event_button">Delete Event</button>
								<button type="button" class="update_event_button">Save Changes</button>
								<button type="button" class="flip">Cancel</button>
							</div>
							</form>
							
						</div>
			<!-- END Event editing section -->
						
			<!-- Event information display section below -->
						<div class="back">
							<div class="event_tl">
								<p class="event_name"><?php echo $event_name; ?></p>
								<p class="event_date">on: <?php echo $event_date; ?></p>
								
								<p class="venue_location"><?php 
								echo $venue_name . "<br>";
								echo $venue_address . "<br>";
								echo $city . ", " . $state . " " . $zipcode; ?>
								</p>
								
								<p class="event_desc"><?php echo $event_desc; ?></p>
							</div>
							<div class="event_right">
								<p class="image_holder"><img class="event_image" src="<?php echo $event_image_loc; ?>" /></p>
								<p class="attendance_count">Attendance count:<br> <?php echo $count; ?></p>
								<p class="event_type">Event Type: <?php echo $event_type; ?></p>
								<p class="event_scope">Event Scope: <?php echo $event_scope; ?></p>
							</div>
							<div class="event_edit_bottom">
								<button type="button" class="flip">Edit Event</button>
							</div>
								
						</div>
			<!-- END Event information display section -->
					</div>
				<?php 
				}
			}
			else {
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
