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
        return_meta("Local Events!");
        $msg = NULL;
        $user_id =  $_SESSION['user_id'];
  ?>
<script>

 //setTimeout('initialize()',2000);

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
                        var event_id = $(this).attr('rel');
                        alert(event_id);
                        var zipcode=$(this).attr('rel1');
                        alert(zipcode);
			console.log("clicked");
			$(this).parent().closest('.flipper').toggleClass('flipped');
                        initialize(event_id,zipcode);
		});
	});
$(function()
{
    //$(".show_more").click =setTimeout('initialize()');
        
        // $(".front").hide();
     $(".attending_radio").change(function() {
        
                
                 if(this.checked)
                 {                   
                            
                    var event_id = $(this).attr('rel');
                    alert(event_id);
                    var datastring = "attending=yes&event_id="+event_id;
                        
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
             
         $(".save_event").click(function() 
         {
             
               var event_id = $(this).attr('rel');
               alert(event_id);
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

function initialize(event_id,zipcode) {
     var lat = '';
            var lng = '';
            // var zip = $(".zipcode").attr('rel');
             //var event_id = $(".event_id").attr('rel');
             var map_canvas = "map_canvas_"+event_id;
             alert(map_canvas);
            alert ("zipcode inside google map" +zipcode);
            var country = "USA";
             var geocoder = new google.maps.Geocoder();
               geocoder.geocode( { 'address':zipcode+ ','+country}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                       
                       lat = results[0].geometry.location.lat();
                        alert (lat);
                       lng = results[0].geometry.location.lng();
                       alert(lng);
                       var mapOptions = {
                                    zoom: 9,
                                    center: new google.maps.LatLng(lat,lng)
                      };
                      
                     var map = new google.maps.Map(document.getElementById(map_canvas),
                     mapOptions);
                      
                       
                     map.setCenter(results[0].geometry.location);
                     var center = map.getCenter();
                     google.maps.event.trigger(map, 'resize');
                     map.setCenter(center);
                     var marker = new google.maps.Marker({
                        map: map,
                        position: results[0].geometry.location
                     });
                    
                    } else {
                      alert("Geocode was not successful for the following reason: " + status);
                    }
                });
                
}
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
       
            <h2>Upcoming events in your area!</h2>
                <form class= "event" action="localEvents.php" method="POST" id = "local_events" name="localevents">      
           <?php
                    
               if(($results))
                {
                   $i =0;
                     foreach ($results as $r) 
                      {
                        //generate individual ids                          
                        //get the picture of the event
                         //  
                         $event_id = $r['event_id'];
                            $zipcode = "zipcode_".$event_id;
                            $event_id_div = "eventid_".$event_id;
                            $save_event = "saveevent_".$event_id;
                            $flip = "flip_".$event_id;
                            $show_more = "show_more_".$event_id;
                            $attending_radio = "attending_radio_".$event_id;
                            $map_canvas = "map_canvas_".$event_id;
                            
                           
                           echo $event_id;
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
                 <div class ="card flipper">
                         <div class="back">
                                  
                                <table>
                                          <input type="hidden" class='event_id' id= "<?php echo $event_id_div;?>" rel="<?php echo $r['event_id']; ?>" name ='event_id' value=<?php echo $r['event_id']; ?> ></input>
                                          <input type="hidden" class="zipcode" id= "<?php echo $zipcode;?>" rel="<?php echo $r['zipcode']; ?>"  name="zipcode" value=<?php echo $r['zipcode']; ?>></input>


                                             <tr><td>Event Name: </td><td><?php echo $r['event_name']; ?> </td></tr>
                                             <tr><td>Event Details: </td><td> <?php echo $r['event_desc']; ?></td>
                                                 <td><img class="gridimg2" src="<?php echo $media_loc;?>" /></td>
                                             </tr>
                                             <tr> <td>Date:</td>
                                                <td> <?php echo $r['event_date']; ?> </td></tr>   

                                             <tr><td>Event Address: </td><td><?php echo $r['venue_name']; ?> <br> <?php echo $r['venue_address']; ?> <br> <?php echo $r['city']; ?> : <span><?php echo $r['state']; ?> - </span><?php echo $r['zipcode']; ?> </td></tr>
                                             <tr><td>Event contact details: </td><td><?php echo $r['first_name']; ?><?php echo $r['last_name']; ?> <br><?php echo $r['email']; ?> <br> <?php echo $r['phone']; ?> </td></tr>
                                             <tr>
                                                 <td>                                           
                                                     <input type="radio"  class="attending_radio" rel="<?php echo $r['event_id']; ?>" id= "<?php echo $attending_radio;?>" name="attending" value="attending" >I am attending!</input>                                               
                                                 </td>
                                                 <td>
                                                     <button class = "save_event" rel="<?php echo $r['event_id']; ?>" id= "<?php echo $save_event;?>" type="submit" name="save_event">Save</button>                                      
                                                 </td>
                                                 <td>
                                                          <td><label name="flip" rel="<?php echo $r['event_id']; ?>" rel1="<?php echo $r['zipcode']; ?>" class="flip" id= "<?php echo $flip;?>" >Flip</label></td>
                                                 </td>
                                                 

                                         </tr>  
                                </table>             
                            </div>                           
                                                               
                            <div class="front">
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
                                        <?php } ?>
                                        <div id="<?php echo $map_canvas;?>" rel="<?php echo $r['event_id']; ?>" class = "map_canvas" style="width:100%; height: 100%; margin-left:0px;" >
                                         <?php 
                                       //  include 'google_map_api.php';
                                         ?>
                                      </div>   
                                        <label name="flip" class="flip" rel="<?php echo $r['event_id']; ?>" id= "<?php echo $flip;?>" >Flip</label>
                                       
                            </div>                                                   
                            
                      </div>
                     <?php
                     $i++;
                 } // end of foreach
              } else
              { ?>
                        <div class="back">
                            <h2>No upcoming events found! </h2>
                            Add an event <a href="userProfile.php">here</a>
                        </div>
        <?php }   ?>
                                      
        </form> 
                
                     <span class="success" style="display:none;"></span>
                     <span class="error" style="display:none;">Please enter some text</span>
                
        </div>   <!-- end of col2-->                   
    </div>  
                              
 </div>

</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>
    
