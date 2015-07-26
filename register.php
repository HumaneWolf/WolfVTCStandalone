<?php
session_start();

//INCLUDES
require_once("config.php");
require_once("db/connect.php");

//USED CLASSES
require_once("classes/settings.php");
require_once("classes/user.php");

//LOGGED IN? / SESSION

//$loginform = TRUE;
//Remove to have the full login form show up on this page.

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


	$isfilled = FALSE; //Is the form filled out? Setting default value.
	$filled = TRUE; // Is it filled out right? Setting default value.
	if (isset($_POST['username']) && $_POST['username'] != "") {
		if (strlen($_POST['username']) <= 3 || strlen($_POST['username']) >= 25) {
			$filled = FALSE;
			$error['top'] .= "<p>Your username must be between 3 and 25 characters.</p>";
			$error['username'] = "Your username must be between 3 and 25 characters.";
		} else {
			$username = new user($sql, "username", $_POST['username']);
			if ($username->load() == FALSE) {
				$filled = FALSE;
				$error['top'] .= "<p>The username is already taken.</p>";
				$error['username'] = "The username is already taken.";
			}
		}
		$isfilled = TRUE;
	}

	if (isset($_POST['email']) && $_POST['email'] != "") {
		if (strlen($_POST['email']) <= 3 || strlen($_POST['email']) >= 150 || isMail($_POST['email'])) {
			$filled = FALSE;
			$error['top'] .= "<p>Please enter a valid email.</p>";
			$error['email'] = "Please enter a valid email.";
		} else {
			$username = new user($sql, "email", $_POST['email']);
			if ($username->load() == FALSE) {
				$filled = FALSE;
				$error['top'] .= "<p>The email is already in use.</p>";
				$error['email'] = "The email is already in use.";
			}
		}
		$isfilled = TRUE;
	}

	if (isset($_POST['password'])) {
		if (strlen($_POST['password']) <= 5 || strlen($_POST['username']) >= 150) {
			$filled = FALSE;
			$error['top'] .= "<p>Please enter a password that is at least 5 characters.</p>";
			$error['password'] = "Please enter a password that is at least 5 characters.";
		}
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
			<?php echo $user; ?>
		<div class="content">
			<h3>Register an account</h3>
			<?php echo $pagecontent; ?>
			<form action="register.php" method="post">
				<table>
					<tr>
						<th>Username</th>
						<th><input type="text" name="username" placeholder="Username" size="35"></th>
						<th></th>
					</tr>
					<tr>
						<th>Email</th>
						<th><input type="text" name="email" placeholder="email@example.com" size="35"></th>
						<th></th>
					</tr>
					<tr>
						<th>Password</th>
						<th><input type="password" name="password" placeholder="Password" size="35"></th>
						<th></th>
					</tr>
					<tr>
						<th>Confirm Password</th>
						<th><input type="password" name="password2" placeholder="Password" size="35"></th>
						<th></th>
					</tr>
					<tr>
						<th></th>
						<th><input type="submit" value="Register" size="35"> or <span class="cancel"><a href="index.php">cancel</a></span></th>
						<th></th>
					</tr>
				</table>
			</form>
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