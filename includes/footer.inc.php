<html>
<head>
<link rel="stylesheet" type="text/css" href="includes/styles/footer_header_style.css"/>
</head>
    <body>
        <div id="footer">
            
                <?php
                $names = array("About Us","Faqs","Contact Us");
                $links = array("aboutus.php","FAQ.php","contactform.php");
                ?>
            
            <center><ul style="list-style: none;margin-left: 40em;font-size: 100%;font-weight: bold; color: brown;">
                <?php
                for ($i = 0; $i < count($names); $i++) {
                    //here, we check if the page on which the user is at is the same as the page in $links[$i]
                    if( basename($_SERVER['SCRIPT_NAME']) ==  $links[$i]) { 
                            $class = "active"; 
                    }
                    else {
                            $class = "";
                    }
                    ?>
                    <li style="display: block;float:left; padding: 2px;margin-left: 3em;"><a href="<?php echo $links[$i];?>" class="<?php echo $class;?>"><?php echo $names[$i];?></a></li>
                    <?php
                }
                ?>
                </ul></center>
                <br><center><h4>(c) 2014 Community Connect</h4></center>
        </div>
    </body>
</html>