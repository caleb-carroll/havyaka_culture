<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">
<script type="text/javascript" src="includes\js\scripts.js"></script>
 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 
<script>
/*function doesCSS(p){
	var s = ( document.body || document.documentElement).style;
	return !!$.grep(['','-moz-', '-webkit-'],function(v){
		return  typeof s[v+p] === 'string'
	}).length
}

$('html')
	.toggleClass('transform',doesCSS('transform'))
	.toggleClass('no-transform',!doesCSS('transform'));
*/
$(function(){
/*  $('.flip').click(function(){
		  console.log("clicked");
		  $(this).parent().closest('.flipper').toggleClass('flipped');
  }); */
  $( "#add_new_food_form" ).dialog({
		autoOpen: false,
		height: 500,
		width: 650,
		modal: true,
		buttons: {
		"Add food": function() {
			 alert('!');
			 var formData = new FormData($(this)[0]);                 
			 alert(formData);
			 add_new_food(formData);               
			$( this ).dialog( "close" );
		},
	Cancel: function() {
	  $( this ).dialog( "close" );
	}
  },
  close: function() {
	 $( this ).dialog( "close" );
  }
});
  
	$("#request_new_food_link").click(function() {
		   $( "#add_new_food_form" ).dialog( "open" );         
	});
   
	$("#add_selected_food").click(function(){
		var e = document.getElementById("selected_food");
		var food_id = e.options[e.selectedIndex].value;
		
		var chef_id = $(this).attr('rel1');
		var datastring = "food_id=" +food_id+ "&chef_id="+chef_id;
		console.log(datastring);
		 $.ajax(
			 { 

				 type: "POST",
				 url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=add_selected_food",                            
				 data: datastring,                             
				 success:function () {                                 
					 $('.success').fadeIn(2000).show().html('Added Successfully!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); 
				   // $("#chef_profile").load('chef_profile.php');
				   refresh_content();
				 }                         
			 });
	   return false;


	});

   function add_new_food(formData) {
   //  $("form#add_new_food_form").submit(function(){

	 //   var formData = new FormData($(this)[0]);
		alert('1');
		$.ajax({
			url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=add_new_food",
			type: 'POST',
			data: formData,
			async: false,
			success: function () {
				$('.success').fadeIn(2000).show().html('Added Successfully!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide(); 
					 //$("#chef_profile").load('chef_profile.php');
					  refresh_content();
			},
			cache: false,
			contentType: false,
			processData: false
		});

		return false;
	 // });
	}
	$(".update_food").click(function() {
	   var food_id = $(this).attr('rel');
	   var chef_id = $(this).attr('rel1');
	   var food_description_id = "food_description_"+food_id;
	   var food_description=document.getElementById(food_description_id).value;

	   if(food_description == '')
		{

						$('.error').fadeIn(400).show().html('Please enter the food description.'); 
		}
		else
		{
			var datastring = "food_description=" +food_description+ "&food_id=" +food_id+ "&chef_id="+chef_id;

			console.log(chef_id);

			 $.ajax(
			 { 

				 type: "POST",
				 url: "<?php echo $_SERVER['PHP_SELF']; ?>?cmd=update_food",                            
				 data: datastring,                             
				 success:function () {                                 
					 $('.success').fadeIn(2000).show().html('updated Successfully!').fadeOut(6000); //Show, then hide success msg
					$('.error').fadeOut(2000).hide();
					// $("#chef_profile").load('chef_profile.php');
					refresh_content();
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
					// $("#chef_profile").load('chef_profile.php');
					refresh_content();
				 }

			 });
			 return false;
	});

});
function refresh_content()
{
     $("#chef_profile").load('get_chef_load.php');
}
</script>

<?php
require_once 'includes/constants/sql_constants.php';
require_once 'chefProfile.php';
secure_page();
$user_id = $_SESSION['user_id'];

        $msg = NULL;
        $err=NULL;

if($_POST and $_GET)
{
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
     //   print_r($_POST);
       // print_r($_FILES);
            $chef_id=$_POST['chef_id'];
            $food_name=filter($_POST['food_name']);
            $food_description=filter($_POST['food_description']);
           $file_handler = $_FILES["file"];
            $picture = store_image($file_handler);
                  $picture_loc = "/".$picture;
            echo "food details " .$food_name .$food_description.$picture_loc;
              $new_food_id = add_new_food($chef_id,$food_name,$food_description,$picture_loc);
    }
    
    if($_GET['cmd'] == 'update_chef_profile')
    {
        echo "inside update chef profile";
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
           echo $chef_id;
            if($chef_id == NULL)
            {
             $chef_profile_edit = create_update_chef_profile($about_chef,$contact_time_preference,$accepted_payment_type,$pickup,$offline,$delivery,$user_id);
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
    $chef_info_ret = get_chef_details_logged_in_user($user_id);
    $chef_info = array_filter($chef_info_ret);

    if(!empty($chef_info)) {
        $chef_id =$chef_info[0]['chef_id'];
        $about_chef = $chef_info[0]['about_chef'];
        $contact_time_preference = $chef_info[0]['contact_time_preference'];
        $pickup_available = $chef_info[0]['pickup_available'];
        //echo "about chef".$about_chef."<br>".$contact_time_preference."<br>".$pickup_available;

        if($chef_id !=NULL){
          $food_chef = get_foods_of_chef($chef_id);
        }
    }
//Get the foods that the chef is preparing.

//get the event types
$event_types = get_event_types();
$results = get_events($user_id);
?>

<head>
	<title>My Profile</title>
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
                   
			<!-- Middle column start -->
                <div class="card " id="user_profile_div"  style="width: 45%;overflow-y: scroll;">
                      <span class="success" style="display:none;"></span>
                      <span class="error" style="display:none;">Please enter some text</span>
                    <div class="front">
                        <?php 
                        if(empty($results))
                        { ?>
                             <a href="manageEvents.php" name="create_events">Create an Event</a>&nbsp;&nbsp;
                       <?php } else
                        { ?>
                            <a href="manageEvents.php" name="manage_events">Manage your Events</a>&nbsp;&nbsp;
                      <?php  }
                       /* if($chef_info == NULL)
                        {
                        ?>
                            <h4>Become and chef and show off your cooking skill!</h4>
                            <a href="chefProfile.php" name="create_chef_profile">Create a Chef Profile</a>&nbsp;&nbsp;
                        <?php 
                        } else {?>
                             <a href="chefProfile.php" name="edit_chef_profile">Edit your Chef Profile</a>&nbsp;&nbsp;
                       <?php } */?>
                              
                        <p><h2>Hello &nbsp;<?php echo $user_info[0]['first_name'];?>,</h2>&nbsp;&nbsp;<br><br><img style="margin-left: 10em; width: 15em;height: 15em;" id="profile_picture" src="<?php echo $profile_pic_loc;?>" /><br><br><h3>Edit your profile here:</h3></br></p>

                        <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=update_user" method="post">
                                First name: <input type="text" class="input_box" name="first_name" value="<?php echo $user_info[0]['first_name'];?>"><br><br>
                                Last name: <input type="text" class="input_box" name="last_name" value="<?php echo $user_info[0]['last_name'];?>"><br><br>
                                Phone: <input type="text" class="input_box" name="phone" value="<?php echo $user_info[0]['phone'];?>"><br><br>
                                Email: <input type="text" class="input_box" name="email" value="<?php echo $user_info[0]['email'];?>"><br><br>    
                                       <input type="checkbox" value="public_info">Allow others to see my contact info<br></br>
                                <button type="submit">Save Changes</button>
                        </form>
                        <p>Upload a Picture</p>
                        <form action="<?php echo basename($_SERVER['PHP_SELF']);?>?cmd=add_picture" method="post" enctype="multipart/form-data">					
                                <input type="file" name="file" id="file"><br>
                                <input type="submit" name="submit" value="Submit">
                        </form>
		</div>
	   </div>  
            <div class="card flipper" id="chef_profile" style="width: 45%; overflow-y: scroll;">
               
             <?php                //include_once 'chefProfile.php';
             chef_profile_data($user_id); 
             ?>
                
            </div>
       <!-- Center column end -->
			
       </div>
        
    </div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>

</html>
