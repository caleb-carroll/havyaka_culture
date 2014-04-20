<!DOCTYPE html>
 <!--About us page to explain about the developer and website -->
<html>

<head>
 
<meta charset="utf-8">
  <title>about us</title>
   <script src="includes/js/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
  <link rel="stylesheet" type="text/css" href="includes/styles/style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/card_style.css"/>
<link rel="stylesheet" type="text/css" href="includes/styles/footer_header_style.css"/>
<script>

function doesCSS(p){
	var s = ( document.body || document.documentElement).style;
	return !!$.grep(['','-moz-', '-webkit-'],function(v){
		return  typeof s[v+p] === 'string';
	}).length;
}

$('html')
	.toggleClass('transform',doesCSS('transform'))
	.toggleClass('no-transform',!doesCSS('transform'));

$(function(){
	$('.flip').click(function(){
		$(this).parent().closest('.flipper').toggleClass('flipped');
	});
    });
	

</script>
</head>

<body>

  <?php include_once ('includes/header.inc.php'); 
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

    <div class="card flipper" style="margin-left:20em; width: 40em;height: 45em;">
                <div class="back">                
                        <h2>What is Community Connect?</h2>
                        <p>
                            Community Connect is website to bring the similar community, culture or religion people together. <br>
                            As, everything is getting modernized these days, it is essential to understand their own tradition and cherish their own culture.<br>
                            This makes local community strong and healthy.                    
                        </p>
                        <h2>What are the community type supported now?</h2>
                        <p>
                            As we begin with this website, we started working on Havyaka Culture. Our intention is to add as many culture/religion as possible.<br>
                             We will give an option to add their culture if not found already in the website to accommodate all the culture.
                        </p>
                        <h2>What is Havyaka Culture?</h2>
                        <p>
                            Havyakan's are a Hindu brahmin subsect, primarily from Indian states Karnataka, Keral and Kashmir.<br>
                            These days, they are spread all over the world. It is hard to find the events or authentic foods that only few people can prepare. <br>
                            So, this website is an effort to make easy for them to relish and cherish their tradition. More information on Havyaka is <a href="http://www.havyak.com/" target="_blank">here</a><br>                    
                        </p>
                         <h2>Thank you</h2>
                        <p>
                            Thank you visiting <b>Community Connect.</b> We hope you enjoy your tradition!.
                        </p>
                        <button class="flip">Who are we?</button><br>
                </div>
                <div class="front">
                    <h2>Who are we?</h2>
                    <p>
                        We are,<br> <b><i>Caleb Carol</i></b> &nbsp;&nbsp;<img src="pictures/calebc_profile.jpg" style="width: 200px;height: 150px;"/> </p>
                    <p>
                        <b><i>Nivedita Bhat</i></b>&nbsp;&nbsp;<img src="pictures/nivi_profile.jpg" style="width: 200px;height: 150px;"/> <br><br>
                        
                        We are HCI 573 students developed this  website 'Community Connect' as a final project.<br>
                        We have mainly used php, mysql, javascript to build this website. <br>. Thank you for visiting. Have fun!.                  
                    </p>
                    <button class="flip">Back</button><br>
               </div>
         </div>
      </div>
   </div>
</div>
<?php include('includes/footer.inc.php'); ?>
</body>
</html>