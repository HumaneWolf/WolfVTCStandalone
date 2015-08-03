<?php
session_start();

//INCLUDES
require_once("config.php");
require_once("db/connect.php");
require_once("classes/functions.php");

//USED CLASSES
require_once("classes/settings.php");
require_once("classes/user.php");
require_once("classes/pages.php");

//LOGGED IN? / SESSION

require("inc/user.php");

//PAGE CODE

$pagecontent = "";

if (isset($_GET['id']) && intval($_GET['id']) != 0) {
	$page = new page($sql);
	$page->id = intval($_GET['id']);
	if ($page->load()) {
		if ($page->ispublic == TRUE) {
			$pagecontent .= '<h3>' . $page->title . '</h3>
			' . $page->text;
		} elseif ($page->ispublic == FALSE && $loggedin == TRUE) {
			$pagecontent .= '<h3>' . $page->title . '</h3>
			' . $page->text;
		} else {
			$pagecontent .= "<h3>You do not have permission to view this page.</h3>";
			$pagetitle = "Not found";
		}
	} else {
		$pagecontent .= "<h3>Page not found.</h3>";
		$pagetitle = "Not found";
	}
} else {
	$pagecontent .= "<h3>No page specified.</h3>";
	$pagetitle = "Not found";
}

?>
<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="style/admin.css">
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