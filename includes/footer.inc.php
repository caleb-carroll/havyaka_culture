<link rel="stylesheet" type="text/css" href="includes/styles/footer_style.css"/>
<div id="footer">
	<center>(c) 2014 Community Connect</center>
	<p>
	<?php
	$names = array("Home","About Us","FAQs","Contact Us");
	$links = array("home.php","aboutus.php","FAQ.php","contactform.php");
	?>
	<ul>
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
			<li><a href="<?php echo $links[$i];?>" class="<?php echo $class;?>"><?php echo $names[$i];?></a></li>
			<?php
		}
	?>
	</ul>
	</p>
</div>
</html>