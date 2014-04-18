<head>

<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/left_coulmn_style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/jquery-ui-1.10.4.custom.css"></link> 
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    
<script>
//function to execute the accordion style
$(function() {
	$( "#accordion1" ).accordion({
	heightStyle: "content"
	});
});

$(function() {
	$( "#accordion-resizer" ).resizable({
		minHeight:200,
		minWidth: 200,
		resize: function() {
			$( "#accordion1" ).accordion({collapsible: true});
		}
	});
});
</script>
</head>
<?php 
require_once 'includes/constants/sql_constants.php';

// select food_name from " . FOOD . " LIMIT 6
$q = "SELECT food_id, food_name FROM " . FOOD . " LIMIT 8";


if($food_query = mysqli_query($link, $q)) {
	while($row = mysqli_fetch_assoc($food_query)) {
		$foods[] = $row;
	}
}
?>

    <body>
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
	
        <center><h3>Quick Faq's</h3></center>
        <div id="accordion-resizer" class="ui-widget-content">
               <div id ="accordion1">                            
                    <h4>What are all these cards?</h4>      
                <div>                  
                     <p class = "para1">
                        Here, the each card contains the information about events or chef etc. When you click on the  button, the card flips over to display more information.
                     </p>
                </div>
             <h4 class="left_faq_header">How does card your work?</h4>       
            <div>            
              <p class = "para1">
                  You see the information in the front of the card, to view more, click on the relavent button to see more information on the back of the card.      
             </p>
            </div>
            <h4 class="left_faq_header">What kind of Events are here?</h4>
            <div>           
              <p class = "para1">
                  Events related to your community, culture or religion
             </p>
            </div>   
            <h4 class="left_faq_header">Who are these chefs?</h4>
            <div>             
                 <p class = "para1">
                    These chefs are just like you, they cook and deliver.
                 </p>
           </div>
             <h4 class="left_faq_header">Can I post events or become chef?</h4>
            <div>           
                 <p class = "para1">
                   Absolutely!  use your My dashboard to create new events or become a chef
                 </p>
           </div>
             <h4 class="left_faq_header">What are the safety measures taken by the chef?</h4>
            <div>             
                 <p class = "para1">
                    All chef's are required to maintain the hygiene while cooking. And, they need to make sure the foods are safe and healthy.
                 </p>
           </div>
         </div>
     </div>
    </div>
    </body>
</html>