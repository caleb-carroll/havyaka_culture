<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();
?>

<script>
function doesCSS(p){
	var s = ( document.body || document.documentElement).style;
	return !!$.grep(['','-moz-', '-webkit-'],function(v){
		return typeof s[v+p] === 'string'
	}).length
}

$('html')
	.toggleClass('transform',doesCSS('transform'))
	.toggleClass('no-transform',!doesCSS('transform'))

$(function(){
	$('.flip').click(function(){
		// console.log("clicked");
		$(this).parent().closest('.flipper').toggleClass('flipped');
	});
});

$(function(){
	//Ajax request to save the profile information
	$('#save_profile_button').click(function() {
		var datastring = $('#update_profile_form').serialize();
		
		if ($('#get_public').prop('checked')){
			datastring += "&public_info=yes";
		}
		else{
			datastring += "&public_info=no";
		}
		console.log("datastring for user profile is " + datastring);
		
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/includes/ajax_functions/profile_interactions.php?cmd=update_user",
			data: datastring,
			success: function(response) {
				var results = JSON.parse(response);
				console.log(results);
				var status = results['success'];
				var message = results['message'];
				
				if(status === 'true') {
					$('.success').fadeIn(2000).show().html(message).fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); //If showing error, fade out
				}
				else {
					$('.error').fadeIn(2000).show().html(message).fadeOut(6000); //Show, then hide success msg
					$('.success').fadeOut(2000).hide();
				}
			}
		});
	});
	
	//To save the chef updates
	$('#save_chef_updates').click(function(){
		var datastring = $('#chef_profile_form').serialize();
		
		// set the values of the checkboxes for the datastring
		if ($('#pickup').prop('checked')){
			datastring += "&pickup=yes";
		}
		else{
			datastring += "&pickup=no";
		}
		
		if ($('#offline').prop('checked')){
			datastring += "&offline=yes";
		}
		else{
			datastring += "&offline=no";
		}
		
		if ($('#delivery').prop('checked')){
			datastring += "&delivery=yes";
		}
		else{
			datastring += "&delivery=no";
		}
		
		console.log("datastring for chef profile is " + datastring);

		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/includes/ajax_functions/profile_interactions.php?cmd=update_chef",
			data: datastring,
			success: function(response) {
				var results = JSON.parse(response);
				console.log(results);
				var status = results['success'];
				var message = results['message'];

				if(status === 'true') {
					$('.success').fadeIn(2000).show().html(message).fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); //If showing error, fade out
				}
				else {
					$('.error').fadeIn(2000).show().html(message).fadeOut(6000); //Show, then hide success msg
					$('.success').fadeOut(2000).hide();
				}
			}
		});
	});
	
	//Ajax request to add a new food as requested by the user
	$( "#add_new_food_form" ).dialog({
		autoOpen: false,
		height: 500,
		width: 650,
		modal: true,
		buttons: {
			"Add food": function() {
				console.log("in add new food dialog");
				var formData = new FormData($(this)[0]);
				alert(formData);
				add_new_food(formData);
				$(this).dialog( "close" );
			},
			Cancel: function() {
				$(this).dialog("close");
			}
		},
		close: function() {
			$(this).dialog("close");
		}
	});

	$("#request_new_food_link").click(function() {
		$("#add_new_food_form").dialog("open");
	});

	//ajax request to process the selected food by the user
	$("#add_selected_food").click(function(){
		var e = document.getElementById("selected_food");
		var food_id = e.options[e.selectedIndex].value;
		var chef_id = $(this).attr('rel1');
		var datastring = "food_id=" +food_id+ "&chef_id="+chef_id;
		
		if (food_id == "default"){
			// show error
			console.log("food was left at default");
			return false;
		}
		
		console.log(datastring);
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/includes/ajax_functions/profile_interactions.php?cmd=add_selected_food",
			data: datastring,
			success:function (response) {
				console.log(response);
				/* var results = JSON.parse(response);
				console.log(results); */
				
				$('.success').fadeIn(2000).show().html('Added Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide();
				// $("#chef_profile").load('chef_profile.php');
			}
		});
		return false;
	});

	function add_new_food(formData) {
		//  $("form#add_new_food_form").submit(function(){
		//   var formData = new FormData($(this)[0]);
		console.log("in function add_new_food with formData of" + formData);
		$.ajax({
			url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=add_new_food",
			type: 'POST',
			data: formData,
			async: false,
			success: function () {
				$('.success').fadeIn(2000).show().html('Added Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide();
				//$("#chef_profile").load('chef_profile.php');
				refresh_content();
			},
			cache: false,
			contentType: false,
			processData: false
		});
		return false;
		// });
	}
	
	$(".update_food").click(function() {
		var food_id = $(this).attr('rel');
		var chef_id = $(this).attr('rel1');
		var food_description_id = "food_description_"+food_id;
		var food_description=document.getElementById(food_description_id).value;

		if(food_description == '') {
			$('.error').fadeIn(400).show().html('Please enter the food description.');
		}
		else {
			var datastring = "food_description=" +food_description+ "&food_id=" +food_id+ "&chef_id="+chef_id;
			console.log("in update food button, datastring is " + datastring);

			$.ajax({
				type: "POST",
				url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=update_food",
				data: datastring,
				success:function() {
					$('.success').fadeIn(2000).show().html('updated Successfully!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide();
					// $("#chef_profile").load('chef_profile.php');
					refresh_content();
				}
			});
		}
		return false;
	});
	
	$(".delete_food").click(function() {
		var self = this;
		var food_id = $(this).attr('rel');
		var chef_id = $(this).attr('rel1');
		var datastring = "food_id=" +food_id+ "&chef_id=" +chef_id;
		
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/includes/ajax_functions/profile_interactions.php?cmd=delete_food",
			data: datastring,
			success:function(response) {
				var results = JSON.parse(response);
				console.log(results);
				
				$(self).closest('tr').hide('slow');
				$('.success').fadeIn(2000).show().html('deleted Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide();
				// $("#chef_profile").load('chef_profile.php');
			}
		});
		return false;
	});
});

