<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-GB">
<head>
	<title>Website Title</title>
	<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
	<meta name="robots" content="index, follow" />
	<link rel="stylesheet" type="text/css" href="styles/screen.css" media="screen" />
</head>
<body>

<div id="header">

	<h1>The Title / Logo of the Webiste</h1>
	<h2>Some catchy sounding phrase</h2>
	
	<?php include('includes/navigation.inc.php'); ?>
	
</div>
	
<div class="colmask rightmenu">
	<div class="colleft">
		<div class="col1">
			<!-- Column 1 start -->
			
			<p>Explore.php content</p>
			
			<!-- Column 1 end -->
		</div>
		<div class="col2">
			<!-- Column 2 start -->
			<?php include('includes/right_column.inc.php'); ?>
			<!-- Column 2 end -->
		</div>
	</div>
</div>

<?php include('includes/footer.inc.php'); ?>

</body>
</html>
