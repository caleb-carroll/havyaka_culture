<link rel="stylesheet" type="text/css" href="../includes/styles/style.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../includes/styles/card_style.css" media="screen" />
<script src="../includes/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
<script type="text/javascript" src="../includes/js/jquery.flip.min.js"></script>
<script type="text/javascript" src="../includes/js/scripts.js"></script>
<script type=text/javascript src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>

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
		});
	});
</script>

<?php 

require_once '../includes/constants/sql_constants.php';
secure_page();


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
	<div class="back">
		<input type="hidden" value=<?php echo $chef_id; ?> ></input>
		<table>
			<tr>
				<td> <?php echo $first_name; ?>&nbsp;<?php echo $last_name; ?><br></br><?php echo $about_chef; ?>&nbsp;</td>
				<td>Chef contact details: <br>Contact hour:</br></td>
				<td><?php echo $email; ?><br><?php echo $phone; ?></br><?php echo $contact_time_preference; ?></td>
			</tr>
			
			<tr>
				<th style="text-align:center;font-size: 100%;">Good at preparing:</th>
			</tr>
			
			<tr>
				<?php 
				// print foods for the selected chef
				$asdf = get_foods_by_chef($chef_id);
				
				// uncomment below to debug
				/*	echo "<br> get_foods_by_chef array is: <br>";
				print_r($asdf);
				echo "<br>"; */
				
				
				foreach ($asdf as $row_food) {
					
					$food_id = $row_food['food_id'];

					$food_picture = $row_food['food_picture'];
					$media_loc = htmlspecialchars($food_picture);
					$media_loc = BASE.$media_loc;
					list($width, $height, $type, $attr)= getimagesize($media_loc);  

					?>
					<tr>
						<td>
						Food Name:<br><br>
						Description:<br><br>
						Price:<br>
						</td>
						
						<td>
						<?php echo $row_food['food_name']; ?><br><br>
						<?php echo $row_food['food_description']; ?><br><br>
						<?php echo $row_food['food_price']; ?><br><br>
						<td><img class="gridimg2" src="<?php echo $media_loc;?>" style="width:10em" /></td>
						</td>
					</tr>
				<?php } ?>
			</tr>

			<tr>
				<td><button class = "save_chef" rel="<?php echo $chef_id; ?>" id= "<?php //echo $save_chef;?>" type="submit" name="save_chef">Save</button></td>
				<td><label class="flip">Flip</label></td>
			</tr>
		</table>

	</div>                           

	<div class="front">
		<table>
			<tr>
				<td><?php echo $first_name; ?> &nbsp;<?php echo $last_name; ?> <br><br><?php echo $about_chef; ?></br></td>
				<td><img class="gridimg2" src="<?php echo $media_loc_profile;?>" style="width:10em" /></td>
			</tr>
			
			<tr>
				<td><th>Delivery available:</th></td> 
				<td><?php echo $delivery_available; ?></td> 
			</tr>
			
			<tr>
				<td><th>Pickup available:</th></td>
				<td><?php echo $pickup_available; ?></td>
			</tr>
			
			<tr>
				<td><th>Payment method:</th></td>
				<td><?php echo $payments_accepted; ?></td>
			</tr>
			
			<tr>
				<td><th>takes offline order?:</th></td>
				<td><?php echo $taking_offline_order; ?></td>
			</tr>

			<tr>
				<td><label class="flip">Flip</label></td>
			</tr>
		</table>
	</div>
</div>
<?php 
}

/* Function to retrieve all chefs that cook a certain type of food */
function get_chefs_by_food($food_type_id) {
	global $link;
	
	// SELECT * from CHEF as t1 LEFT JOIN FOOD_CHEF_DETAILS as t2 ON t1.chef_id = t2.chef_id LEFT JOIN FOOD as t3 ON t2.food_id = t3.food_id WHERE t2.food_id = 1;
	$q = "SELECT t1.chef_id, t1.about_chef, t1.contact_time_preference, t1.delivery_available, t1.payments_accepted, t1.pickup_available, t1.taking_offline_order, t4.first_name, t4.last_name, t4.user_id, t4.email, t4.phone, t4.profile_picture 
	FROM chef as t1 
	LEFT JOIN " . FOOD_CHEF_DETAILS . " as t2 ON t1.chef_id = t2.chef_id 
	LEFT JOIN " . FOOD . " as t3 ON t2.food_id = t3.food_id 
	LEFT JOIN " . USERS . " as t4 on t4.user_id = t1.user_id 
	WHERE t3.food_id = $food_type_id;";
	
	// uncomment this to debug
	/* echo "<br> get_chefs_by_food query is: <br>";
	echo $q;
	echo "<br>"; */
	
	// execute the query
	if($food_query = mysqli_query($link,$q)) {
		while ($row = mysqli_fetch_assoc($food_query)) {
			$results[] =$row;
		}
	}
	
	return $results;
}

/* Function to retrieve info for a specific chef */
function get_chef_info($chef_id) {
	global $link;
	
	// SELECT * from CHEF as t1 LEFT JOIN FOOD_CHEF_DETAILS as t2 ON t1.chef_id = t2.chef_id LEFT JOIN FOOD as t3 ON t2.food_id = t3.food_id WHERE t2.food_id = 1;
	$q = "SELECT t1.chef_id, t1.about_chef, t1.contact_time_preference, t1.delivery_available, t1.payments_accepted, t1.pickup_available, t1.taking_offline_order, t4.first_name, t4.last_name, t4.user_id, t4.email, t4.phone, t4.profile_picture 
	FROM chef as t1 
	LEFT JOIN " . FOOD_CHEF_DETAILS . " as t2 ON t1.chef_id = t2.chef_id 
	LEFT JOIN " . FOOD . " as t3 ON t2.food_id = t3.food_id 
	LEFT JOIN " . USERS . " as t4 on t4.user_id = t1.user_id 
	WHERE t1.chef_id = $chef_id;";
	
	// uncomment this to debug
	/* echo "<br> get_chef_info query is: <br>";
	echo $q;
	echo "<br>"; */
	
	// execute the query
	if($query = mysqli_query($link,$q)) {
		$results = mysqli_fetch_assoc($query);
	}
	
	return $results;
}
?>

<?php


 ?>

