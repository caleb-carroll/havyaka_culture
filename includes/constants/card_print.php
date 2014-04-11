<?php
require_once 'includes/constants/sql_constants.php';

/* Function to print a chef card - accepts an array of chef information to print */
function print_chef_card($chef_info_array) {
	$chef_id = $chef_info_array['chef_id'];
	$first_name = $chef_info_array['first_name'];
	$last_name = $chef_info_array['last_name'];
	$about_chef = $chef_info_array['about_chef'];
	$email = $chef_info_array['email'];
	$phone = $chef_info_array['phone'];
	$contact_time_preference = $chef_info_array['contact_time_preference'];
	$delivery_available = $chef_info_array['delivery_available'];
	$pickup_available = $chef_info_array['pickup_available'];
	$payments_accepted = $chef_info_array['payments_accepted'];
	$taking_offline_order = $chef_info_array['taking_offline_order'];
	// $zipcode = $chef_info_array['zipcode'];

	//get the chef's profile picture
	$profile_picture = $chef_info_array['profile_picture']; 
	$media_loc_profile = htmlspecialchars($profile_picture);
	$media_loc_profile = BASE.$media_loc_profile;
	list($width, $height, $type, $attr)= getimagesize($media_loc_profile);

?>
<div class ="card flipper">
	<div class="front">
		<input type="hidden" value=<?php echo $chef_id; ?> ></input>
		<p style="display:block; position:absolute; top: 1em; left: 1em; font-size:1.5em; font-weight:bold">Foods by <?php echo $first_name . " " .  $last_name; ?></p>
		<p style="display:inline-block; position:absolute; top: 1em; right: 5px;">Phone: <?php echo $phone; ?></p>
		<p style="display:inline-block; position:absolute; top: 2em; right: 5px;">Email: <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></p>
		<p style="display:inline-block; position:absolute; top: 3em; right: 5px;">Contact hour: <?php echo $contact_time_preference; ?></p>
		<br><br><br>

		<table class="foods_table">
		<tbody class="foods_table">
				<?php 
				// print foods for the selected chef
				$foods_array = get_foods_by_chef($chef_id);
				 
				// uncomment below to debug
				/*	echo "<br> get_foods_by_chef array is: <br>";
				print_r($foods_array);
				echo "<br>"; */
				
				if ($foods_array){
					foreach ($foods_array as $row_food) {
						
						$food_id = $row_food['food_id'];
						
						$food_picture = $row_food['food_picture'];
						$media_loc = htmlspecialchars($food_picture);
						$media_loc = BASE.$media_loc;
						list($width, $height, $type, $attr)= getimagesize($media_loc);  
						?>
						
						<tr class="foods_table">
							<td class="foods_table">
							<?php echo $row_food['food_name'] . " - " . $row_food['food_price']; ?><br><br>
							<?php echo $row_food['food_description']; ?><br><br><br><br>
							</td>
							
							<td><img class="gridimg2" src="<?php echo $media_loc;?>" style="height:8em; float:right;" /></td>

						</tr>

				<?php
					}
				}
				else{ 
				?>
					<tr class="foods_table">
						<td class="foods_table">This chef has not specified any foods</td>
					</tr>
				<?php 
				}
				?>
		</tbody>
		</table>
		
		<button class="flip" style="position:absolute;bottom:0;right:0;">Flip Card</button>
	</div>

	<div class="back">
		<div class="tl">

			<p class="card_title"><?php echo $first_name . " " . $last_name; ?></p>

			<p class="contact_info">
			Phone: <?php echo $phone; ?><br>
			Email: <a href="<?php echo $email; ?>"><?php echo $email; ?></a><br>
			Contact hours: <?php echo $contact_time_preference; ?>
			</p>
		</div>

		<div class="tr">
			<img class="chef_profile" src="<?php echo $media_loc_profile;?>" />
		</div>

		<div class="bl">
			<h3>About chef:</h3>
			<p><?php echo $about_chef; ?></p>
			<h3>Favorite Dish:</h3>
			<img class="gridimg2" src="http://localhost/havyaka_culture/pictures/3.jpg" style="width:80%" />
		</div>

		<div class="br">
			<button class="save_chef" rel="<?php echo $chef_id; ?>" type="submit">Save Chef</button>
			<p style="font-weight:bold;">
			<?php 
				if ($delivery_available == "yes")
					echo "Delivery available"; 
				else echo "Delivery not available";
				echo "<br><br>";
				
				if ($pickup_available == "yes")
					echo "Pickup available"; 
				else echo "Pickup not available";
				echo "<br><br>";
				
				echo "Payments accepted:" . $payments_accepted;
				echo "<br><br>";
				
				if ($taking_offline_order == "yes")
					echo "Offline orders available"; 
				else echo "Offline orders not available";
			?>
			</p>
			
		</div>
	<button class="flip" style="position:absolute;bottom:0;right:0;">Flip Card</button>
	</div>

</div>
<?php //end of chef card function
}

