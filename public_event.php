<script src="includes/js/jquery-1.10.2.js"></script>
<link rel="stylesheet" href="includes/styles/style.css"/>

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

$q = "SELECT event_name,event_desc,event_date from event where event_status=1 and event_scope = 'public' ORDER BY RAND() LIMIT 1";
 if($event_query = mysqli_query($link,$q))
 {

    while ($row = mysqli_fetch_assoc($event_query))
    {
        $results[] =$row;
    }   
}

?>
<h1> Happening Events!</h1>
  <table>
     
    <?php 
      
      foreach ($results as $r) { 
      
        ?>
            <tr><th> Event Name: </th>
                <td><?php echo $r['event_name']; ?> </td></tr>
            <tr><th>  Event Details: </th><td> <?php echo $r['event_desc']; ?></td></tr>
               <tr> <th> Date:</th>
              <td> <?php echo $r['event_date']; ?> </td></tr>           
  </table>
 <?php } ?>

           


