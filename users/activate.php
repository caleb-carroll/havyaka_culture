<?php
require '../includes/constant/config.inc.php';
return_meta("Activate your account");

$err = array();
$msg = array();
$user = NULL;
$activ = NULL;
$user_email = NULL;

/******** EMAIL ACTIVATION LINK**********************/
if(isset($_GET['user']) && !empty($_GET['activ_code']) && !empty($_GET['user']) && is_numeric($_GET['activ_code']) )
{

	$user = filter($_GET['user']);
	$activ = filter($_GET['activ_code']);

	//check if activ code and user is valid
	$rs_check = mysql_query("SELECT id FROM ".USERS." WHERE md5_id='$user' AND activation_code='$activ'") or die (mysql_error());
	$num = mysql_num_rows($rs_check);
	// Match row found with more than 1 results  - the user is authenticated.
	if ( $num <= 0 )
	{
		$err[] = "Unable to verify account";
	}

	if(empty($err))
	{
		// set the approved field to 1 to activate the account
		$rs_activ = mysql_query("UPDATE ".USERS." SET approved='1' WHERE
		md5_id='$user' AND activation_code = '$activ' ") or die(mysql_error());
		$msg[] = "Account activated successfully!  You may now <a href=\"".SITE_BASE."/login.php\">login</a>.";
	}
}

/******************* ACTIVATION BY FORM**************************/
if (isset($_POST['activate']))
{

	$user_email = filter($_POST['user_email']);
	$activ = filter($_POST['activ_code']);
	//check if activ code and user is valid as precaution
	$rs_check = mysql_query("SELECT id FROM ".USERS." WHERE usr_email = AES_ENCRYPT('$user_email', '$salt') AND activation_code='$activ'") or die (mysql_error());
	$num = mysql_num_rows($rs_check);
	// Match row found with more than 1 results  - the user is authenticated.
	if ( $num <= 0 )
	{
		$err[] = "Unable to verify account";
	}
	//set approved field to 1 to activate the user
	if(empty($err))
	{
		$rs_activ = mysql_query("UPDATE ".USERS." SET approved='1' WHERE
		usr_email= AES_ENCRYPT('$user_email', '$salt') AND activation_code = '$activ' ") or die(mysql_error());
		$msg[] = "Account activated successfully!  You may now <a href=\"".SITE_BASE."/login.php\">login</a>.";
	}
}
?>
<script>
$(document).ready(function(){
	$("#activ_form").validate();
});
</script>
</head>
<body>
<div id="container">

	<?php include '../includes/constant/nav.inc.php'; ?>

	<?php
	//Show message if isset
	if(!empty($msg))
	{
		echo '<div class="success">'.$msg[0].'</div>';
	}
	//Show error message if isset
	if(!empty($err))
	{
		echo '<div class="err">';
		foreach($err as $e)
		{
		echo $e.'<br />';
		}
		echo '</div>';
	}
	?>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="activ_form">
	<table cellpadding="5" cellspacing="5" border="0">
	<tr>
	<td>Email:</td>
	<td><input type="text" name="user_email" value="<?php echo stripslashes($user_email); ?>" class="required" /></td>
	</tr>
	<td>Activation Code:</td>
	<td><input type="text" name="activ_code" value="<?php echo stripslashes($activ); ?>" class="required" /></td>
	</tr>
	<tr>
	<td colspan="2" align="center"><input type="submit" name="activate" value="Activate Account" /></td>
	</tr>
	</table>
	</form>

</div>
</body>
</html>