<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="home" content="index, follow" />
        <link rel="stylesheet" type="text/css" href="includes/styles/styles.css" media="screen" />
</head>
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
                          <p>random images goes here</p>
               
                        </div>
                        <div class ="card">

                            <h1>This is a card!</h1>
                                <p>In essence, a card is just a rectangular region which contains content.  This content could be text, images, lists, etc...
                                    The card UI methaphor dictates the interaction and layout of these regions.</p>
                        </div>
                        <div class="card card-back">                        
                                <p>Contents for card1 goes here</p>
                        </div>
                        <div class="card card-image">
                                 <p>Contents for card2 goes here</p>
                        </div> 
                        <div class="card card-image">
                                 <p>Contents for card3 goes here</p>
                        </div> 
                        <div class="card card-image">
                                 <p>Contents for card4 goes here</p>
                        </div> 
                        <div class="card card-image">
                                 <p>Contents for card5 goes here</p>
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
    