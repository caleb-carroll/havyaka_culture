<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="home" content="index, follow" />
        <link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.0/jquery.min.js" type="text/javascript"><!--mce:0--></script>

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
	});
    
    </script>
     <?php
                       

        require_once 'includes/constants/sql_constants.php';
        secure_page();  
        return_meta("Local Events!");
        $msg = NULL;
        $user_id =  $_SESSION['user_id'];
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
                                <li> <img src="<?php echo BASE; ?>/pictures/1.jpg">1</img></li>
                                 <li> <img src="<?php echo BASE; ?>/pictures/2.jpg">2</img></li>
                                  <li> <img src="<?php echo BASE; ?>/pictures/3.jpg">3</img></li>
                                   <li> <img src="<?php echo BASE; ?>/pictures/4.jpg">4</img></li>
                            </ul>
                          <p>random images goes here</p>
               
                        </div>
                    <div class="card_header"><h3>Upcoming Events!</h3></div>
                        <div class ="card flipper">
                            
                            <div class="back">
                                1
                                <label name="flip" class="flip" >Flip</label>
                            </div>
                            <div class="front">
                                1
                                 <label name="flip" class="flip" >Flip</label>
                            </div> 
                                                      
                        </div>
                             <div class="more_link">
                                <a href="localEvents.php">More events>></a>
                            </div>
                    <div class="card_header"><h3>Top rated Chefs!</h3></div>
                    <div class ="card flipper">
                            
                            <div class="back">
                                1
                                 <label name="flip" class="flip" >Flip</label>
                            </div>
                            <div class="front">
                                1
                                 <label name="flip" class="flip" >Flip</label>
                            </div> 
                                                     
                        </div>
                            <div class="more_link">
                                <a href="localChefs.php">More Chefs>></a>
                            </div>
                        

                                <!-- Middle Column end -->
                 </div>
               <!-- for future reference Right column start 
               <div class="col3"> 
                   
                </div>
               -->
       </div>

</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>
    