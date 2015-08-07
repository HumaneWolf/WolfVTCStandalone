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

//$loginform = TRUE;
//Remove to have the full login form show up on this page.

require("inc/user.php");

//PAGE TITLE
$pagetitle = "Admin CP";

//PAGE CODE
$pagecontent = "";
if ($sessus->adminusers == TRUE || $sessus->adminpages == TRUE || $sessus->admindivisions == TRUE || $sessus->adminnews == TRUE || $sessus->adminadmin == TRUE) {
	if (isset($_GET['action']) && $_GET['action'] != "") {
		if ($_GET['action'] == "approval" && $sessus->adminusers == TRUE) {
			$pagecontent .= '<h3>Users awaiting approval</h3>';

			if (isset($_POST['a-id']) && $_POST['a-id'] != "") {
				$user = new user($sql, "id", $_POST['a-id']);
				if ($user->load()) {
					$user->verified = TRUE;
					if ($user->save()) {
						$pagecontent .= '<div class="notification green"><p>User has been verified.</p></div>';
					} else {
						$pagecontent .= '<div class="notification red"><p>Failed to approve user: Could not save user info.</p></div>';
					}
				} else {
					$pagecontent .= '<div class="notification red"><p>Failed to approve user: Could not load user info.</p></div>';
				}
			} 

			if ($list = $sql->query("SELECT * FROM wolfvtc_users WHERE verified=FALSE")) {
				if ($list->num_rows == 0) {
					$pagecontent .= '<div class="notification red"><p>No users awaiting approval.</p></div>';
				} else {
					$pagecontent .= '<table class="table">
					<tr>
						<th>ID</th>
						<th>Username</th>
						<th>Email</th>
						<th>Signed up</th>
						<th>Banned</th>
						<th>Actions</th>
					</tr>';

					while ($row = $list->fetch_assoc()) {
						$pagecontent .= '<tr>
						<td>' . $row['id'] . '</td>
						<td>' . $row['username'] . '</td>
						<td>' . $row['email'] . '</td>
						<td>' . $row['membersince'] . '</td>';

						if ($row['banned'] == TRUE) {
							$banned = "Yes";
						} else {
							$banned = "No";
						}
						$pagecontent .= '<td>' . $banned . '</td>
						<td>
							<form action="admin.php?action=approval" method="post" class="link">
								<input type="hidden" name="a-id" value="' . $row['id'] . '">
								<input type="submit" value="Verify">
							</form>
						</td>
						</tr>';
					}

					$pagecontent .= '</table>';
				}
			} else {
				$pagecontent .= '<div class="notification red"><p>Failed to load users.</p></div>';
			}
		} else {
			$pagecontent .= '<div class="notification red"><p>You do not have permission to view this page.</p></div>';
		}
	} else {
		$pagecontent .= '<h3>Admin Control Panel</h3>';

		if ($sessus->adminusers == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=approval">Users awaiting approval</a></div>';
		}

		if ($sessus->adminusers == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=users">User list</a></div>';
		}

		if ($sessus->adminusers == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=bans">Ban list</a></div>';
		}

		if ($sessus->adminnews == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=news">Manage News</a></div>';
		}

		if ($sessus->adminpages == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=pages">Manage Pages</a></div>';
		}

		if ($sessus->admindivisions == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=divisions">Manage Divisions</a></div>';
		}

		if ($sessus->adminadmin == TRUE || $sessus->adminnews == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=city">Cities</a></div>';
		}

		if ($sessus->adminadmin == TRUE || $sessus->adminnews == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=cargo">Cargo</a></div>';
		}

		if ($sessus->adminusers == TRUE || $sessus->adminpages == TRUE || $sessus->admindivisions == TRUE || $sessus->adminnews == TRUE || $sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=stats">Statistics</a></div>';
		}

		if ($sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=index">Front Page</a></div>';
		}
	}
} else {
	$pagecontent .= '<div class="notification red"><p>You do not have permission to view this page.</p></div>';
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include("inc/head.php"); ?>
	<link rel="stylesheet" type="text/css" href="style/admin.css">
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