<!DOCTYPE html>
 <!-- this is the freequently asked questions page. Here, I have tried to use Accordion jquery function to fold and unfold the sections -->
<html>

<head>
<meta charset="utf-8">
  <title>Faq's</title>
   <script src="<?php echo BASE; ?> /includes/js/jquery-1.10.2.js"></script>
    <script src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" href="includes/styles/faq_style.css"/>

    <script>
    //function to execute the accordion style
    $(function()  {
      $('#accordion').accordion();
    } )  ;

    </script>

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
    
<h2>All questions about this website</h2>

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
                            <h3>Do you have issues using this website?:</h3>
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