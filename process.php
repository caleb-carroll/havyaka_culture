<?php

//first, check if we have both post and get data
if ($_GET){

	//next, check that the 'command' has been passed with $_GET data
	if (isset($_GET['command'])){
		
		//for each possible command, generate a response
		if ($_GET['command'] == 'get_letters'){
			//dummy response -- ideally this comes a MySQL database
			$letters = array("A","G","T","C");
			
			//convert to JSON format
			$json_response = json_encode($letters);
			
			//echo the response -- the client will pick it up 
			echo $json_response;
		}
		
	
		
	}
	

}

?>