<!DOCTYPE html>
<!--this is contact form, which has a form and unordered list to display name, phone etc and a submit button-->
<html>

<head>
<meta charset="utf-8">
  <title>Contact Us</title>
  <link rel="stylesheet" href="includes/styles/Contact_style_sheet.css"/>
    <script src="includes/js/jquery-1.10.2.js"></script>
 
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
                        <form class = "contact_form" action="#" method = "post" name = "contact_form">
                          <ul>
                                <h2>Contact Us:</h2>

                             <li>
                                <label>Name:</label>
                                <input type="text" placeholder="Enter your name" required="required" />
                             </li>

                             <li>
                                <label>Phone:</label>
                                <input type="number" placeholder = "123-345-1234"  />
                             </li>

                             <li>
                                <label>Email:</label>
                                <input type="email" placeholder="abc@abc.com"  />
                             </li>

                             <li>
                                 <label>Message:</label>
                                 <textarea name="message" cols="30" rows="10"></textarea>
                             </li>

                             <li>
                              <input class="radio" type="radio" checked="checked" name = "Contact_freequency" value = "First_time" />I am contacting you for the first time</br>
                              <input class ="radio" type="radio" name = "Contact_freequency" value= "often" />I have contacted you before
                             </li>
                             <li>
                                 <button class = "submit" type = "submit">Send the message</button>
                             </li>
                          </ul>
                     </form>
                 </div>
            </div>
        </div>
<?php include('includes/footer.inc.php'); ?>
</body>
</html>