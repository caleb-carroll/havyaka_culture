<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
<meta name="robots" content="index, follow" />
<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/chef_style.css"/>
<?php
require_once 'includes/constants/sql_constants.php';
require_once 'includes/constants/card_print.php';
secure_page();
$user_id = $_SESSION['user_id'];
?>
<input style="display:none" type="text" id="user_id" value="<?php echo $user_id ?>">
<script>
	function doesCSS(p){
		var s = ( document.body || document.documentElement).style;
		return !!$.grep(['','-moz-', '-webkit-'],function(v){
			return  typeof s[v+p] === 'string';
		}).length;
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

$(function() {
	$(".save_chef").click(function() {
		var chef_id = $(this).attr('rel');
		var user_id = $('#user_id').val();
		var datastring = "chef_id=" + chef_id + "&user_id=" + user_id;
		console.log(datastring);
		
		$.ajax({
			type: "POST",
			url: "<?php echo BASE; ?>/chef_interactions.php?cmd=save_chef", 
			data: datastring,
			success: function(response) {
				console.log(response);
				var results = JSON.parse(response);
				
				$('.success').fadeIn(2000).show().html('Chef details are saved in your profile!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); //If showing error, fade out
			}
		});
		
		return false;
	});
});
	
</script>

<head>
<title>Search Results</title>
	
<?php
	
if (isset($_GET['food_id'])){
	$food_id = $_GET['food_id'];
}
else{
	echo "<p class='error'>An error has occurred. No food search was specified.</p>";
	exit();
}

?>
</head>

<body>

<div id="header">
	<?php  
          include_once ('includes/header.inc.php');
        include('includes/navigation.inc.php'); ?> 
</div>

<div class="content leftmenu">
	<div class="colright">
		<div class="col1">
			<!-- Left Column start -->
			<?php include('includes/left_column.inc.php'); ?>
			<!-- Left Column end -->
		</div>
		<div class="col2">
			<div>
				<?php $food_info = get_food_info($food_id); ?>
				<h1>Chefs that serve <?php echo $food_info['food_name']; ?></h1>
				<img src="<?php echo BASE . $food_info['food_picture']; ?>" style="width:30%">
			</div>
			
			<?php
			// This section gets all chefs for the appropriate food types, then prints them into a card
			// functions below are defined in sql_constants
			$chefs_list = get_chefs_by_food($food_id);
			
			// prints a card for each chef associated with a food type
			foreach ($chefs_list as $chef) {
				
				// gets the chef info and loads it into an array
				$chef_info_array = get_chef_info($chef['chef_id']);
				
				// uses the chef info array to print cards
				print_chef_card($chef_info_array);
			}
			?>
		</div>
	</div>
</div>
