<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
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

$(function(){
	$('.flip').click(function(){
		  console.log("clicked");
		  $(this).parent().closest('.flipper').toggleClass('flipped');
	});
});
</script>

<?php
function chef_profile_data($user_id) {
	$user_info = get_user_info($user_id);
	$profile_pic = $user_info[0]['profile_picture'];
	$profile_pic_loc = htmlspecialchars($profile_pic);
	$profile_pic_loc =$profile_pic_loc;
	list($width, $height, $type, $attr)= getimagesize($profile_pic_loc);

	//Get the chef details of the logged in user if exists
	$chef_info = get_chef_details_logged_in_user($user_id);
	$chef_info_filter = array_filter($chef_info);

	if(!empty($chef_info_filter)) {
		$chef_id =$chef_info[0]['chef_id'];
		// echo "chef id is: " .$chef_id;
		$about_chef = $chef_info[0]['about_chef'];
		$contact_time_preference = $chef_info[0]['contact_time_preference'];
		$pickup_available = $chef_info[0]['pickup_available'];

		//Get the foods that the chef is preparing.
		if($chef_id !=NULL){
			$food_chef = get_foods_of_chef($chef_id);
		}
	}

	//get the event types
	$event_types = get_event_types();
	$food_names = get_all_food_names();

?>
<div class="front">
	<button class="flip">Back to Chef Profile</button> &nbsp;<br></br>
	<?php
	if(!empty($food_chef)) { ?>
		<h2>Edit your food bucket</h2>
		<?php
	}
	else {?>
		<h2>Create a new food bucket</h2>
	<?php
	} ?>
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
		<br>
		<form action="userProfile.php" method="post">
			<div id="food_from_db">
				<select id ="selected_food" class="dropdown">
					<option selected value="default">Please Select a Food Type</option>
					<?php
					foreach ($food_names as $current_food)
					{
					?>
						<option value="<?php echo $current_food['food_id'];?>" ><?php echo $current_food['food_name'];?></option>

					<?php } ?>
				</select>
				<input type="button" name="add_selected_food" rel="<?php echo $current_food['food_id'];?>" rel1="<?php echo $chef_info[0]['chef_id'];?>" id="add_selected_food" value="Add this food to your bucket"> &nbsp;&nbsp;&nbsp; <h4>Not found anything you prepare?</h4>
				<input type="button" class="request_new_food_button" id="request_new_food_link" value="Request one Now!">
			</div>
		</form>
		<?php 
		if(isset($food_chef)) { ?>
			<table><h4>Food details, that you are ready to prepare</h4>
				<tr><th> Food Name </th>
				<th> Food Description </th>
				<th> Food picture </th>
				<th> Your Action</th></tr>
				<?php
				foreach($food_chef as $r) {
					$food_id= $r['food_id'];
					$food_picture = $r['food_picture'];
					$food_picture_loc = htmlspecialchars($food_picture);
					$food_picture_loc = BASE.$food_picture_loc;
					list($width, $height, $type, $attr)= getimagesize($food_picture_loc);
					?>
					<tr>
					<form action="userProfile.php" method="post">
						<td id="food_name_<?php echo $food_id;?>"> <?php echo $r['food_name'];?></td>
						<td> <textarea name="food_description" id="food_description_<?php echo $r['food_id'];?>" ><?php echo $r['food_description'];?></textarea></td>
						
						 <td><img style="width: 80px; height: 70px;" src="<?php echo $food_picture_loc;?>"</td>

						 <td><button name="delete_food" class ="delete_food" rel="<?php echo $r['food_id'];?>" rel1="<?php echo $chef_info[0]['chef_id'];?>" id="delete_food_"<?php echo $r['food_id'];?> >Delete this food</button><br>

						 <button class="update_food" rel="<?php echo $r['food_id'];?>" rel1=<?php echo $chef_info[0]['chef_id'];?> id="update_food_"<?php echo $r['food_id'];?> >Update this food</button>
					</form>
					
					<form action="userProfile.php?cmd=add_food_picture" method="post" enctype="multipart/form-data">
						<input  type="hidden" name="food_id" value="<?php echo $r['food_id'];?>">
						<input type="file" name="file" id="food_pic"><br>
						<input type="submit" name="submit" value="Update">
					</form>
					</tr>
				<?php
				} ?>

			</table>
		<?php
		} ?>
</div>
<?php 
} ?>