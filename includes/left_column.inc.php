<?php 
require_once 'includes/constants/sql_constants.php';


// select food_name from " . FOOD . " LIMIT 6
$q = "SELECT food_id, food_name FROM " . FOOD . " LIMIT 5";


if($food_query = mysqli_query($link, $q)) {
	while($row = mysqli_fetch_assoc($food_query)) {
		$foods[] = $row;
	}
}


?>


<!-- This column contains the food categories and search options which will be present on each page.  -->
<!-- Column 1 start -->
<div id = "left_column">
	<div class="category_heading">
		<center>Food Categories</center>
		<div class="categories">
			<p>
			<ul>
				<?php
				foreach ($foods as $food) {
				?>
				<li>
					<a href="searchResults.php?food_id=<?php echo $food['food_id']; ?>"><?php echo $food['food_name']; ?></a>
				</li>
				<?php } ?>
			</ul>
			</p>
		</div>
	</div>
	
	<!--<div class="ad_search_heading">
		<center>Advanced Search</center>
		<div class="search_option">
			<p>search options</p>
		</div>
	</div> -->
</div>