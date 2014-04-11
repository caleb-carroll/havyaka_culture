<?php
require_once 'includes/constants/sql_constants.php';

//Pre-assign our variables to avoid undefined indexes
$username = NULL;
$pass2 = NULL;
$msg = NULL;
$err = array();
global $link;
$results = array();
//query the public events and display them randomly in the public_event section at the registration page

$q = "SELECT event_name, event_desc, event_date, t2.image_location AS event_image FROM " . EVENT . " as t1 LEFT JOIN " . EVENT_PICTURE . " AS t2 ON t1.event_id = t2.event_id WHERE event_status=1 AND event_scope = 'public' ORDER BY RAND() LIMIT 1";

if($event_query = mysqli_query($link,$q)) {
	while ($row = mysqli_fetch_assoc($event_query)) {
		$results[] =$row;
	}
	mysqli_free_result($event_query);
}
?>

<h1>Public Events!</h1>

<?php
foreach ($results as $r) {
	if (empty($r['event_image'])){
        $r['event_image']="/pictures/default_event.jpg";
    }
    ?>
    <p class="event_name"><?php echo $r['event_name']; ?></p>
    <p class="event_date"><?php echo $r['event_date']; ?></p>
	<p class="event_description"><?php echo $r['event_desc']; ?></p>
    <img src="<?php echo BASE . $r['event_image']; ?>" class="event_image" style="max-width:15em"/>
<?php 
} 
?>