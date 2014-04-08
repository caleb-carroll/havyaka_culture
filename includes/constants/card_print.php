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

		<table>
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
						
						<tr>
							<td>
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
					<tr>
						<td>This chef has not specified any foods</td>
					</tr>
				<?php 
				}
				?>
		</table>

		<button class="save_chef" rel="<?php echo $chef_id; ?>" type="submit">Save</button>
		<button class="flip">Flip</button>
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
			<button class="">Save Chef</button>
			<button class="">Share Chef</button>
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
<?php 
}
?>