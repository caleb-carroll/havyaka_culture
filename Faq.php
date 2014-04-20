<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US">

<head>
<meta charset="utf-8">
  <title>Faq's</title>

<link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/footer_header_style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/jquery-ui-1.10.4.custom.css"></link> 
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="/resources/demos/style.css"></link>    
  <link rel="stylesheet" href="includes/styles/faq_style.css"/>
  
<link rel="stylesheet" type="text/css" href="styles/left_coulmn_style.css"/>

    <script>
    //function to execute the accordion style
   $(function() {
    $( "#accordion" ).accordion({
      collapsible: true
    });
  });
    </script>

</head>

<body>
   <?php
   //if the session is not started then display only header and not the navigation which should be available only for the logged in users
   include_once ('includes/header.inc.php'); 
   session_start();
        if($_SESSION){
             include('includes/navigation.inc.php');
        }
   ?>

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
   
    <h2>Get to know <i>Community Connect!</i></h2>

                 <div id = "accordion">
                        <h3>What is Community Connect?</h3>
                        <div class = "acc">
                         <p class = "para">
                             Connect back to your own community, culture religion people<br>and cherish your tradition.
                         </p>
                        </div>
                        <h3>How can I benifit from this website</h3>
                        <div>
                          <p class = "para">
                              You can benifit in atleast 4 ways:<br>
                              &nbsp;1.&nbsp;You can connect back to your local community people and events.<br>
                              &nbsp;2.&nbsp;You can create your own public or private events.<br>
                              &nbsp;3.&nbsp;You can find the local chef who prepares your own authentic foods.<br>
                              &nbsp;1.&nbsp;You can become a chef and market yourself.<br>                               
                         </p>
                        </div>
                                <h3>What is Card UI interface?</h3>        
                        <div>                
                                 <p class = "para">
                                    Card UI interface is future of the web!. Here, card flips over to display more information.
                                 </p>
                            </div>
                                <h3>How does card your work?</h3>
                        <div>
                            
                          <p class = "para">
                              You see the information in the front of the card, to view more, click on the relavent button to see more information on the back of the card.      
                         </p>
                        </div>
                           <h3>What kind of Events are here?</h3>
                        <div>
                            
                          <p class = "para">
                              Events related to your community, culture or religion
                         </p>
                        </div>     
                           <h3>Who are these chefs?</h3>
                        <div>
                             
                             <p class = "para">
                                These chefs are just like you, they cook and deliver.
                             </p>
                       </div>
                            <h3>Can I post events or become chef?</h3>
                        <div>
                            
                             <p class = "para">
                               Absolutely!  use your My dashboard to create new events or become a chef
                             </p>
                       </div>
                            <h3>What are the safety measures taken by the chef?</h3>
                        <div>
                             
                             <p class = "para">
                                All chef's are required to maintain the hygiene while cooking. And, they need to make sure the foods are safe and healthy.
                             </p>
                       </div>
                           <h3>Do you have more questions?:</h3>
                        <div>
                          <p class = "para">
                              <a href="ContactForm.php">Contact us</a> and let us know your comments and questions. <br>
                              We will be in touch with you.
                         </p>
                        </div>
                           <h3>Do you have issues using this website?:</h3>
                        <div>
                             <p class = "para">
                                <a href="ContactForm.php">Contact us</a> and let us know your comments and questions. <br>
                                We will be in touch with you.
                             </p>
                       </div>                            
             </div>
        </div>
    </div>
</div>
<?php include('includes/footer.inc.php'); ?>
</body>
</html>