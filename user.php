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

require("inc/user.php");


//PAGE CODE
$pagecontent = "";
if ($loggedin == TRUE) {
	//password
	$filled = FALSE;

	$fail['current'] = TRUE;
	$fail['password'] = TRUE;

	$message = "";

	$error['top'] = "";
	$error['current'] = "";
	$error['password'] = "";
	$error['password2'] = "";

	if (isset($_POST['current']) && $_POST['current'] != "") {
		if (strlen($_POST['current']) >= 6 && strlen($_POST['current']) <= 150) {
			if ($sessus->checkPW($_POST['current'])) {
				$fail['current'] = FALSE;
			} else {
				$error['current'] = "Wrong current password.";
				$error['top'] .= "<p>Wrong current password.</p>";
			}
		} else {
			$error['current'] = "Wrong current password.";
			$error['top'] .= "<p>Wrong current password.</p>";
		}
		$filled = TRUE;
	}

	if (isset($_POST['password']) && $_POST['password'] != "") {
		if (strlen($_POST['current']) >= 6 && strlen($_POST['current']) <= 150) {
			if (isset($_POST['password2']) && $_POST['password2'] != "") {
				if ($_POST['password'] == $_POST['password2']) {
					$fail['password'] = FALSE;
				} else {
					$error['password2'] = "The passwords are not identical.";
					$error['top'] .= "<p>The passwords are not identical.</p>";
				}
			} else {
				$error['password2'] = "The passwords are not identical.";
				$error['top'] .= "<p>The passwords are not identical.</p>";
			}
		} else {
			$error['password'] = "Your new password must be at least 6 characters.";
			$error['top'] .= "<p>Your new password must be at least 6 characters.</p>";
		}
		$filled = TRUE;
	}

	if ($filled == TRUE && $fail['current'] == FALSE && $fail['password'] == FALSE) {
		if ($sessus->changePW($_POST['password'])) {
			if ($sessus->save()) {
				$message = '<div class="notification green"><p>Your password has been changed.</p></div>';
			} else {
				$error['password'] = "Failed to save the new password.";
				$error['top'] .= "<p>Failed to save the new password.</p>";
			}
		} else {
			$error['password'] = "Failed to update the password.";
			$error['top'] .= "<p>Failed to update the password.</p>";
		}
	}

	if (isset($error['top']) && $error['top'] != "" && $filled == TRUE) {
		$error['top'] = '<div class="notification red">' . $error['top'] . '</div>';
	}


	//page
	$pagecontent .= "<h3>My account:</h3>
	<p><b>Verification:</b> ";

	if ($sessus->verified == TRUE) {
		$pagecontent .= "Verified.</p>";
	} else {
		$pagecontent .= "Not verified. Contact website staff for verification.</p>";
	}

	$pagecontent .= "<p><b>Account state:</b> ";

	if ($sessus->banned == TRUE) {
		$bannedby = new user($sql, "id", $sessus->banned);
		if ($bannedby->load()) {
			$bannedby = $bannedby->username;
			$pagecontent .= "Banned for " . $sessus->bannedreason . "(at " . $sessus->bannedtime . " by " . $bannedby . ")</p>";
		}
	} else {
		$pagecontent .= "Good.</p>";
	}

	$pagecontent .= '<h4>Change Password</h4>

	<form action="user.php" method="post">
			' . $error['top'] . $message . '
			<table>
				<tr>
					<td>Current password</td>
					<td><input type="password" name="current" placeholder="Current Password" size="35"></td>
					<td>' . $error['current'] . '</td>
				</tr>
				<tr>
					<td>New Password</td>
					<td><input type="password" name="password" placeholder="Password" size="35"></td>
					<td>' . $error['password'] . '</td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="password2" placeholder="Repeat Password" size="35"></td>
					<td>' . $error['password2'] . '</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Register" size="35"> or <span class="cancel"><a href="index.php">cancel</a></span></td>
					<td></td>
				</tr>
			</table>
		</form>';
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