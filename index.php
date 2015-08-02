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

//PAGETITLE
$pagetitle = "Home";

//PAGE CODE

//news list
$newsq = $sql->query("SELECT * FROM wolfvtc_announcements WHERE divid=0");
$news = "";

if ($newsq->num_rows >= 1) {
	$news .= "<h3>Latest news:</h3>";
}

while ($row = $newsq->fetch_assoc()) {
	$author = new user($sql, "id", $row['userid']);
	if ($author->load()) {
		$news .= '<p><span class="title"><a href="news.php?art=' . $row['id'] . '" class="news">' . $row['title'] . '</a></span> <span class="by">By ' . $author->username . ' on ' . $row['datetime'] . '</p>';
	}
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
			<?php echo $news; ?>
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