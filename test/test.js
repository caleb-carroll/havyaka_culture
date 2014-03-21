
/* for some reason including this javascript file makes the cards act strange on test.php */
$(document).ready(function(){
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
});


/* Script copied from the site mentioned in test.php */
/* <script>
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
		$('.flipper.jQHover').hover(function(){
			$(this).toggleClass('flipped')
		})
		$('.flipper.mouseDown').on('mousedown mouseup mouseleave',function(e){
			$(this).toggleClass( "flipped", e.type === "mousedown" );
		})
	})
</script> */