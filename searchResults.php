<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<script type="text/javascript" src="includes\js\scripts.js"></script>
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
	})
</script>

<head>
	<title>Search Results</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>

	
	<?php
	
	require_once 'includes/constants/sql_constants.php';
	secure_page();
	
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
	<h1>The Title / Logo of the Webiste</h1>
	<h2>Some catchy sounding phrase</h2>
	
	<?php include('includes/navigation.inc.php'); ?>
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
