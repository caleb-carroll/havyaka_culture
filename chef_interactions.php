<?php
require_once 'includes/constants/sql_constants.php';
// print_r($_GET['cmd']);
if(isset($_GET['cmd'])){
	if ($_GET['cmd'] == 'save_chef'){
		$chef_id = $_POST['chef_id'];
		$user_id = $_POST['user_id'];
		
		if(save_info("chef", $user_id, $chef_id)){
			$results = array(
				"success" => true,
				"message" => "Save was successful"
			);
		}
		else {
			$results = array(
				"success" => false,
				"message" => "Save failed"
			);
		}
		
		$json_response = json_encode($results);
		echo $json_response;
	}
}
?>