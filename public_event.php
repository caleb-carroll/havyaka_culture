<?php
require_once 'includes/constants/sql_constants.php';

//Pre-assign our variables to avoid undefined indexes
$username = NULL;
$pass2 = NULL;
$msg = NULL;
$err = array();
global $link;
$results = array();

//query the public events and display them randomly in the public_event section at the registration page
$q = "SELECT t1.event_id, t1.event_date, t1.event_desc, t1.event_id, t1.event_name, t5.first_name, AES_DECRYPT(t5.email, '$salt') as email, t5.phone, t5.last_name, t3.venue_address, t3.venue_name, t4.city, t4.zipcode as zipcode, t4.state
	FROM event AS t1
	LEFT JOIN " . EVENT_TYPE . " AS t2 ON t1.e_type_id = t2.e_type_id
	LEFT JOIN " . VENUE . " AS t3 ON t1.venue_id = t3.venue_id
	LEFT JOIN " . LOCATION . " AS t4 ON t3.e_loc_id = t4.e_loc_id
	LEFT JOIN " . USERS . " AS t5 ON t1.user_id = t5.user_id
	WHERE t1.event_status=1 AND t1.event_scope = 'public' AND t1.event_date > CURDATE() ORDER BY RAND() LIMIT 1;";
	
	//echo "query is: " . $q;
if($event_query = mysqli_query($link,$q)) {
	$results = mysqli_fetch_assoc($event_query);
	mysqli_free_result($event_query);
}
	//print_r($results);
	$event_id = $results['event_id'];
	$zipcode = $results['zipcode'];
	$event_name = $results['event_name'];
	$event_desc = $results['event_desc'];
	$event_date = $results['event_date'];
	$venue_name = $results['venue_name'];
	$venue_address = $results['venue_address'];
	$city = $results['city'];
	$state = $results['state'];
	$first_name = $results['first_name'];
	$last_name = $results['last_name'];
	$email = $results['email'];
	$phone = $results['phone'];
	
	$q3 = "SELECT image_location FROM " . EVENT_PICTURE . " WHERE event_id = ".$event_id. " LIMIT 1";
	$query = mysqli_query($link,$q3) or (die(mysqli_error($link)));
	$row_image = mysqli_fetch_row($query);
	$image = $row_image[0];
	
	if (empty($image)){
		$media_loc = "/pictures/default_event.jpg";
	}
	else {
		$media_loc = htmlspecialchars($image);
	}
	$media_loc = BASE.$media_loc;
?>

<div class="event_tl">
	<p class="event_name"><?php echo $event_name; ?></p>
	<p class="event_date">on: <?php echo $event_date; ?></p>
	
	<p class="venue_location"><?php 
	echo $venue_name . "<br>";
	echo $venue_address . "<br>";
	echo $city . ", " . $state . " " . $zipcode; ?>
	</p>
	<p class="event_description"><?php echo $event_desc; ?></p>
</div>

<div class="event_bl">
	<p class="contact_info">For more information, contact:<br>
	<?php echo $first_name . " " . $last_name . "<br>" . $email . "<br>" . $phone; ?></p>
</div>

<div class="event_right">
	<p class="image_holder"><img class="event_image" src="<?php echo $media_loc;?>" /></p>
	<br>
</div>
