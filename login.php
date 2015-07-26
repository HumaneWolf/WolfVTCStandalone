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

if ($loggedin == FALSE) {
	$filled = FALSE;
	$fail = FALSE;
	$pagecontent = "";

	if (isset($_POST['username']) && $_POST['username'] != "") {
		if (strlen($_POST['username']) <= 3 || strlen($_POST['username']) >= 25) {
			$fail = TRUE;
		}
		$filled = TRUE;
	}

	if (isset($_POST['password']) && $_POST['password'] != "") {
		if (strlen($_POST['password']) <= 6 || strlen($_POST['password']) >= 150) {
			$fail = TRUE;
		}
		$filled = TRUE;
	}

	if ($filled == TRUE) {
		if ($fail == FALSE) {
			$logginguser = new user($sql, "username", $_POST['username']);
			if ($logginguser->load() == TRUE) {
				if ($logginguser->checkPW($_POST['password'])) {
					$logginguser->sessionstart = currentTime();
					if ($logginguser->save()) {
						$_SESSION['userid'] = $logginguser->id;

						$pagecontent .= '
							<div class="notification green">
								<p>You have been logged in.</p>
							</div>';

						if ($logginguser->banned == TRUE) {
							$pagecontent .= '
							<div class="notification false">
								<p>Your account is banned. Find out more on the "My Account" page.</p>
							</div>';
						}
						$loginform = FALSE;
						require("inc/user.php");
					} else {
						$pagecontent .= '
						<div class="notification red">
							<p>Failed to complete log in.</p>
						</div>';
					}
				} else {
					$pagecontent .= '
					<div class="notification red">
						<p>Invalid username or password.</p>
					</div>';
				}
			} else {
				$pagecontent .= '
				<div class="notification red">
					<p>Invalid username or password.</p>
				</div>';
			}
		} else {
			$pagecontent .= '
			<div class="notification red">
				<p>Invalid username or password.</p>
			</div>';
		}
	}
} else {
	$pagecontent .= '
				<div class="notification red">
					<p>You are already logged in.</p>
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