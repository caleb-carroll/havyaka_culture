<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="home" content="index, follow" />        
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="includes/styles/chef_style.css" media="screen" />
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js" type="text/javascript"><!--mce:0--></script>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css"></link>

</head>
    <script>
        
        $(document).ready(function() {
            var t = setInterval(function() {
                $("#carousal ul").animate({marginLeft:-480},1000,function() {
                    $(this).find("li:last").after($(this).find("li:first"));
                    $(this).css({marginLeft:0});
                })
            },5000);
        });
        
        
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
                
                $("#information_dialog").dialog({
                      autoOpen: true,
                      height: 500,
                      width: 650
                  });
	});
    
    </script>
     <?php
        require_once 'includes/constants/sql_constants.php';        
		require_once 'includes/constants/card_print.php';
        //require_once 'includes/constants/event_card_print.php';
        secure_page();  
        
        if (isset($_SESSION['homepage']))
            $_SESSION['homepage']++;
        else
           $_SESSION['homepage'] = 1;
        
          $user_id =  $_SESSION['user_id'];
           
        $hash_pass= crypt($passsalt,'connectcommunity1');
          echo $hash_pass;
        
        //check if the user is logged in for the first time, if so, display the information dialog box
        
          $q = mysqli_query($link,"SELECT num_logins from " .USERS. " WHERE user_id =".$user_id) or die(mysqli_error($link));
          
          list($num_login) = mysqli_fetch_row($q);
         
          if(($num_login == 1) &&  ($_SESSION['homepage'] == 1))
          {   
              ?>
            <div id ="information_dialog">
                <p>
                    Welcome to Community connect!
                </p>
            </div>
          <?php }
          
        return_meta("Home");
        $msg = NULL;
      
  ?>
    <body>
        <div id ="header">
            <h1>Community Connect</h1> 
      
        
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
                    <!-- Middle Column start -->
                        <div id="carousal">
                         <ul>
                            <?php                            
                            $results = fetch_food_picture();
                            foreach ($results as $r)
                            {
                                $food_image = $r['food_picture']; 
                                $food_image_loc = htmlspecialchars($food_image);
                                $food_image_loc = BASE.$food_image_loc;
                               list($width, $height, $type, $attr)= getimagesize($food_image_loc);
                            ?>
                             <li> <img src="<?php echo $food_image_loc?>"></img></li>
                             
                            <?php } ?>
                            </ul>               
                        </div>
			<!-- Middle Column start -->
			<style>img {width: 160px;}</style> 
                        <div id ="chef_holder">
				
				<?php
				// This section gets all chefs for the appropriate food types, then prints them into a card
				// functions below are defined in sql_constants
				$chefs_list = get_localchef_details($user_id,2);
				
				// prints a card for each chef associated with a food type
                               if(!empty($chef_list)) { 
                                   
                                   echo "<h2>Chefs in your area!</h2>";
				
                                    foreach ($chefs_list as $chef) {

                                            // gets the chef info and loads it into an array
                                            $chef_info_array = get_chef_info($chef['chef_id']);

                                            // uses the chef info array to print cards
                                            print_chef_card($chef_info_array);
                                    }
                               } 
				?>
                                 <div class="more_link">
                                <a href="localChefs.php">More Chefs>></a>
                            </div>
			</div>
                        <!-- end of col2-->
                           
                            <br><div id="event_holder" style="margin-top:30em; margin-left: 15px;">
                                <h2> Events in your area!</h2>

                               <form class= "event" action="localEvents.php" method="POST" id = "local_events" name="localevents">      
                                <?php  
                                        // front of the card: call the retrieve_event function to retrive all event details based ont he user's location. defined in sql_constants.php
                                 $results = retrieve_future_event($user_id,2);  
                                if(($results))
                                  {
                                     $i =0;
                                       foreach ($results as $r) 
                                        {
                                               print_event_card($r);
                                        }
                                  }
                                        ?>

                             </form>  
                         <span class="success" style="display:none;"></span>
                         <span class="error" style="display:none;">Please enter some text</span>

                                <div class="more_link">
                                    <a href="localEvents.php">More events>></a>
                                </div>                  
                   </div>

            </div>                          <!-- Middle Column end -->
       </div>
               <!-- for future reference Right column start 
               <div class="col3"> 
                   
                </div>
               -->
       </div>
<?php include('includes/footer.inc.php'); ?>

</body>
</html>
    