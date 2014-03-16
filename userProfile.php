<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Website Title</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
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
				<div class="event">
					<table>
						<tr><td width="25%">Event Name</td><td>Example Event Name</td></tr>
						<tr><td>Event Location</td><td>Example Event Location</td></tr>
						<tr><td>Event Type</td><td>Example Event Type</td></tr>
						<tr><td>Event Date</td><td>Example Event Date</td></tr>
						<tr><td>Event Details</td><td>Example Event Details which are quite a bit longer than the other fields so we should make sure there is enough room.</td></tr>
					</table>
					
					<img class="event_picture" src="pictures/event.jpg" />
				</div>
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
</html>
