<?php

$names = array("Home","Local Events","Local Chefs","Local Contacts");
$short_names = array("home","events","chefs","contacts");
$links = array("index.php","localEvents.php","localChefs.php","localContacts.php");
?>
<ul>
	<?php
	
		for ($i = 0; $i < count($names); $i++){
		
			//here, we check if the page on which the user is at is the same as the page in $links[$i]
			if( basename($_SERVER['SCRIPT_NAME']) ==  $links[$i]) { 
				$class = "active"; 
			}
			else
				$class = "";
		
			?>
			<li><a href="<?php echo $links[$i];?>" class="<?php echo $class;?>"><?php echo $names[$i];?></a></li>
			
			<?php
		}
	?>
</ul>
<p id="layoutdims"></p>