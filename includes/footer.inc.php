<!-- This is a footer page, shows about us, faq and contact us -->
<div id="footer">
	<?php
	$names = array("About Us","FAQs","Contact Us");
	$links = array("aboutus.php","FAQ.php","contactform.php");
	?>
	<ul>
		<?php
		// puts a link for each footer item on the page
		for ($i = 0; $i < count($names); $i++) {
			//here, we check if the page on which the user is at is the same as the page in $links[$i]
			if( basename($_SERVER['SCRIPT_NAME']) ==  $links[$i]) { 
				$class = "active"; 
			}
			else {
				$class = "";
			}
			?>
			<li><a href="<?php echo $links[$i];?>" class="<?php echo $class;?>"> <?php echo $names[$i];?></a></li>
			<?php
		}
		?>
	</ul>
	
	<br><center><h4>(c) 2014 Community Connect</h4></center>
</div>