<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<script type="text/javascript" src="includes\js\jquery-1.10.2.js"></script>
<link rel="stylesheet" type="text/css" href="includes/styles/footer_style.css"/>
<div id="footer">
	<center>(c) 2014 Community Connect</center>
        <p>
            <?php
               $names = array("Home","About Us","Faq's","Contact Us");
                $links = array("home.php","aboutus.php","Faq.php","contactform.php");

            ?>
            <ul>
                    <?php

                            for ($i = 0; $i < count($names); $i++){		   

                                    //here, we check if the page on which the user is at is the same as the page in $links[$i]
                                    if( basename($_SERVER['SCRIPT_NAME']) ==  $links[$i]) { 
                                            $class = "active"; 
                                    }
                                    else
                                            $class = "";


                                    ?>
                                    <li><a href="<?php echo $links[$i];?>" class="<?php echo $class;?>"><?php echo $names[$i];?></a></li>
                                    <?php
                            }
                               ?>
            </ul>
        </p>
</div>
</html>