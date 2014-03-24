<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<script type="text/javascript" src="includes\js\scripts.js"></script>
<script>
	function doesCSS(p){
		var s = ( document.body || document.documentElement).style;
		return !!$.grep(['','-moz-', '-webkit-'],function(v){
			return  typeof s[v+p] === 'string'
		}).length
	}

	$('html')
		.toggleClass('transform',doesCSS('transform'))
		.toggleClass('no-transform',!doesCSS('transform'))

	$(function(){
		$('.flip').click(function(){
			console.log("clicked");
			$(this).parent().closest('.flipper').toggleClass('flipped');
		})
	})
</script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();

if($_POST and $_GET){
	if ($_GET['cmd'] == 'update_event'){
		
		$event_name = $_POST['event_name'];
		$event_date = $_POST['event_date'];
		$event_desc = $_POST['event_desc'];
		$event_scope = 'public';
		$e_type_id = 1; /* $_POST['event_type']; */
		$venue_id = 1; /* $_POST['event_venue']; */
		$e_recurring_id = 1;
		$event_id = $_POST['event_id'];
		
		// function to update an event 
		if (update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_id, $e_recurring_id, $event_id)) {
			// add something here to display success/failure?
			// echo "Update successful";
		}
		else {
			// echo "Update failed";
		}
	}

	if ($_GET['cmd'] == 'update_user'){
		
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$user_id = $_SESSION['user_id']; // need to update this to pull from session data
		
		// testing of user info update function
		update_user_info($user_id, $first_name, $last_name, $email, $phone);
		
		// function to update an event 
		if (update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_id, $e_recurring_id, $event_id)) {
			// add something here to display success/failure?
			// echo "Update successful";
		}
		else {
			// echo "Update failed";
		}
	}
}
?>


<head>
	<title>Website Title</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
</head>
<body>

<div id="header" style='display:none'>

	<h1>The Title / Logo of the Webiste</h1>
	<h2>Some catchy sounding phrase</h2>
	
	<?php include('includes/navigation.inc.php'); ?>
	
</div>
	
<div class="colmask rightmenu">
	<!-- to do: get user ID from session data and add it to function call below -->
	<?php 
	$user_info = get_user_info(1);
	$chef_info = get_chef_info(1);
	?>
	<div class="colleft">
		<div class="col1"> <!-- To do: move to column 2 -->
			<!-- Center column start -->
			<div id="editProfile" class="card">
				<h1>User Profile</h1>
				<div id="profile_left">
					<div id="profile_info">
						<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_user" method="post">
						First name: <input type="text" class="input_box" name="first_name" value="<?php echo $user_info[0]['first_name'];?>"><br><br>
						Last name: <input type="text" class="input_box" name="last_name" value="<?php echo $user_info[0]['last_name'];?>"><br><br>
						Phone: <input type="text" class="input_box" name="phone" value="<?php echo $user_info[0]['phone'];?>"><br><br>
						Email: <input type="text" class="input_box" name="email" value="<?php echo $user_info[0]['email'];?>"><br><br>    
						<input type="checkbox" value="public_info">Allow others to see my contact info
					</div>
					<button type="submit">Save Changes</button>
					</form>
				</div>
				<div id="profile_right">
					<img id="profile_picture" src="pictures/calebc_profile.jpg" />
					<button type="button" id="change_profile_picture_button">Change Profile Picture</button>
				</div>
			</div>

			<div id="editChef" class="card">
				<h1>Chef Profile</h1>
				<div class="left_chef">
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
				<?php
				
				// get_events function defined in sql_constants.php
				$results = get_events(1);
				
				// add each event returned to an event card
				foreach ($results as $r) {
				?>
				<div class="card flipper">
					<div class="front">
						<button class="flip">Cancel</button>
						<form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_event" method="post">
						<input style="display:none" type="text" name="event_id" value="<?php echo $r['event_id']?>">
						<table>
							<tr><td width="25%">Event Name</td><td><input type="text" name="event_name" value="<?php echo $r['event_name']; ?>"></td></tr>
							<tr><td>Event Location</td><td><input type="text" name="event_venue" value="<?php echo $r['venue_name']?>"></td></tr>
							<tr><td>Event Type</td><td><input type="text" name="event_type" value="<?php echo $r['event_type']?>"></td></tr>
							<tr><td>Event Date</td><td><input type="text" name="event_date" value="<?php echo $r['event_date']?>"></td></tr>
							<tr><td>Event Details</td><td><textarea name="event_desc"><?php echo $r['event_desc']?></textarea><td></tr>
						</table>
						<!-- To do: get event picture from query results -->
						<img class="event_picture" src="pictures/event.jpg" />
						<button type="submit">Save Changes</button>
						</form>
					</div>
					<div class="back">
						<button class="flip">Edit Event</button>
						<table>
							<tr><td width="25%">Event Name</td><td><?php echo $r['event_name']; ?></td></tr>
							<tr><td>Event Location</td><td><?php echo $r['venue_name'] . "<br>" . $r['venue_address'] . "<br>" . $r['city'] . ", " . $r['state'] . " " . $r['zipcode']?></td></tr>
							<tr><td>Event Type</td><td>Example Event Type</td></tr>
							<tr><td>Event Date</td><td><?php echo $r['event_date']; ?></td></tr>
							<tr><td>Event Details</td><td><?php echo $r['event_desc']; ?><td></tr>
						</table>
						<img class="event_picture" src="pictures/event.jpg" />
					</div>
				</div>
				<?php } ?>
			</div>
			<!-- Center column end -->
			
		<div class="col2">
			<!-- Column 2 start -->
			<?php include('includes/right_column.inc.php'); ?>
			<!-- Column 2 end -->
		</div>
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>
<?php
	//function to delete an event 
	// delete_event($event_id);
?>

<?php 
	// function to add an event 
	// add_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $user_id, $venue_id, $community_id, $e_recurring_id)
	// testing adding event
	// add_event("new event", "2014-04-04", "This is a newly added event description", "public", 1, 1, 1, 1, 1)
?>
</html>
