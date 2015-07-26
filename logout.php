<?php
session_start();

//INCLUDES
require_once("config.php");
require_once("db/connect.php");
require_once("classes/functions.php");

//USED CLASSES
require_once("classes/settings.php");
require_once("classes/user.php");

//LOGGED IN? / SESSION

$loginform = TRUE;

require("inc/user.php");


//PAGE CODE
$pagecontent = "";

if ($loggedin == TRUE) {
	unset($_SESSION['userid']);

	$pagecontent .= '
	<div class="notification green">
		<p>You have been logged out.</p>
	</div>';

	require("inc/user.php");
} else {
	$pagecontent .= '
	<div class="notification red">
		<p>You are not logged in.</p>
	</div>';
}

?>
<!DOCTYPE html>
<html>
<head>
	<?php include("inc/head.php"); ?>
</head>
<body>
	<div class="wrapper">
		<div class="header">
			<?php require("inc/header.php"); ?>
		</div>
			<?php echo $userbar; ?>
		<div class="content">
			<?php echo $pagecontent; ?>
		</div>
		<div class="footer">
			<?php include("inc/footer.php"); ?>
		</div>
	</div>
</body>
</html>
<?php
require_once("db/disconnect.php");
?>