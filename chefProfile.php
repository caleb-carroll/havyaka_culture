<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<script type="text/javascript" src="includes\js\scripts.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
 
<script>
	function doesCSS(p){
		var s = ( document.body || document.documentElement).style;
		return !!$.grep(['','-moz-', '-webkit-'],function(v){
			return  typeof s[v+p] === 'string'
		}).length
	}

	$('html')
		.toggleClass('transform',doesCSS('transform'))
		.toggleClass('no-transform',!doesCSS('transform'));


$(function(){
             $("#create_event_div").hide();
             $("#saved_event_div").hide();
             $("#saved_chef_div").hide();
             $("#close_card_event_chef").hide();
             $("#change_food_pic_form").hide();
             
              $( ".datepicker" ).datepicker({dateFormat: "yy-mm-dd" });
              
		$('.flip').click(function(){
			console.log("clicked");
			$(this).parent().closest('.flipper').toggleClass('flipped');
		});
                
                $("#create_event_button").click(function() {
                    $("#create_event_div").show();
                    $("#create_event_button").hide(); 
                    return false;
                });
                
                $("#cancel_event").click(function() {
                     $(this).closest('form').find("input[type=text], textarea").val("");
                    $("#create_event_div").hide();
                    $("#create_event_button").show(); 
                    return false;
                });
                
                $("#saved_event_button").click(function() {
                    $("#saved_event_div").show();
                    $("#close_card_event_chef").show();
                    
                });
                 $("#saved_chef_button").click(function() {
                    $("#saved_chef_div").show();
                    $("#close_card_event_chef").show();
                   
                });
                $("#close_card_event_chef").click(function() {
                    $("#saved_event_div").hide();
                 $("#saved_chef_div").hide();
                 $("#close_card_event_chef").hide(); 
                  $("#user_profile_div").hide();
                });
                
                $("#change_food_pic").click(function() {
                    $("#change_food_pic_form").show();
                     $("#change_food_pic").hide();                    
                });
                $("#user_profile_button").click(function() {
                    $("#user_profile_div").show();
                    $("#close_card_event_chef").show(); 
                });
                
                $("#request_new_food_link").click(function() {
                   $("#request_new_food_div").show(); 
                });
                
                $("#cancel_food").click(function() {
                   $("#request_new_food_div").hide();
                   
                   $("#request_new_food_link").show();
                });
                
                $("#add_selected_food").click(function(){
                    var e = document.getElementById("selected_food");
                    var food_id = e.options[e.selectedIndex].value;
                    
                    var chef_id = $(this).attr('rel1');
                    var datastring = "food_id=" +food_id+ "&chef_id="+chef_id;
                    
                     $.ajax(
                         { 
                            
                             type: "POST",
                             url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=add_selected_food",                            
                             data: datastring,                             
                             success:function () {                                 
                                 $('.success').fadeIn(2000).show().html('Added Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); 
                                //refresh_content();
                             }                         
                         });
                   return false;
                    
                    
                });
                                
                 $("form#add_new_food_form").submit(function(){

                    var formData = new FormData($(this)[0]);

                    $.ajax({
                        url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=add_new_food",
                        type: 'POST',
                        data: formData,
                        async: false,
                        success: function () {
                            $('.success').fadeIn(2000).show().html('Added Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); 
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });

                    return false;
                });
           
                $(".update_food").click(function() {
                   var food_id = $(this).attr('rel');
                   var chef_id = $(this).attr('rel1');
                   var food_description_id = "food_description_"+food_id;
                   var food_price_id = "food_price_"+food_id;
                   var food_description=document.getElementById(food_description_id).value;
                   var food_price = document.getElementById(food_price_id).value;
                   
                   if(food_description == '')
                    {
                                    
                                    $('.error').fadeIn(400).show().html('Please enter the food description.'); 
                    }
                    else
                    {
                        var datastring = "food_description=" +food_description+ "&food_id=" +food_id+ "&chef_id="+chef_id;

                        console.log(food_price+chef_id);
                        
                         $.ajax(
                         { 
                            
                             type: "POST",
                             url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=update_food",                            
                             data: datastring,                             
                             success:function () {                                 
                                 $('.success').fadeIn(2000).show().html('updated Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); 
                                //refresh_content();
                             }                         
                         });
                    }
                   return false;
                });
                $(".delete_food").click(function() {
                    var food_id = $(this).attr('rel');
                    var chef_id = $(this).attr('rel1');
                     var datastring = "food_id=" +food_id+ "&chef_id=" +chef_id;
                  alert(datastring);
                  $.ajax(
                         { 
                            
                             type: "POST",
                             url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=Delete_food",                            
                             data: datastring,                             
                             success:function () {                                 
                                 $('.success').fadeIn(2000).show().html('deleted Successfully!').fadeOut(6000); //Show, then hide success msg
				$('.error').fadeOut(2000).hide(); 
                                //refresh_content();
                             }
                         
                         });
                         return false;
                });
               
});
</script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();
$user_id = $_SESSION['user_id'];

		$msg = NULL;
                $err=NULL;

if($_POST and $_GET){
	
	if ($_GET['cmd'] == 'update_user'){
		 
		$first_name = $_POST['first_name'];
		$last_name = $_POST['last_name'];
		$phone = $_POST['phone'];
		$email = $_POST['email'];
		$profile_picture = NULL;
		
		// function to update an event 
		if (update_user_info($user_id, $first_name, $last_name, $email, $phone, $profile_picture)) {
			// add something here to display success/failure?
			 $msg="Profile updated successfully";
		}
		else {
			$err = "Oops!. sorry, could not update your profile, Please try again";
		}
	}
       
	// if the user is adding a picture, add it to the file system and reference in user table
	if ($_GET['cmd'] == 'add_picture' || $_GET['cmd'] == 'add_event_picture' || $_GET['cmd'] == 'add_food_picture'){
            
		if ($_FILES["file"]["error"] > 0) {
			echo "Error: " . $_FILES["file"]["error"] . "<br>";
		}
		else {
                    
			$file_handler = $_FILES["file"];
			$picture = store_image($file_handler);
                        $picture_loc = "/".$picture;
                            if($_GET['cmd'] == 'add_picture') {
                                // $user_info[0]['profile_picture'] = $profile_picture;
                                update_user_info($user_id, NULL, NULL, NULL, NULL, $picture_loc);
                            } 
                            elseif ($_GET['cmd'] == 'add_event_picture') 
                            {
                                
                                $event_id = $_POST['event_id'];
                                update_event_picture($picture_loc,$event_id);
                                
                            } elseif ($_GET['cmd'] == 'add_food_picture')
                            {
                               
                                $food_id=$_POST['food_id'];
                                 $food_update = update_foods_of_chef(NULL,$food_id,NULL,NULL,$picture_loc);
                                    if($food_update)
                                    {
                                        $msg="Food details updated successfully";
                                    } else {
                                        $err="Could not update this time, Please try again";
                                    }
                            }
		}
	}
        if($_GET['cmd'] == 'Delete_food')
        {
            echo "it is coming here";
            
                $food_id = $_POST['food_id'];
                $chef_id = $_POST['chef_id'];
                echo $food_id.$chef_id;
                 $q= "DELETE from ".FOOD_CHEF_DETAILS. " WHERE food_id =".$food_id." AND chef_id =".$chef_id. ";";
               
                 if($food_q = mysqli_query($link,$q))
                 {
                     $msg="Deleted successfully!";
                 } else
                 {
                     $err="Could not delete, please try again";
                 }

		exit();
        }
        
        if($_GET['cmd'] == 'add_selected_food')
        {
            
             $chef_id = $_POST['chef_id'];
              $food_id = $_POST['food_id'];
              echo $chef_id.$food_id;
              $add_selected_food = add_selected_food($food_id,$chef_id);
              if($add_selected_food)
              {
                   $msg="Food details added successfully to your bucket";
              } else
              {
                  $err = "You have already added this food!";
              }            
        }
        
         if($_GET['cmd'] == 'update_food')
        {
              echo "it is coming here";
              $chef_id=$_POST['chef_id'];
              $food_description=filter($_POST['food_description']);
             
              $food_id=$_POST['food_id'];              
            
              $food_update = update_foods_of_chef($chef_id,$food_id,$food_description,NULL);
              if($food_update)
              {
                  $msg="Food details updated successfully";
              } else {
                  $err="Could not update this time, Please try again";
              }
             
        }
        if($_GET['cmd'] == 'add_new_food')
        {
           // print_r($_POST);
           // print_r($_FILES);
                $chef_id=$_POST['chef_id'];
                $food_name=filter($_POST['food_name']);
                $food_description=filter($_POST['food_description']);
               $file_handler = $_FILES["file"];
                $picture = store_image($file_handler);
                      $picture_loc = "/".$picture;
                echo $food_name .$food_description.$picture_loc;
                  $new_food_id = add_new_food($chef_id,$food_name,$food_description,$picture_loc);
        }
        if($_GET['cmd'] == 'update_chef_profile')
        {
            
            $about_chef = filter($_POST['about_chef']);
            $contact_time_preference = $_POST['contact_time_preference'];
            $accepted_payment_type = $_POST['accepted_payment_type'];
            $chef_id = $_POST['chef_id'];
            if(isset ($_POST['pickup']))
                {
                    $pickup = $_POST['pickup']; 
                    $pickup = "yes";                    
                } else 
                {
                    $pickup = "no";
                }
                 if(isset ($_POST['offline']))
                {
                     $offline = $_POST['offline']; 
                    $offline = "yes";                    
                } else 
                {
                    $offline = "no";
                }
                 if(isset ($_POST['delivery']))
                {
                    $delivery = $_POST['delivery']; 
                    $delivery = "yes";                    
                } else 
                {
                    $delivery = "no";
                }
           
            if($chef_id)
            {
             $chef_profile_edit = create_update_chef_profile($about_chef,$contact_time_preference,$accepted_payment_type,$pickup,$offline,$delivery);
            } else {
                $chef_profile_edit = create_update_chef_profile($about_chef,$contact_time_preference,$accepted_payment_type,$pickup,$offline,$delivery,$user_id,$chef_id);
            }
            if($chef_profile_edit)
            {
                $msg = "Chef profile updated successfully!";
            } else
            {
                $err = "Could not update your profile page, please try again";
            }            
        }
 }
    
            $user_info = get_user_info($user_id);
            $profile_pic = $user_info[0]['profile_picture'];
            $profile_pic_loc = htmlspecialchars($profile_pic);
            $profile_pic_loc = BASE.$profile_pic_loc;
            list($width, $height, $type, $attr)= getimagesize($profile_pic_loc);

            //Get the chef details of the logged in user if exists
            $chef_info = get_chef_details_logged_in_user($user_id);
            $chef_info_filter = array_filter($chef_info);

            if(!empty($chef_info_filter)) {
       
                $chef_id =$chef_info[0]['chef_id'];
               // echo "chef id is: " .$chef_id;
                $about_chef = $chef_info[0]['about_chef'];
                $contact_time_preference = $chef_info[0]['contact_time_preference'];
                $pickup_available = $chef_info[0]['pickup_available'];

                //Get the foods that the chef is preparing.
                if($chef_id !=NULL){
                $food_chef = get_foods_of_chef($chef_id);
                }
            }

                //get the event types
                $event_types = get_event_types();
                $food_names = get_all_food_names();

?>

<head>
	<title>Chef Profile</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="includes/styles/profile_styles.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
	<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>
</head>
<body>

<div id="header">

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
                    <?php 
                    if(isset($msg))
                        {
                                echo '<div class="success" >'.$msg.'</div>';
                        } elseif (isset($err))
                        {
                            echo '<div class="error">'.$err.'</div>';
                        }
?>
                    
                <div class="dashboard_sub_section">  
                   <?php include('includes/subnavigation.inc.php'); ?>

               </div>
                       <div class="card flipper" style="width:80em; height:500px;">
                            <div class="back">
                               <?php if(!empty($chef_info_filter))
                                { ?>
                                <button class="flip">Edit your food bucket</button> &nbsp;<br></br>
                                <h2>Edit your Chef Profile</h2> 
                                <?php } else {?>                                
                                    <h2>Create a new Chef Profile</h2> 
                                    <button class="flip">Create your food bucket</button> &nbsp;<br></br>
                                <?php } ?>
                                     <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_chef_profile" method="post"> 
                                         <input type='hidden' name='chef_id' value='<?php echo $chef_info[0]['chef_id'];?>' ></input>
                                                
                                            About yourself as a chef: <textarea style="width:400px; height: 100px;"  name="about_chef"><?php echo $chef_info[0]['about_chef'];?></textarea><br>				
                                            Contact Hours: <select name="contact_time_preference" id="contact_time_preference">                                            
                                                                <option value="morning" <?php if($chef_info[0]['contact_time_preference'] == "morning") echo "selected";?>>Morning</option>
                                                                <option value="noon" <?php if($chef_info[0]['contact_time_preference'] == "noon") echo "selected";?>>Noon</option>
                                                                <option value="evening" <?php if($chef_info[0]['contact_time_preference'] == "evening") echo "selected";?>>Evening</option>
                                                                <option value="anytime" <?php if($chef_info[0]['contact_time_preference'] == "evening") echo "selected";?>>Any time</option>
                                                           </select> 
                                                 <br>
                                             Accepted payment method's : <select name="accepted_payment_type" id="accepted_payment_type">                                            
                                                    <option value="cash" <?php if($chef_info[0]['payments_accepted'] == "cash") echo "selected";?>>Cash</option>
                                                    <option value="check" <?php if($chef_info[0]['payments_accepted'] == "Check") echo "selected";?>>Check</option>
                                                    <option value="cash or check" <?php if($chef_info[0]['payments_accepted'] == "Cash or Check") echo "selected";?>>Cash or Check</option>
                                                    <option value="paypal" <?php if($chef_info[0]['payments_accepted'] == "Paypal") echo "selected";?>>Paypal</option>
                                                    <option value="other" <?php if($chef_info[0]['payments_accepted'] == "Other") echo "selected";?>>Other</option>
                                              </select> 
                                    <br>
                                                <!-- marks these checkboxes as checked or unchecked based on what we find in the DB -->
                                                <input style="width:20px; height: 20px;" type="checkbox" name='pickup' value="pickup" <?php if($chef_info[0]['pickup_available'] == "Yes") echo "checked"; else echo "unchecked";?>>Offer pickup?</input><br>
                                                 <input style="width:20px; height: 20px;" type="checkbox" name='offline' value="offline" <?php if($chef_info[0]['taking_offline_order'] == "Yes") echo "checked"; else echo "unchecked";?>>Take offline orders?</input><br>
                                                 <input style="width:20px; height: 20px;" type="checkbox" name='delivery' value="delivery" <?php if($chef_info[0]['delivery_available'] == "Yes") echo "checked"; else echo "unchecked";?>>Offer delivery?</input><br>
                                                 
                                                 <input type="submit" name="submit" value="Update"></input>
                                      </form>             
                                 </div>
                            <div class="front">
                                <button class="flip">Back to Chef Profile</button> &nbsp;<br></br>
                                 <?php if(!empty($food_chef))
                                { ?>
                                <h2>Edit your food bucket</h2> 
                                <?php } else {?>
                                    <h2>Create a new food bucket</h2> 
                                <?php } ?>
                                <div id="request_new_food_div" style="display:none;">   
                               
                                <h3>Add a food to your profile. (This should be one, you started taking orders!)</h3>
                                   <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" id ="add_new_food_form" method="post" enctype="multipart/form-data">
                                       <input type="hidden" name="chef_id" value="<?php echo $chef_info[0]['chef_id'];?>"></input>
                                           Food Name: <input class="input_box" name="food_name" id="new_food_name" placeholder="Enter the food Name">
                                           Food description: <input class="input_box" name="food_description" id="new_food_description" placeholder="Enter the food Name"></input>
                                           
                                     <h3> Add a colorful picture to your food!</h3>
                                          <input type="file" name="file" id="food_pic"><br>
                                          <input type="submit" name="submit" value="Update"> &nbsp;<button name="cancel_food" id="cancel_food">Cancel</button>
                                  </form>
                                
                               </div>
                                <br>
                                  <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" method="post">    
                                        <div id="food_from_db">
                                            <select id ="selected_food" class="dropdown">
                                                <option selected value="default">Please Select a Food Type</option>
                                                <?php
                                                foreach ($food_names as $current_food)
                                                {
                                                ?>
                                                    <option value="<?php echo $current_food['food_id'];?>" ><?php echo $current_food['food_name'];?></option>

                                                <?php } ?>
                                             </select>
                                            <button rel="<?php echo $current_food['food_id'];?>" rel1="<?php echo $chef_info[0]['chef_id'];?>" id="add_selected_food">Add this food to your bucket </button> &nbsp;&nbsp;&nbsp; <h4>Not found anything you prepare?</h4>
                                            <a class="link_class" id="request_new_food_link" href="#" >Request one Now!</a>
                                        </div>
                                  </form>
                                 <?php if(isset($food_chef))
                               { ?>   
                                    <table><h4>Food details, that you are ready to prepare</h4>

                                        <tr><th> Food Name </th>
                                        <th> Food Description </th>
                                        <th> Food picture </th>
                                        <th> Your Action</th></tr>
                                    <?php
                                        foreach($food_chef as $r)
                                        {
                                                $food_id= $r['food_id'];
                                                $food_picture = $r['food_picture'];
                                                $food_picture_loc = htmlspecialchars($food_picture);
                                                $food_picture_loc = BASE.$food_picture_loc;
                                                list($width, $height, $type, $attr)= getimagesize($food_picture_loc);
                                        ?>
                                        <tr>
                                            <form action="<?php echo basename($_SERVER['PHP_SELF']);?>" method="post">

                                                     <td id="food_name_<?php echo $food_id;?>"> <?php echo $r['food_name'];?></td>

                                                     <td> <textarea name="food_description" id="food_description_<?php echo $r['food_id'];?>" ><?php echo $r['food_description'];?></textarea></td>

                                                     <td><img style="width: 80px; height: 70px;" src="<?php echo $food_picture_loc;?>"</td>

                                                     <td><button name="delete_food" class ="delete_food" rel="<?php echo $r['food_id'];?>" rel1="<?php echo $chef_info[0]['chef_id'];?>" id="delete_food_"<?php echo $r['food_id'];?> >Delete this food</button><br> 

                                                     <button class="update_food" rel="<?php echo $r['food_id'];?>" rel1=<?php echo $chef_info[0]['chef_id'];?> id="update_food_"<?php echo $r['food_id'];?> >Update this food</button>
                                            </form>
                                             <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_food_picture" method="post" enctype="multipart/form-data">
                                                 <input  type="hidden" name="food_id" value="<?php echo $r['food_id'];?>">                                                   
                                                 <input type="file" name="file" id="food_pic"><br>
                                                 <input type="submit" name="submit" value="Update">
                                             </form>
                                        </tr>
                            <?php } ?>
                            
                                   </table>
                             <?php  }?>
                            </div>
			</div>
			<!-- Center column end -->
			
		</div>
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>

</html>
