<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="LocalChefs" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="includes/styles/chef_style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css" media="screen" />
	<script src="includes/js/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script type="text/javascript" src="includes/js/jquery.flip.min.js"></script>
	<script type="text/javascript" src="includes/js/scripts.js"></script>
	<script type=text/javascript src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
	<meta charset="utf-8">

<?php
	require_once 'includes/constants/sql_constants.php';
	require_once 'includes/constants/card_print.php';
	secure_page();  
	return_meta("Local Chef!");
	$msg = NULL;
	$user_id =  $_SESSION['user_id'];
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

$(function() {
	$(".save_chef").click(function() {
		alert('!');
		var chef_id = $(this).attr('rel');
		alert(chef_id);
		var datastring = "chef_id="+chef_id;

		$.ajax({
			type: "POST",
			url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=save", 
			data: datastring,
			success: function() {
			$('.success').fadeIn(2000).show().html('Chef details are saved in your profile!').fadeOut(6000); //Show, then hide success msg
			$('.error').fadeOut(2000).hide(); //If showing error, fade out
			}
		}
		);
		
		return false;
	});
});

</script>
</head>

<body>
<?php
// PHP code to save a chef
if(isset($_POST) and isset($_GET)) {
	if (!empty($_GET['cmd'])) {
		if($_GET['cmd'] == 'save') {
			$chef_id = $_POST['chef_id'];
			
			if($stmt = mysqli_prepare($link, "SELECT * FROM ".USER_SAVED_INFO. " WHERE user_id = ".$_SESSION['user_id']." AND chef_id= " .$chef_id) or die(mysqli_error($link))) {
				//execute the query
				mysqli_stmt_execute($stmt);
				//store the result
				mysqli_stmt_store_result($stmt);

				if(mysqli_stmt_num_rows($stmt) == 0) {
					$q = mysqli_query($link, "INSERT INTO ".USER_SAVED_INFO. " (user_id,chef_id) VALUES(" .$_SESSION['user_id']. ",".$chef_id. ")") or die(mysqli_error($link));
				} 
				else {
					$err[] = "You have saved this chef details!";
				}
				mysqli_stmt_close($stmt);
			}
			
			exit();
		}
	}
}
?>

<div id ="header">
    <img src="pictures/logo_594749_web1.jpg" alt="Community Connect"></img><h1>Community Connect</h1><br><h3>Connect and relish your tradition!</h3></br>
	<?php include('includes/navigation.inc.php');

	// $firstname = $_SESSION['firstname'];
	// front of the card: call the retrieve_event function to retrive all event details based ont he user's location. defined in sql_constants.php
	$results = get_localchef_details($user_id);

	?>
</div>
<div class="content leftmenu">
	<div class="colright">
		<div class="col1">
			<!-- Left Column start -->
			<?php include('includes/left_column.inc.php'); ?>
			<!-- Left Column end -->
		</div>
		
		<div class="col2">
			<!-- Middle Column start -->
			<style>img {width: 160px;}</style> 
			<div id ="chef_holder">
				<h2>Local chefs in your area!</h2>
				
				<?php
				// This section gets all chefs for the appropriate food types, then prints them into a card
				// functions below are defined in sql_constants
				$chefs_list = get_localchef_details($user_id);
				
				// prints a card for each chef associated with a food type
				foreach ($chefs_list as $chef) {
					
					// gets the chef info and loads it into an array
					$chef_info_array = get_chef_info($chef['chef_id']);
					
					// uses the chef info array to print cards
					print_chef_card($chef_info_array);
				}
				?>
			</div>
		</div>   <!-- end of col2-->
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>

