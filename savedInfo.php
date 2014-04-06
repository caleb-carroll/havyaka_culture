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
             
              $( ".datepicker" ).datepicker({dateFormat: "yy-mm-dd" });
              
		$('.flip').click(function(){
			console.log("clicked");
			$(this).parent().closest('.flipper').toggleClass('flipped');
		});
                
                
                $("#delete_saved_event").click()
                {
                    //todo
                }
                
});

</script>

<?php
require_once 'includes/constants/sql_constants.php';
secure_page();
$user_id = $_SESSION['user_id'];

		$msg = NULL;
                $err=NULL;

if($_POST and $_GET){
	if ($_GET['cmd'] == 'update_event'){
		echo "inside update_event";
		$event_name = filter($_POST['event_name']);
		$event_date =filter($_POST['event_date']);
		$event_desc = filter($_POST['event_desc']);               
		$event_scope = filter($_POST['event_scope']);
                $e_type_id = filter($_POST['event_type']);
                $venue_name = filter($_POST['venue_name']);
                $venue_address = filter($_POST['venue_address']);
                $event_zipcode = filter($_POST['event_zipcode']); 
		$e_recurring_id = 1;
		$event_id = $_POST['event_id'];
		
		// function to update an event 
		if (update_event($event_name, $event_date, $event_desc, $event_scope, $e_type_id, $venue_name, $venue_address,$event_zipcode, $e_recurring_id, $event_id)) {
			// add something here to display success/failure?
                   ?>
                       <script>
                         get_city_state('<?php echo $event_zipcode;?>');
                        </script>  
                   <?php 
			 $msg="Event updated successfully";
		}
		else {
			$err = "Oops!. sorry, could not update your event, Please try again";
		}
	}

}

$saved_events = get_saved_events($user_id);

$saved_chef = get_saved_chef($user_id);
$saved_contacts = get_saved_contacts($user_id);

?>

<head>
	<title>Community Connect</title>
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
    <?php
                if(!empty($saved_events))
                { ?>
                    <div class="card" id='saved_event_div'>
                        <div class="front">
                            <table> 
                                <th>Event Name</th>
                                <th>Event Date</th>
                                <th>Venue Address</th>
                                <th>Contact info  of organizer</th>
                                <th>Your action</th>
                                
                                <?php
                                foreach($saved_events as $r)
                                {?>
                                <tr>
                                    <input type="hidden" id="event_id" value=""></input>
                                    <td> <?php echo $r['event_name']; ?><br><?php echo $r['event_desc']; ?></br>  </td>
                                     <td> <?php echo $r['event_date']; ?>  </td>
                                      <td> <?php echo $r['venue_name']; ?><br><?php echo $r['venue_address']; ?></br>&nbsp; <?php echo $r['city']; ?> ,&nbsp;<?php echo $r['state']; ?> -<?php echo $r['zipcode']; ?>   </td>
                                      <td><?php echo $r['first_name']; ?> &nbsp;<?php echo $r['last_name']; ?><br><?php echo $r['email']; ?></br><?php echo $r['phone']; ?></td>
                                      <td><button class="delete_saved_event" rel="<?php echo $r['event_id']; ?>" id="delete_saved_event_"<?php echo $r['event_id']; ?>>Delete</button></td>
                                </tr>
                                <?php }?>
                            </table>
                        </div>
                    </div>
                <?php }?>
                    <div class="card" id='saved_chef_div'>
                        <div class="front">
                            saved chef
                        </div>
                    </div>
                    <div class="card" id='saved_contacts_div'>
                        <div class="front">
                            saved contacts
                        </div>
                     </div>
		</div>
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>

</html>
