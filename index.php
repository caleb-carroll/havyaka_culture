<script src="includes/js/jquery-1.10.2.js"></script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/event_style.css" media="screen" />

<script>
$(document).ready(function(){
	$('#username').focus();
});

function refresh_content() {
	$(".public_event_section").fadeIn(450).show().load('public_event.php');
}

setInterval( refresh_content, 6000 );

function get_city_state(zipcode) {
	var zip = zipcode;
	var country = 'United States';
	var lat;
	var lng;
	var geocoder = new google.maps.Geocoder();
	
	geocoder.geocode({ 'address': zipcode + ',' + country }, function (results, status) {
		if (status === google.maps.GeocoderStatus.OK) {
			geocoder.geocode({'latLng': results[0].geometry.location}, function(results, status) {
				if (status === google.maps.GeocoderStatus.OK) {
					if (results[1]) {
						var loc = getCityState(results,zipcode);
					}
				}
			});
		}
	}); 
}

function getCityState(results,zipcode) {
	var a = results[0].address_components;
	var city, state;
	
	for(i = 0; i <  a.length; ++i) 	{
		var t = a[i].types;
		if(compIsType(t, 'administrative_area_level_1'))
			state = a[i].long_name; //store the state
		else if(compIsType(t, 'locality'))
			city = a[i].long_name; //store the city
	}
	
	var datastring = "zipcode= "+zipcode+ "&city= " +city+"&state= "+state;
	
	$.ajax({
		type: "POST",
		url: "updateaddress.php?cmd=updatecitystate", 
		data: datastring,
		
	});
	return false;
}

function compIsType(t, s) { 
	for(z = 0; z < t.length; ++z) 
		if(t[z] == s)
			return true;
   return false;
} 
</script>

<?php
require_once 'includes/constants/sql_constants.php';

$meta_title = "Get Started";

$firstname = NULL;
$username = NULL;
$password = NULL;
$city= NULL;
$zipcode = NULL;
$email = NULL;
$pass2 = NULL;
$msg = NULL;
$err = array();
$e_loc_id = NULL;

//Check if the user signed up, add user to the user table.
if(isset($_POST['register'])) {
	$firstname = filter($_POST['firstname']);
	$username = filter($_POST['username']);
	$password = filter($_POST['pass1']);
	$confirm_pass = filter($_POST['pass2']);
	$email = filter($_POST['email']);
	$zipcode = intval(filter($_POST['zipcode']));
	$date = date('Y-m-d');
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$activation_code = rand(1000,9999);
	$community_type = 'havyaka'; //$_POST['community_type'];
	
	$err = array();
	//defined in config.inc.php
	$err = add_user($firstname,$username,$password,$confirm_pass,$email,$zipcode,$date,$user_ip,$activation_code,$community_type );
	
	if ( count($err) == 0) {
		$msg = "Registration successful!";
                
		$meta_title = "Registration successful!";
		//if the registration is successful then get the city and state name using zipcode and update the table
                
		?>
		<script>
			get_city_state('<?php echo $zipcode;?>');
		</script>
		<?php
	}

}

return_meta($meta_title);
?>
<link rel="stylesheet" type="text/css" href="includes/styles/index_style.css" media="screen" />
</head>

<body>
	<div id="header">
		<h1>Community Connect</h1>
		<div class="login">
			<?php include_once 'login.php'; ?>
		</div>
	</div>
	
	<!-- Beginning of error display section -->
	<div class="login_event_section">
		<?php
		//Show message if isset
		if(isset($msg)) {
			echo '<div class="success">'.$msg.'</div>';
		}
		//Show error message if isset
		
		if(!empty($err)) {
			echo '<div class="err">';
			
			foreach($err as $e) {
				echo $e.'<br />';
			}
			
			echo '</div>';
		}
		?>
	</div>
	<!-- End of error display section -->
	
	<div class="page_content_holder">
		<!--- Begining of registration section -->
		<div class="registration_section" style="border:solid;">
			<h1>Register Now!</h1>
			
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="register_form">
				<label for="firstname">First</label>
				<input class="account" type="text" name="firstname" required="required">
				
				<label for="lastname">Last</label>
				<input class="account" type="text" name="lastname" required="required">

				<label> Zip Code:</label> 
				<input class="account" id ="zipcode" type="number" name="zipcode">

				<label> Email: </label>
				<input class="account" type="email" name="email" required="required">

				<label> Username: </label> 
				<input class="account" id="username" type="text" name="username" required="required">

				<label> Password: </label> 
				<input class="account" type="password" name="pass1" required="required">

				<label>Confirm Password: </label> 
				<input class="account" type="password" name="pass2" required="required">
				
				<br>
				<button type="submit" name="register">Register</button>
			</form>
		</div> 
		<!--- End of registration section -->
	
		<!-- Beginning of public event display section -->
		<h1>Public Events!</h1>
		<div class="public_event_section">
			<?php include_once 'public_event.php'; ?>
		</div> 
		<!-- End of public event display section -->
		
		<!-- Beginning of information section -->
		<div class="information_section" >
			<p class="section_header">Information Section</p>
			<img src="<?php echo BASE . "/pictures/default_event.jpg" ?>" class="event_image" style="max-width:15em"/>
		</div>
		<!-- End of information section -->
		
		
		
		
	</div>
</body>
	<?php include('includes/footer.inc.php'); ?>