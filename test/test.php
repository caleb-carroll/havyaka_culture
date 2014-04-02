<?php
	
	include('test_inc.php');
	
	$chefs_list = get_chefs_by_food(2);
/* 	echo "Chef ID array: <br>";
	print_r($chefs_list);
	echo "<br>"; */
	
	foreach ($chefs_list as $chef) {
/* 		echo '<br> $chef = ';
		print_r($chef);
		echo "<br>"; */
		
		$chef_info_array = get_chef_info($chef['chef_id']);
/* 		echo '<br> $chef_info_array = ';
		print_r($chef_info_array);
		echo "<br>"; */
		
		print_chef_card($chef_info_array);
	}
	
?>