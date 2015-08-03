<?php
session_start();

//INCLUDES
require_once("config.php");
require_once("db/connect.php");
require_once("classes/functions.php");

//USED CLASSES
require_once("classes/settings.php");
require_once("classes/user.php");
require_once("classes/anno.php");

//LOGGED IN? / SESSION

$loginform = TRUE;

require("inc/user.php");

//PAGE CODE

$pagecontent = "";

if (isset($_GET['art']) && intval($_GET['art']) != 0) {
	$news = new anno($sql);
	$news->id = intval($_GET['art']);
	if ($news->load()) {
		if ($news->divid == 0) {
			$by = new user($sql, "id", $news->userid);
			if ($by->load()) {
				$author = $by->username;
			} else {
				$author = "unknown user";
			}

			$pagecontent .= '<h3>' . $news->title . '</h3>
							<p class="by">by ' . $author . ' on ' . $news->datetime . '</p>
							' . $news->text;

			$pagetitle = "News: " . $news->title;
		} else {
			$pagecontent .= "<h3>The news article could not be found.</h3>";
			$pagetitle = "Not found";
		}
	} else {
		$pagecontent .= "<h3>The news article could not be found.</h3>";
		$pagetitle = "Not found";
	}
} else {
	$pagecontent .= "<h3>No news article specified.</h3>";
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