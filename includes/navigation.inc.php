<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
    <link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
</head>
    
<?php

$names = array("Home","Local Events","Local Chefs","My Dashboard","Logout");
$links = array("home.php","localEvents.php","localChefs.php","userProfile.php","logout.php");

?>
<div class="navigation">
	<ul>
		<?php
		for ($i = 0; $i < count($names); $i++){		   
					
			//here, we check if the page on which the user is at is the same as the page in $links[$i]
			if( basename($_SERVER['SCRIPT_NAME']) ==  $links[$i]) { 
				$class = "active"; 
			}
			else {
				$class = "";
			}
			?>
			<li><a href="<?php echo $links[$i];?>" class="<?php echo $class;?>"><?php echo $names[$i];?></a></li>
			<?php
		} ?>
                
	</ul>
</div>
<p id="layoutdims"></p>