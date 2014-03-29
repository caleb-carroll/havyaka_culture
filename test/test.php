<?php
if ($_POST){
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
	}
	else {
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "Stored in: " . $_FILES["file"]["tmp_name"];
	}
}
?>

<html>
<body>

<form action="test.php" method="post" enctype="multipart/form-data">
	<label for="file">Filename:</label>
	<input type="file" name="file" id="file"><br>
	<input type="submit" name="submit" value="Submit">
</form>

</body>
</html>

<?php
$allowedExts = array("gif", "jpeg", "jpg", "png");
$file_handler = $_FILES["file"];

$temp = explode(".", $file_handler["name"]);

$extension = end($temp);

if ((($file_handler["type"] == "image/gif")
	|| ($file_handler["type"] == "image/jpeg")
	|| ($file_handler["type"] == "image/jpg")
	|| ($file_handler["type"] == "image/pjpeg")
	|| ($file_handler["type"] == "image/x-png")
	|| ($file_handler["type"] == "image/png"))
	&& ($file_handler["size"] < 500000)
	&& in_array($extension, $allowedExts)) {
	if ($file_handler["error"] > 0) {
		echo "Return Code: " . $file_handler["error"] . "<br>";
	}
	else {
		echo "Upload: " . $file_handler["name"] . "<br>";
		echo "Type: " . $file_handler["type"] . "<br>";
		echo "Size: " . ($file_handler["size"] / 1024) . " kB<br>";
		echo "Temp file: " . $file_handler["tmp_name"] . "<br>";

		if (file_exists("pictures/" . $file_handler["name"])) {
			echo $file_handler["name"] . " already exists. ";
		}
		else {
			move_uploaded_file($file_handler["tmp_name"], "pictures/" . $file_handler["name"]);
			echo "Stored in: " . "pictures/" . $file_handler["name"];
		}
	}
}
else {
	echo "Invalid file";
}
?>