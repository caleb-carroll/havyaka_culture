<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<link rel="stylesheet" type="text/css" href="styles/style.css"/>

<head>
    
       <script>
    //function to execute the accordion style
    $(function()  {
      $('#accordion').accordion();
    });

    </script>
</head>
<?php 
require_once 'includes/constants/sql_constants.php';

// select food_name from " . FOOD . " LIMIT 6
$q = "SELECT food_id, food_name FROM " . FOOD . " LIMIT 5";


if($food_query = mysqli_query($link, $q)) {
	while($row = mysqli_fetch_assoc($food_query)) {
		$foods[] = $row;
	}
}
?>


<!-- This column contains the food categories and search options which will be present on each page.  -->
<!-- Column 1 start -->
<div id = "left_column">
	<div class="category_heading">
		<center class='left_menu'>Food Categories</center>
		<div class="categories">
			<p>
			<ul>
				<?php
				foreach ($foods as $food) {
				?>
				<li>
					<a href="searchResults.php?food_id=<?php echo $food['food_id']; ?>"><?php echo $food['food_name']; ?></a>
				</li>
				<?php } ?>
			</ul>
			</p>
		</div>
	</div>
	
    <div class="information">
		<center>Quick Faq's</center>
			<div id = "accordion">
                            <div>
                                <h4>What is Card UI interface?</h4>                        
                                 <p class = "para">
                                    Card UI interface is future of the web!. Here, card flips over to display more information.
                                 </p>
                            </div>
                        <div>
                            <h4>How does card your work?</h4>
                          <p class = "para">
                              You see the information in the front of the card, to view more, click on the relavent button to see more information on the back of the card.      
                         </p>
                        </div>
                           
                        <div>
                            <h4>What kind of Events are here?</h4>
                          <p class = "para">
                              Events related to your community, culture or religion
                         </p>
                        </div>                          
                        <div>
                             <h4>Who are these chefs?</h4>
                             <p class = "para">
                                These chefs are just like you, they cook and deliver.
                             </p>
                       </div>
                            
                        <div>
                            <h4>Can I post events or become chef?</h4>
                             <p class = "para">
                               Absolutely!  use your My dashboard to create new events or become a chef
                             </p>
                       </div>
                            
                        <div>
                             <h4>What are the safety measures taken by the chef?</h4>
                             <p class = "para">
                                All chef's are required to maintain the hygiene while cooking. And, they need to make sure the foods are safe and healthy.
                             </p>
                       </div>
                             
             </div>
	</div>
</div>