/* Function to print event cards - accepts an array of event information */
function print_event_card ($r) {
	global  $link;

	//generate individual ids
	//get the picture of the event
	//  
	$event_id = $r['event_id'];
	$zipcode = "zipcode_".$event_id;
	$event_id_div = "eventid_".$event_id;
	$save_event = "saveevent_".$event_id;
	$flip = "flip_".$event_id;
	$show_more = "show_more_".$event_id;
	$attending_radio = "attending_radio_".$event_id;
	$map_canvas = "map_canvas_".$event_id;

	$q3 = "SELECT image_location FROM event_picture WHERE event_id = ".$event_id. " LIMIT 1";
	$query = mysqli_query($link,$q3) or (die(mysqli_error($link)));
	$row_image = mysqli_fetch_row($query);
	$image = $row_image[0];
	$media_loc = htmlspecialchars($image);
	$media_loc = BASE.$media_loc;
	list($width, $height, $type, $attr)= getimagesize($media_loc);

	//back of the card: I am attending option, list users attending add to calender, google map      
	$q = "select u.username from user as u inner join event_attendance as et on u.user_id = et.user_id and et.event_id = ".$event_id;

	$query1 = mysqli_query($link,$q) or (die(mysqli_error($link)));

	While($row = mysqli_fetch_assoc($query1)) {
		$user_list[]=$row;
	}

?>
	<div class ="card flipper">
		<div class="back">
			<table>
				<input type="hidden" class='event_id' id= "<?php echo $event_id_div;?>" rel="<?php echo $r['event_id']; ?>" name ='event_id' value=<?php echo $r['event_id']; ?> ></input>
				<input type="hidden" class="zipcode" id= "<?php echo $zipcode;?>" rel="<?php echo $r['zipcode']; ?>"  name="zipcode" value=<?php echo $r['zipcode']; ?>></input>

				<tr>
				<td>Event Name: </td>
				<td><?php echo $r['event_name']; ?> </td>
				</tr>
				
				<tr>
				<td>Event Details: </td>
				<td> <?php echo $r['event_desc']; ?></td>
				<td><img class="gridimg2" src="<?php echo $media_loc;?>" /></td>
				</tr>
				
				<tr>
				<td>Date:</td>
				<td> <?php echo $r['event_date']; ?> </td>
				</tr>

				<tr>
				<td>Event Address: </td>
				<td><?php echo $r['venue_name']; ?> <br> <?php echo $r['venue_address']; ?> <br> <?php echo $r['city']; ?> : <span><?php echo $r['state']; ?> - </span><?php echo $r['zipcode']; ?> </td>
				</tr>
				
				<tr>
				<td>Event contact details: </td>
				<td><?php echo $r['first_name']; ?><?php echo $r['last_name']; ?> <br><?php echo $r['email']; ?> <br> <?php echo $r['phone']; ?> </td>
				</tr>
				
				<tr>
				<td><input type="checkbox"  class="attending_radio" rel="<?php echo $r['event_id']; ?>" id= "<?php echo $attending_radio;?>" name="attending" value="attending" >I am attending!</input></td>
				<td><button class = "save_event" rel="<?php echo $r['event_id']; ?>" id= "<?php echo $save_event;?>" type="submit" name="save_event">Save</button></td>
				<td><label name="flip" rel="<?php echo $r['event_id']; ?>" rel1="<?php echo $r['zipcode']; ?>" class="flip" id= "<?php echo $flip;?>" >Flip</label></td>
				</tr>
			</table>
		</div>
		
		<div class="front">
			<h3>Friends attending <b><?php echo $r['event_name']; ?></b> function:</h3>
			<?php
			if(!empty($user_list)) {
					foreach($user_list as $user) {
						$username = $user['username'];
						?>
						
						<div><?php echo $username; ?><br></div>
						<?php
						
					}
			} 
			else { ?>
				<h3>No attendances</h3>  
	<?php	} ?>
				
			<div id="<?php echo $map_canvas;?>" rel="<?php echo $r['event_id']; ?>" class = "map_canvas" style="width:100%; height: 100%; margin-left:0px;" >
				<?php 
				//  include 'google_map_api.php';
				?>
			</div>
				
			<label name="flip" class="flip" rel="<?php echo $r['event_id']; ?>" id= "<?php echo $flip;?>" >Flip</label>
		</div>
	</div>
	<?php
		 
} // end of event card function
?>