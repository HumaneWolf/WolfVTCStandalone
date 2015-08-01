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
	$pagecontent .= '
	<div class="notification red">
		<p>You are already logged in.</p>
	</div>';
} else {
	$error['top'] = "";
	$error['username'] = "";
	$error['email'] = "";
	$error['password'] = "";
	$error['password2'] = "";

	$fill['username'] = "";
	$fill['email'] = "";

	$isfilled = FALSE; //Is the form filled out? Setting default value.
	$filled['username'] = FALSE; // Is it filled out right? Setting default value.
	$filled['email'] = FALSE;
	$filled['password'] = FALSE;


	if (isset($_POST['username']) && $_POST['username'] != "") {
		$fill['username'] = $_POST['username'];
		if (strlen($_POST['username']) <= 3 || strlen($_POST['username']) >= 25) {
			$error['top'] .= "<p>Your username must be between 3 and 25 characters.</p>";
			$error['username'] = "Your username must be between 3 and 25 characters.";
		} else {
			$username = new user($sql, "username", $_POST['username']);
			if ($username->load() == TRUE) {
				$error['top'] .= "<p>The username is already taken.</p>";
				$error['username'] = "The username is already taken.";
			} else {
				$filled['username'] = TRUE;
			}
		}
		$isfilled = TRUE;
	} else {
		$error['top'] .= "<p>Your username must be between 3 and 25 characters.</p>";
		$error['username'] = "Your username must be between 3 and 25 characters.";
	}

	if (isset($_POST['email']) && $_POST['email'] != "") {
		$fill['email'] = $_POST['email'];
		if (strlen($_POST['email']) <= 3 || strlen($_POST['email']) >= 150 || isMail($_POST['email']) == FALSE) {

			$error['top'] .= "<p>Please enter a valid email.</p>";
			$error['email'] = "Please enter a valid email.";
		} else {
			$username = new user($sql, "email", $_POST['email']);
			if ($username->load() == TRUE) {
				$error['top'] .= "<p>The email is already in use.</p>";
				$error['email'] = "The email is already in use.";
			} else {
				$filled['email'] = TRUE;
			}
		}
		$isfilled = TRUE;
	} else {
		$error['top'] .= "<p>Please enter a valid email.</p>";
		$error['email'] = "Please enter a valid email.";
	}

	if (isset($_POST['password'])) {
		if (strlen($_POST['password']) <= 6 || strlen($_POST['username']) >= 150) {
			$error['top'] .= "<p>Please enter a password that is at least 6 characters.</p>";
			$error['password'] = "Please enter a password that is at least 6 characters.";
		} else {
			if ($_POST['password'] != $_POST['password2']) {
				$error['top'] .= "<p>Both passwords must be identical.</p>";
				$error['password2'] = "Both passwords must be identical.";
			} else {
				$filled['password'] = TRUE;
			}
		}
		$isfilled = TRUE;
	} else {
		$error['top'] .= "<p>Both passwords must be identical.</p>";
		$error['password2'] = "Both passwords must be identical.";
	}

	if ($isfilled == TRUE && $filled['username'] == TRUE && $filled['email'] == TRUE && $filled['password'] == TRUE) {
			$user = new user($sql, "username", $_POST['username']);
			$user->email = $_POST['email'];

			$user->membersince = currentTime();

			$user->sessionstart = currentTime();

			if ($user->changePW($_POST['password'])) {
				if ($user->save()) {
					$user->load();
					$_SESSION['userid'] = $user->id;

					$pagecontent .= '
					<div class="notification green">
						<p>Your account has been created.</p>
					</div>';
				} else {
					$pagecontent .= '
					<div class="notification red">
						<p>Failed to save user.</p>
					</div>';
				}
			} else {
				$pagecontent .= '
				<div class="notification red">
					<p>Failed to save user.</p>
				</div>';
			}
	} else {
		if ($isfilled == TRUE) {
			$pagecontent .= '
			<div class="notification red">
				' . $error['top'] . '
			</div>';
		} else {
			$error['username'] = "";
			$error['email'] = "";
			$error['password'] = "";
			$error['password2'] = "";
		}
		$pagecontent .= '
		<form action="register.php" method="post">
			<table>
				<tr>
					<td>Username</td>
					<td><input type="text" name="username" placeholder="Username" size="35" value="' . $fill['username'] . '"></td>
					<td>' . $error['username'] . '</td>
				</tr>
				<tr>
					<td>Email</td>
					<td><input type="email" name="email" placeholder="email@example.com" size="35" value="' . $fill['email'] . '"></td>
					<td>' . $error['email'] . '</td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="password" placeholder="Password" size="35"></td>
					<td>' . $error['password'] . '</td>
				</tr>
				<tr>
					<td>Confirm Password</td>
					<td><input type="password" name="password2" placeholder="Password" size="35"></td>
					<td>' . $error['password2'] . '</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Register" size="35"> or <span class="cancel"><a href="index.php">cancel</a></span></td>
					<td></td>
				</tr>
			</table>
		</form>
		';
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
			<h3>Register an account</h3>
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