</script>

<?php
$user_id = $_SESSION['user_id'];
$msg = NULL;
$err=NULL;

$user_info = get_user_info($user_id);
$profile_pic = PICTURE_LOCATION . $user_info[0]['profile_picture'];
$profile_pic_loc = htmlspecialchars($profile_pic);


//Get the chef details of the logged in user if exists
$chef_info_ret = get_chef_details_logged_in_user($user_id);
$chef_info = array_filter($chef_info_ret);

if(!empty($chef_info)) {
	$chef_id =$chef_info[0]['chef_id'];
	$about_chef = $chef_info[0]['about_chef'];
	$contact_time_preference = $chef_info[0]['contact_time_preference'];
	$pickup_available = $chef_info[0]['pickup_available'];
	
	if($chef_id !=NULL){
		$food_chef = get_foods_of_chef($chef_id);
	}
}
?>

<head>
	<title>My Profile</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>
</head>

<body>
<?php
include('includes/header.inc.php');
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
			
			<!-- Middle column start -->
			<span class="success" style="display:none;"></span>
                       <span class="error" style="display:none;">Please enter some text</span>
			
			
			<!-- USER PROFILE START -->
			<div class="card flipper" id="user_profile_div">
				
				<div class="back">
					<?php
					/* if($chef_info == NULL) {
					?>
						<h4>Become and chef and show off your cooking skill!</h4>
						<a href="chefProfile.php" name="create_chef_profile">Create a Chef Profile</a>&nbsp;&nbsp;
					<?php } 
					else {?>
						<a href="chefProfile.php" name="edit_chef_profile">Edit your Chef Profile</a>&nbsp;&nbsp;
					<?php } */?>
					
					
					<div class="update_profile_left">
					<h2>Hello <?php echo $user_info[0]['first_name'];?>,</h2>
					<h3>Edit your profile here:</h3></br></p>
						<form id="update_profile_form" action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_user" method="post">
							<input style="display:none" type="text" name="user_id" value="<?php echo $user_id ?>">

							<label for="first_name">First Name:</label>
							<input type="text" id="get_first_name" name="first_name" value="<?php echo $user_info[0]['first_name'];?>">
							
							<label for="last_name">Last name:</label>
							<input type="text" id="get_last_name" name="last_name" value="<?php echo $user_info[0]['last_name'];?>">
							
							<label for="phone">Phone:</label>
							<input type="text" id="get_phone" name="phone" value="<?php echo $user_info[0]['phone'];?>">
							
							<label for="email">Email:</label>
							<input type="text" id="get_email" name="email" value="<?php echo $user_info[0]['email'];?>">
							
							<br>
							<input type="checkbox" id="get_public" name="public_info" value="public_info">Allow others to see my contact info
						</form>
					</div>
					
					<div class="update_profile_right">
						<p class="image_holder"><img class="card_image" src="<?php echo $profile_pic_loc;?>" /></p>
						<p>Upload a Picture</p>
						<form action="<?php echo BASE;?>/includes/ajax_functions/profile_interactions.php?cmd=add_picture" method="post" enctype="multipart/form-data">
							<input type="file" name="file" id="file">
							<input type="submit" name="submit" value="Submit">
						</form>
						<button type="button" id="save_profile_button">Save Changes</button>
					</div>
				</div>
			</div>
			<!-- USER PROFILE END -->
			
			<!-- CHEF PROFILE START -->
			<div class="card flipper" id="chef_profile">
				<div class="back">
					<div class="update_chef_top">
						<p class="card_name">Chef Profile</p>
						
						<form id="chef_profile_form" method="post">
						<input type='hidden' name='chef_id' value='<?php echo $chef_info[0]['chef_id'];?>' ></input>
						<input style="display:none" type="text" name="user_id" value="<?php echo $user_id ?>">
							<label for="about_chef">About yourself as a chef:</label> 
							<textarea style="width:400px; height: 100px;"  name="about_chef"><?php echo $chef_info[0]['about_chef'];?></textarea>
					</div>
						
					<div class="update_chef_bl">
						<label for="contact_hours">Contact Hours:</label> 
						<select name="contact_time_preference" id="contact_time_preference">
							<option value="morning" <?php if($chef_info[0]['contact_time_preference'] == "morning") echo "selected";?>>Morning</option>
							<option value="noon" <?php if($chef_info[0]['contact_time_preference'] == "noon") echo "selected";?>>Noon</option>
							<option value="evening" <?php if($chef_info[0]['contact_time_preference'] == "evening") echo "selected";?>>Evening</option>
							<option value="anytime" <?php if($chef_info[0]['contact_time_preference'] == "evening") echo "selected";?>>Any time</option>
						</select>
						
						<label for="contact_hours">Payments Accepted:</label> 
						<select name="payments_accepted" id="payments_accepted">
							<option value="cash" <?php if($chef_info[0]['payments_accepted'] == "cash") echo "selected";?>>Cash</option>
							<option value="check" <?php if($chef_info[0]['payments_accepted'] == "Check") echo "selected";?>>Check</option>
							<option value="cash or check" <?php if($chef_info[0]['payments_accepted'] == "Cash or Check") echo "selected";?>>Cash or Check</option>
							<option value="paypal" <?php if($chef_info[0]['payments_accepted'] == "Paypal") echo "selected";?>>Paypal</option>
							<option value="other" <?php if($chef_info[0]['payments_accepted'] == "Other") echo "selected";?>>Other</option>
						</select>
					</div>
					
					<div class="update_chef_br">
						<!-- marks these checkboxes as checked or unchecked based on what we find in the DB -->
						<input style="width:20px; height: 20px;" type="checkbox" id="pickup" <?php if($chef_info[0]['pickup_available'] == "Yes") echo "checked"; else echo "unchecked";?>>Offer pickup?</input><br>
						
						<input style="width:20px; height: 20px;" type="checkbox" id="offline" <?php if($chef_info[0]['taking_offline_order'] == "Yes") echo "checked"; else echo "unchecked";?>>Take offline orders?</input><br>
						
						<input style="width:20px; height: 20px;" type="checkbox" id="delivery" <?php if($chef_info[0]['delivery_available'] == "Yes") echo "checked"; else echo "unchecked";?>>Offer delivery?</input><br>
					</div>
						
						<button type="button" id="save_chef_updates" name="save_chef_updates" style="position: absolute; bottom: 1em; right: 10em;">Save</button>
						<button type="button" class="flip" style="position:absolute;bottom:1em;right:1em;">Food bucket</button>
					</form>
				</div>
				
				<?php
				// TO DO - update this to ONLY get foods for the current chef
				//Get the chef details of the logged in user if exists
				$chef_info = get_chef_details_logged_in_user($user_id);
				$chef_info_filter = array_filter($chef_info);
				if(!empty($chef_info_filter)) {
					$chef_id =$chef_info[0]['chef_id'];

					//Get the foods that the chef is preparing.
					if($chef_id !=NULL){
						$food_chef = get_foods_of_chef($chef_id);
					}
				}

				$food_names = get_all_food_names();
				?>
				
				<div class="front">
					<div id="request_new_food_div" style="display:none;">
						<h3>Add a food to your profile. (This should be one, you started taking orders!)</h3>
						<form action="userProfile.php" id ="add_new_food_form" method="post" enctype="multipart/form-data">
							<fieldset>
								<input type="hidden" id="chef_id" name ="chef_id" value="<?php echo $chef_id;?>">
									Food Name: <input class="input_box" name="food_name" id="new_food_name" placeholder="Enter the food Name">
									Food description:<textarea name="food_description" id="new_food_description"></textarea>

									<h3> Add a colorful picture to your food!</h3>
									<input type="file" name="file" id="food_pic"><br>
							</fieldset>
						 </form>
					</div>
					<p>Food Bucket</p>
					<!-- TODO: Remove this form name so that AJAX can do it? -->
						<form action="userProfile.php" method="post">
							<div id="food_from_db">
								<select id ="selected_food" class="dropdown">
									<option selected value="default">Please select a food type</option>
									<?php
									foreach ($food_names as $current_food)
									{
									?>
										<option value="<?php echo $current_food['food_id'];?>" ><?php echo $current_food['food_name'];?></option>

									<?php } ?>
								</select>
								<input type="button" name="add_selected_food" rel="<?php echo $current_food['food_id'];?>" rel1="<?php echo $chef_info[0]['chef_id'];?>" id="add_selected_food" value="Add this food to your bucket">
								<p id="request_new_food_link">Request a new food</p>
							</div>
						</form>
						<?php 
						if(isset($food_chef)) { ?>
							<table id="foods_table" style="display: block;   width: 100%;   border-collapse: collapse;   border: solid 1px #D4D4D3;   max-height: 16em; overflow-y:auto;">
							<tbody>
								<?php 
								// print foods for the selected chef
								$foods_array = get_foods_by_chef($chef_id);
								
								if ($foods_array){
									foreach ($foods_array as $row_food) {
										
										$food_id = $row_food['food_id'];
										
										$food_picture = $row_food['food_picture'];
										$media_loc = htmlspecialchars($food_picture);
										$media_loc = PICTURE_LOCATION . $media_loc;
										?>
										
										<tr class="foods_table">
											<td class="foods_table">
												<p class="food_name"><?php echo $row_food['food_name']; ?></p>
												<p class="food_description"><?php echo $row_food['food_description']; ?></p>
												<button class="update_food" rel="<?php echo $row_food['food_id'];?>" rel1=<?php echo $chef_info[0]['chef_id'];?> id="update_food_"<?php echo $row_food['food_id'];?> >Update</button>
												<button type="button" name="delete_food" class ="delete_food" rel="<?php echo $row_food['food_id'];?>" rel1="<?php echo $chef_info[0]['chef_id'];?>">Delete</button>
											</td>
											
											<td>
												<img class="gridimg2" src="<?php echo $media_loc;?>" style="height:8em; float:right;" />
											</td>
											
										</tr>

								<?php
									}
								}
								else{ 
								?>
									<tr class="foods_table">
										<td class="foods_table">You have not specified any foods.</td>
									</tr>
								<?php 
								}
								?>
						</tbody>
						</table>

						<?php
						} ?>
					<button class="flip" style="position:absolute;bottom:1em;right:1em;">Back to Chef Profile</button>
				</div>
			</div>
	<!-- CHEF PROFILE END -->
		</div>
	<!-- Center column end -->
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>