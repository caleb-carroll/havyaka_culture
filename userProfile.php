<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Website Title</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
	<?php require_once 'includes/constants/sql_constants.php'; ?>
</head>
<body>

<div id="header" style='display:none'>

	<h1>The Title / Logo of the Webiste</h1>
	<h2>Some catchy sounding phrase</h2>
	
	<?php include('includes/navigation.inc.php'); ?>
	
</div>
	
<div class="colmask rightmenu">
	<div class="colleft">
		<div class="col1">
			<!-- Center column start -->
			<div id="editProfile">
				<p class="card_title">User Profile</p>
				<div id="profile_left">
					<div id="profile_info">
						First name: <input type="text" class="input_box" name="fname"><br><br>
						Last name: <input type="text" class="input_box" name="lname"><br><br>
						Phone: <input type="text" class="input_box" name="phone"><br><br>
						Email: <input type="text" class="input_box" name="email"><br><br>    
						<input type="checkbox" value="public_info">Allow others to see my contact info
					</div>
					<button type="button" id="update_info_button">Save Changes</button>
				</div>
				<div id="profile_right">
					<img id="profile_picture" src="pictures/calebc_profile.jpg" />
					<button type="button" id="change_profile_picture_button">Change Profile Picture</button>
				</div>
			</div>

			<div id="editChef">
				<p class="card_title">Chef Profile</p>
				<div class="left_chef">
					Phone: <input type="text" class="input_box" name="phone"><br><br>
					Email: <input type="text" class="input_box" name="email"><br><br>
					Contact Hours: <input type="text" class="input_box" name="lname"><br><br>
					
					<input type="checkbox" value="pickup">Offer pickup?
					<input type="checkbox" value="offline">Take offline orders?
					<input type="checkbox" value="delivery">Offer delivery?


					
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
				// To do: add in event picture
				// To do: get the user's ID to see if they can edit it?
				// Query to get event name, location, type, date, and details
				$q = "SELECT event_name, t3.venue_name, t3.venue_address, t4.city, t4.state, t4.zipcode, t2.event_type, event_date, event_desc 
				FROM " . EVENT . " as t1 
				LEFT JOIN " . EVENT_TYPE . " as t2 ON t1.e_type_id = t2.e_type_id
				LEFT JOIN " . VENUE . " as t3 ON t1.venue_id = t3.venue_id
				LEFT JOIN " . LOCATION . " as t4 ON t3.e_loc_id = t4.e_loc_id
				where event_status=1 and event_scope = 'public'";
				
				if($event_query = mysqli_query($link,$q)) {
					while ($row = mysqli_fetch_assoc($event_query)) {
						$results[] =$row;
					}
				}
					
				foreach ($results as $r) {
				?>
				<div class="event">
					<table>
						<tr><td width="25%">Event Name</td><td><?php echo $r['event_name']; ?></td></tr>
						<tr><td>Event Location</td><td><?php echo $r['venue_name'] . "<br>" . $r['venue_address'] . "<br>" . $r['city'] . ", " . $r['state'] . " " . $r['zipcode']?></td></tr>
						<tr><td>Event Type</td><td>Example Event Type</td></tr>
						<tr><td>Event Date</td><td><?php echo $r['event_date']; ?></td></tr>
						<tr><td>Event Details</td><td><?php echo $r['event_desc']; ?><td></tr>
					</table>
					
					<img class="event_picture" src="pictures/event.jpg" />
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
	// funciton to update an event 
	// update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_id, $e_recurring_id, $event_id);
?>
</html>
