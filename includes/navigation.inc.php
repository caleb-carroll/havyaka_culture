<?php

//if(isset($_SESSION['user_id'])) // if the user is logged in and display related navigation tabs, else display the default tabs.
//{
    $names = array("Home","LocalEvents","LocalChefs","LocalContacts","MyDashboard","Logout");
    $links = array("home.php","localEvents.php","localChefs.php","localContacts.php","userProfile.php","logout.php");

//} else 
//{
  //$names = array("Register","Login");
  //$links = array("register.php","login.php");
  
//}

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