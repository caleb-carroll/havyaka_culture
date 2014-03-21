<!-- 

Testing code found here:
http://forum.jquery.com/topic/jquery-flippy-plugin-reverse-issue
http://home.jejaju.com/play/flipCards/simple

-->

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Simple Card Flipper</title>
	<meta http-equiv="X-UA-Compatible" content="IE=edge;chrome=1" />
	<script src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
	<script>
	function doesCSS(p){
		var s = ( document.body || document.documentElement).style;
		return !!$.grep(['','-moz-', '-webkit-'],function(v){
			return  typeof s[v+p] === 'string'
		}).length
	}
	
	$('html')
		.toggleClass('transform',doesCSS('transform'))
		.toggleClass('no-transform',!doesCSS('transform'))
	
	$(function(){
		$('.flipper.manual').click(function(){
			$(this).toggleClass('flipped')
		})
	})
	
</script>
	<link rel="stylesheet" type="text/css" href="test.css"/>

</head>
<body>

	<div class="love flipper manual">
		<div class="face">
			Face
		</div>
		<div class="back">
			Back
		</div>
	</div>
	
	<div class="love flipper manual">
		<div class="face">
			Face
		</div>
		<div class="back">
			Back
		</div>
	</div>
</body>
</html>