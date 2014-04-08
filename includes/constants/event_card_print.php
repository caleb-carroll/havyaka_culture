<?php
require_once 'includes/constants/sql_constants.php';


function print_event_card ($r)
{
    global  $link;
    
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
                     
} // end of function
             
