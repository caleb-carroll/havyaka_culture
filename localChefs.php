<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Community Resource</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="LocalEvents" content="index, follow" />
        <link rel="stylesheet" type="text/css" href="includes/styles/style.css" media="screen" />
        <script src="includes/js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="includes/js/jquery.flip.min.js"></script>
        <script type="text/javascript" src="includes/js/scripts.js"></script>
        <script type=text/javascript src = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
    <meta charset="utf-8">
    
  <?php
                       

        require_once 'includes/constants/sql_constants.php';
        secure_page();  
        return_meta("Local Chef!");
        $msg = NULL;
        $user_id =  $_SESSION['user_id'];
  ?>
<script>

//onload = setTimeout('initialize()',2000);
$(function()
{
    $(".card_back").hide();
     
         $(".save_chef").click(function() 
         {
              alert('!');
               var chef_id = $(this).attr('rel');
               alert(chef_id);
               var datastring = "chef_id="+chef_id;

               $.ajax(
                     {
                            type: "POST",
                            url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=save", 
                            data: datastring,
                            success: function()
                            {
                               $('.success').fadeIn(2000).show().html('Chef details are saved in your profile!').fadeOut(6000); //Show, then hide success msg
                                $('.error').fadeOut(2000).hide(); //If showing error, fade out
                            }
                     }
                );

              return false;
            
         });         
});

 </script>
            
</head>
    <body>
    <?php  
    
        if(isset($_POST) and isset($_GET))
        {
            if (!empty($_GET['cmd']))
            {
                   if($_GET['cmd'] == 'save') 
                       {
                            $chef_id = $_POST['chef_id'];
                            echo "hello";
                           if($stmt = mysqli_prepare($link, "SELECT * FROM ".USER_SAVED_INFO. " WHERE user_id = ".$_SESSION['user_id']." AND chef_id= " .$chef_id) or die(mysqli_error($link)))
                           {
                                //execute the query
                                 mysqli_stmt_execute($stmt);
                                 //store the result
                                 mysqli_stmt_store_result($stmt);

                                 if(mysqli_stmt_num_rows($stmt) == 0) {
                                      $q = mysqli_query($link, "INSERT INTO ".USER_SAVED_INFO. " (user_id,chef_id) VALUES(" .$_SESSION['user_id']. ",".$chef_id. ")") or die(mysqli_error($link));
                                 } 
                                 else 
                                 {
                                     $err[] = "You have saved this chef details!";

                                  }

                                     mysqli_stmt_close($stmt);

                                 } 
                                 exit();  
                        }
            }
            
        }
    ?>
        
        <div id ="header">
            <h1>Community Connect</h1>
        <?php      include('includes/navigation.inc.php');  
        
       // $firstname = $_SESSION['firstname'];
        // front of the card: call the retrieve_event function to retrive all event details based ont he user's location. defined in sql_constants.php
        $results = get_localchef_details($user_id);  
        
                
      ?>       
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
            <style>img {width: 160px;}</style> 
       
            <h2>Local chef's in your area!</h2>
                <form class= "chef" action="localChefs.php" method="POST" id = "local_chef" name="localchef">      
           <?php
                    
               if(($results))
                {
                   $i =0;
                     foreach ($results as $r) 
                      {
                        //generate individual ids                          
                        //get the picture of the event
                         //  
                            $zipcode = "zipcode_".$i;
                            $chef_id = "chefid_".$i;
                            $save_chef = "savechef_".$i;
                            $flip = "flip_".$i;
                            $success_id = "success_".$i;
                            $error_id = "error_".$i;
                            $map_canvas = "map_canvas_".$i;
                            
                           $chef_id = $r['chef_id'];
                           $food_id = $r['food_id'];
                           $food_picture = $r['food_picture'];
                         
                            $media_loc = htmlspecialchars($food_picture);
                            $media_loc = BASE.$media_loc;
                           list($width, $height, $type, $attr)= getimagesize($media_loc);  
                           
                           $profile_picture = $r['profile_picture'];  
                           $media_loc_profile = htmlspecialchars($profile_picture);
                            $media_loc_profile = BASE.$media_loc_profile;
                           list($width, $height, $type, $attr)= getimagesize($media_loc_profile);
                           
                           
             ?>
                 <div class ="card">
                    
                    <div class="card_front">
                             <input type="hidden" class='chef_id' id= "<?php echo $chef_id;?>" name ='chef_id' value=<?php echo $r['chef_id']; ?> ></input>
                             <table>
                                 <tr><td>Chef: </td><td> <?php echo $r['first_name']; ?>&nbsp;<?php echo $r['last_name']; ?><br><br></br><?php echo $r['about_chef']; ?></br></td>
                                     <td><img class="gridimg2" src="<?php echo $media_loc;?>" /></td>
                                 </tr>                                     
                                     <tr><td>Chef contact details: <br>Contact hour:</br></td><td><?php echo $r['email']; ?><br><?php echo $r['phone']; ?></br><?php echo $r['contact_time_preference']; ?></td></tr>
                                 <tr><td>Good at preparing:</td><td><?php echo $r['food_name']; ?></td><td><?php echo $r['food_description']; ?></td></tr>
                                
                                 <tr><td><button class = "save_chef" rel="<?php echo $r['chef_id']; ?>" id= "<?php echo $save_chef;?>" type="submit" name="save_chef">Save</button></td>
                                     <td><button name="flip" class="flip" id= "<?php echo $flip;?>" >Flip</button></td>
                                 </tr>
                             </table>
                                 
                       </div>                           
                                                               
                       <div class="card_back">
                                <table>
                                    
                                    <tr><td><?php echo $r['first_name']; ?> &nbsp;<?php echo $r['last_name']; ?> <br><br><?php echo $r['about_chef']; ?></br></td><td><img class="gridimg2" src="<?php echo $media_loc_profile;?>" /></td></tr>
                                    
                                    <tr>
                                        <td><th>Delivery available:</th></td> <td><?php echo $r['delivery_available']; ?></td> </tr>
                                    <tr> <td><th>Pickup available:</th></td><td><?php echo $r['pickup_available']; ?></td>  </tr>  
                                    <tr><td><th>Payment method:</th></td><td><?php echo $r['payments_accepted']; ?></td></tr>
                                    <tr><td><th>takes offline order?:</th> </td><td><?php echo $r['taking_offline_order']; ?></td></tr>                               
                                   </tr>
                                   <tr>
                                     <td><button name="flip" class="flip" id= "<?php echo $flip;?>" >Flip</button></td>
                                 </tr>
                                    
                                </table>
                                
                       </div>   
                               <p>
                                    <span class="success" id ='<?php echo $success_id;?>' style="display:none;"></span>
                                    <span class="error" id ='<?php echo $error_id; ?>' style="display:none;">Please enter some text</span>
                                 </p>
                      </div>
                     <?php
                     $i++;
                 } // end of foreach
              } else
              { ?>
                        <div class="card_front">
                            <h2>No chef's found in your area! </h2>
                            use the advanced search  <a href="advancedsearch.php">here</a>
                        </div>
        <?php }   ?>
                                      
        </form> 
               
      </div>   <!-- end of col2-->                   
    </div>  
                              
 </div>

</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>
    
