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
  <?php
                       

        require_once 'includes/constants/sql_constants.php';
        secure_page();  
        return_meta("Local Events!");
        $msg = NULL;
        $user_id =  $_SESSION['user_id'];
  ?>
<script>

$(function()
{
    $(".card_back").hide();
     $("#attending_radio1").change(function() {
        
                 alert('!!');
                 if(this.checked)
                 {                   
                            
                    var event_id = $('#event_id').val();
                    var datastring = "attending=yes&event_id="+event_id;
                        alert ('!');
                       $.ajax(
                               {
                                       type: "POST",
                                       url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=attending", 
                                       data: datastring,
                                       success: function()
                                       {
                                            $('.success').fadeIn(2000).show().html('Your attendence is counted!').fadeOut(6000); //Show, then hide success msg
					   $('.error').fadeOut(2000).hide(); //If showing error, fade out   
                                       }
                               }
                       );

                       return false;
                 } else 
                 {
                  //we may want to add another option called 'may be attending' in that case, we need to write the code here
                     alert('do nothing');
                 }
             });
             
         $("#save_event").click(function() 
         {
              
               var event_id = $('#event_id').val();
               var datastring = "event_id="+event_id;

               $.ajax(
                     {
                            type: "POST",
                            url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=save", 
                            data: datastring,
                            success: function()
                            {
                               $('.success').fadeIn(2000).show().html('Event details are saved in your profile!').fadeOut(6000); //Show, then hide success msg
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
                    if($_GET['cmd']== 'attending')
                     {
                            $event_id = $_POST['event_id'];
                            //check if the logged in user is already attending the event, if not insert into the table
                            if($stmt = mysqli_prepare($link, "SELECT * FROM ".ATTENDENCE. " WHERE user_id = ".$_SESSION['user_id']." AND event_id= " .$event_id) or die(mysqli_error($link)))
                            {
                                //execute the query
                                 mysqli_stmt_execute($stmt);
                                 //store the result
                                 mysqli_stmt_store_result($stmt);

                                 if(mysqli_stmt_num_rows($stmt) == 0) {

                                        $q = mysqli_query($link, "INSERT INTO ".ATTENDENCE. " (event_id,user_id) VALUES(".$event_id. "," .$_SESSION['user_id']. ")") or die(mysqli_error($link));

                                 } 
                                 else 
                                 {
                                     $err[] = "You are attending!";
                                     
                                 }

                                 mysqli_stmt_close($stmt);
                            }
                            exit();
                       }
                       elseif($_GET['cmd'] == 'save') 
                       {
                            $event_id = $_POST['event_id'];
                            echo "hello";
                           if($stmt = mysqli_prepare($link, "SELECT * FROM ".USER_SAVED_INFO. " WHERE user_id = ".$_SESSION['user_id']." AND event_id= " .$event_id) or die(mysqli_error($link)))
                           {
                                //execute the query
                                 mysqli_stmt_execute($stmt);
                                 //store the result
                                 mysqli_stmt_store_result($stmt);

                                 if(mysqli_stmt_num_rows($stmt) == 0) {
                                      $q = mysqli_query($link, "INSERT INTO ".USER_SAVED_INFO. " (user_id,event_id) VALUES(" .$_SESSION['user_id']. ",".$event_id. ")") or die(mysqli_error($link));
                                 } 
                                 else 
                                 {
                                     $err[] = "You have saved this event!";

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
        $results = retrieve_future_event($user_id);  
        
                
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
       
            
                <form class= "event" action="localEvents.php" method="POST" id = "local_events" name="localevents">      
           <?php
                    
               if(($results))
                {
                   
                     foreach ($results as $r) 
                      {
                         //get the picture of the event
                            $event_id = $r['event_id'];
                            $q3 = "SELECT image_location FROM event_picture WHERE event_id = ".$event_id. " LIMIT 1";
                            $query = mysqli_query($link,$q3) or (die(mysqli_error($link)));
                             $row_image = mysqli_fetch_row($query);
                             $image = $row_image[0];
                            $media_loc = htmlspecialchars($image);
                            $media_loc = BASE.$media_loc;
                           list($width, $height, $type, $attr)= getimagesize($media_loc);
                           
                             //back of the card: I am attending option, list users attending add to calender, google map      
                            $q = "select u.username from user as u inner join event_attendance as et on u.user_id = et.user_id and et.event_id = ".$event_id;
                          
                            $query1 = mysqli_query($link,$q) or (die(mysqli_error($link)));
                            
                           While($row = mysqli_fetch_assoc($query1))
                           {
                               $user_list[]=$row;
                           }
                            

             ?>
                 <div class ="card">
                         <div class="card_front">
                                  
                                <table>
                                          <input type="hidden" id='event_id' name ='event_id' value=<?php echo $r['event_id']; ?> ></input>
                                          <input type="hidden" id='zipcode' name="zipcode" value=<?php echo $r['zipcode']; ?>></input>


                                             <tr><td>Event Name: </td><td><?php echo $r['event_name']; ?> </td></tr>
                                             <tr><td>Event Details: </td><td> <?php echo $r['event_desc']; ?></td>
                                                 <td><img class=\"gridimg2\" src="<?php echo $media_loc;?>" /></td>
                                             </tr>
                                             <tr> <td>Date:</td>
                                                <td> <?php echo $r['event_date']; ?> </td></tr>   

                                             <tr><td>Event Address: </td><td><?php echo $r['venue_name']; ?> <br> <?php echo $r['venue_address']; ?> <br> <?php echo $r['city']; ?> : <span><?php echo $r['state']; ?> - </span><?php echo $r['zipcode']; ?> </td></tr>
                                             <tr><td>Event contact details: </td><td><?php echo $r['venue_email']; ?> <br> <?php echo $r['venue_phone']; ?> </td></tr>
                                             <tr>
                                                 <td>                                           
                                                     <input type="radio"  id="attending_radio1" name="attending" value="attending" >I am attending!</input>                                               
                                                 </td>
                                                 <td>
                                                     <button id ="save_event" type="submit" name="save_event">Save</button>                                      
                                                 </td>
                                                 <td>
                                                        <button name="flip" class="flip" >Flip</button> 
                                                 </td>

                                         </tr>                     
                                         <p>
                                             <span class="success" style="display:none;"></span>
                                             <span class="error" style="display:none;">Please enter some text</span>
                                         </p>
                                </table>             
                            </div>                           
                                                               
                            <div class="card_back">
                                <h3>Friends attending <b><?php echo $r['event_name']; ?></b> function:</h3>
                                        <?php
                                        if(!empty($user_list))
                                        {
                                              foreach($user_list as $user)
                                              {
                                                  $username = $user['username'];                                        
                                        ?>

                                        <div><?php echo $username; ?><br></div>
                                        <?php
                                              }
                                        } else { ?>
                                        <h3>No attendances</h3>                                      
                                        <?php }
                                        
                                       include 'google_map_api.php';
                                       ?>   
                                        <button name="flip" class="flip">Flip</button> 
                            </div>
                                                    
                            
                      </div>
                     <?php
                 }
              } else
              { ?>
                        <div class="card_front">
                            <h2>No upcoming events found! </h2>
                            Add an event <a href="userProfile.php">here</a>
                        </div>
        <?php }   ?>
                                      
        </form>                
      </div>                      
    </div>  
                              
 </div>

</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>
    
