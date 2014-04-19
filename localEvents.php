<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<head>
	<script src="includes/js/jquery-1.10.2.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
	<script type=text/javascript src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="includes/styles/event_style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css" media="screen" />

<?php
	require_once 'includes/constants/sql_constants.php';
	require_once 'includes/constants/card_print.php';
	include 'google_map_api.php';
	secure_page();
	return_meta("Local Events!");
	$msg = NULL;
	$user_id = $_SESSION['user_id'];
?>
<script>

 //setTimeout('initialize()',2000);

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
		// console.log("clicked");
		$(this).parent().closest('.flipper').toggleClass('flipped');
		
		console.log($(this).parentsUntil('.flipper').find('.map_canvas').css('visibility'));
/*  		if ($(this).parent().find('.map_canvas').css('visibility') == 'hidden')
			$(this).parent().find('.map_canvas').css('visibility', 'visible');
		else
			$(this).parent().find('.map_canvas').css('visibility', 'hidden'); */
	});
});
		
$(function(){
	$('.card').show('slide', {direction: "up"}, 700);
	//$(".show_more").click =setTimeout('initialize()');

	// $(".front").hide();
	$(".attending_radio").change(function() {
		
		if(this.checked) {
			var event_id = $(this).attr('rel');
			// alert(event_id);
			var datastring = "attending=yes&event_id="+event_id;
				
			$.ajax({
				type: "POST",
				url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=attending", 
				data: datastring,
				success: function() {
					$('.success').fadeIn(2000).show().html('Your attendence is counted!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); //If showing error, fade out   
				}
			});
			
			return false;
		} 
		else {
			//we may want to add another option called 'may be attending' in that case, we need to write the code here
			// alert('do nothing');
		}
	});
	 
	$(".save_event").click(function() {
		var event_id = $(this).attr('rel');
		// alert(event_id);
		var datastring = "event_id="+event_id;
		
		$.ajax({
			type: "POST",
			url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=save", 
			data: datastring,
			success: function(){
				$('.success').fadeIn(2000).show().html('Event details are saved in your profile!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); //If showing error, fade out
			}
		});
		
		return false;
	});
	
	$(".attending_radio").change(function() {
			var event_id = $(this).attr('rel');
		if(this.checked) {
			var datastring = "attending=true&event_id="+event_id;
			console.log(datastring);
				
			$.ajax({
				type: "POST",
				url: "<?php echo BASE; ?>/event_interactions.php?cmd=attending",
				data: datastring,
				success: function(response) {
					console.log(response);
					$('.success').fadeIn(2000).show().html('Your attendence is counted!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); //If showing error, fade out   
				}
			});
			
			return false;
		} 
		else {
			//we may want to add another option called 'may be attending' in that case, we need to write the code here
			var datastring = "attending=false&event_id="+event_id;
			console.log(datastring);
				
			$.ajax({
				type: "POST",
				url: "<?php echo BASE; ?>/event_interactions.php?cmd=attending",
				data: datastring,
				success: function(response) {
					console.log(response);
					$('.success').fadeIn(2000).show().html('Your attendence is counted!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); //If showing error, fade out   
				}
			});
			
			return false;
		}
	});
});

function initialize() {
	var lat = '';
	var lng = '';
	var zip = $(".zipcode").attr('rel');
	var event_id = $(".event_id").attr('rel');
	var map_canvas = "map_canvas_"+event_id;
	
	console.log("event_id is " + event_id);
	console.log("zipcode is " + zip);
	console.log("map canvas is " + map_canvas);
	console.log("zipcode inside google map" + zip);
	var country = "USA";
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({ 'address':zip+ ','+country}, function(results, status) {
		if (status === google.maps.GeocoderStatus.OK) {
			lat = results[0].geometry.location.lat();
			// alert (lat);
			lng = results[0].geometry.location.lng();
			// alert(lng);
			var mapOptions = {
				zoom: 9,
				center: new google.maps.LatLng(lat,lng)
			};
		
			var map = new google.maps.Map(document.getElementById(map_canvas),mapOptions);
			
			map.setCenter(results[0].geometry.location);
			var center = map.getCenter();
			google.maps.event.trigger(map, 'resize');
			map.setCenter(center);
			var marker = new google.maps.Marker({
				map: map,
				position: results[0].geometry.location
			});
		
		} 
		else {
			// alert("Geocode was not successful for the following reason: " + status);
		}
	});
}
</script>

</head>
    <body>
    <?php  
    
        if(isset($_POST) and isset($_GET))
        {
            if (!empty($_GET['cmd']))
            {
                       if($_GET['cmd'] == 'save') 
                       {
                            $event_id = $_POST['event_id'];
                           if($stmt = mysqli_prepare($link, "SELECT * FROM ".USER_SAVED_INFO. " WHERE user_id = ".$_SESSION['user_id']." AND event_id= " .$event_id) or die(mysqli_error($link)))
                           {
                                //execute the query
                                 mysqli_stmt_execute($stmt);
                                 //store the result
                                 mysqli_stmt_store_result($stmt);

                                 if(mysqli_stmt_num_rows($stmt) == 0) {
                                      $q = mysqli_query($link, "INSERT INTO ".USER_SAVED_INFO. " (user_id,event_id) VALUES(" .$_SESSION['user_id']. ",".$event_id. ")") or die(mysqli_error($link));
                                 } 
                                 else 
                                 {
                                     $err[] = "You have saved this event!";

                                  }

                                     mysqli_stmt_close($stmt);

                            } 
                                 exit();  
                      }
            }
            
        }
    ?>
  <?php
          include_once ('includes/header.inc.php');
        include('includes/navigation.inc.php'); ?>
  <div class="content leftmenu">      
         <span class="success" style="display:none;"></span>
         <span class="error" style="display:none;">Please enter some text</span>
     <div class="colright">
         <div class="col1">
                <!-- Left Column start -->
                <?php include('includes/left_column.inc.php'); ?>			

                <!-- Left Column end -->
        </div>
        <div class="col2">
            <!-- Middle Column start -->
            <style>img {width: 160px;}</style> 

            <h2>Upcoming events in your area!</h2>
          <?php  
          // front of the card: call the retrieve_event function to retrive all event details based ont he user's location. defined in sql_constants.php
             $results = retrieve_future_event($user_id);  
              if(($results))
                {
                   $i =0;
                     foreach ($results as $r) 
                      {
                             print_event_card($r);
                      }
                } else
              { ?>
                        <div class="back">
                            <h2>No upcoming events found! </h2>
                            Add an event <a href="userProfile.php">here</a>
                        </div>
        <?php }  
        $i++;
?>
                    
                
        </div>   <!-- end of col2-->                   
    </div>  
                              
 </div>

</div>

	<div id="footer">
	<?php include('includes/footer.inc.php'); ?>
	</div>
</body>
</html>
    
