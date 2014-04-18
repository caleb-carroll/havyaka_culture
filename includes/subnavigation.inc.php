<link rel="stylesheet" type="text/css" href="styles/style.css"/>
<?php

if(isset($_SESSION['user_id'])) // if the user is logged in and display related navigation tabs, else display the default tabs.
{
    $names = array("My profile","My saved Info","Manage my events");
    $links = array("userProfile.php","savedInfo.php","manageEvents.php");

?>
<div id="sub_navigation">
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
		}
                   ?>
</ul>
</div>
<?php } ?>