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
		} elseif ($_GET['action'] == "users" && $sessus->adminusers == TRUE) {
			if (isset($_GET['id'])) {
				$pagecontent .= '<h3>Edit user</h3>';
				if (intval($_GET['id']) != 0) {
					$user = new user($sql, "id", intval($_GET['id']));
				} elseif (isMail($_GET['id']) == TRUE) {
					$user = new user($sql, "email", e($_GET['id']));
				} else {
					$user = new user($sql, "username", e($_GET['id']));
				}
				if ($user->load()) {

					$redmsg = "";
					$greenmsg = "";

					if (isset($_POST['save'])) {
						$changed = FALSE;
						$redmsg = "";
						$greenmsg = "";

						if (isset($_POST['password'])) {
							$password = randomString(25);
							$user->changePW($password);
							$greenmsg = '<p>New user password: ' . $password . '</p>
							<p>Give it to the user.</p>';
							$changed = TRUE;
						}

						if (isset($_POST['verified'])) {
							$user->verified = TRUE;
							$changed = TRUE;
						} else {
							$user->verified = FALSE;
							$changed = TRUE;
						}

						if (isset($_POST['division'])) {
							$user->division = intval($_POST['division']);
							$changed = TRUE;
						}

						if (isset($_POST['divisionapproved']) && $user->division != 0) {
							$user->divisionapproved = TRUE;
							$changed = TRUE;
						} else {
							$user->divisionapproved = FALSE;
							$changed = TRUE;
						}

						if ($sessus->admindivisions == TRUE) {
							if (isset($_POST['divisionadmin']) && $user->division != 0) {
								$user->divisionadmin = TRUE;
								$changed = TRUE;
							} else {
								$user->divisionadmin = FALSE;
								$changed = TRUE;
							}
						}

						if ($sessus->adminadmin == TRUE) {
							if (isset($_POST['adminusers'])) {
								$user->adminusers = TRUE;
								$changed = TRUE;
							} else {
								$user->adminusers = FALSE;
								$changed = TRUE;
							}

							if (isset($_POST['adminpages'])) {
								$user->adminpages = TRUE;
								$changed = TRUE;
							} else {
								$user->adminpages = FALSE;
								$changed = TRUE;
							}

							if (isset($_POST['admindivisions'])) {
								$user->admindivisions = TRUE;
								$changed = TRUE;
							} else {
								$user->admindivisions = FALSE;
								$changed = TRUE;
							}

							if (isset($_POST['adminnews'])) {
								$user->adminnews = TRUE;
								$changed = TRUE;
							} else {
								$user->adminnews = FALSE;
								$changed = TRUE;
							}

							if (isset($_POST['adminadmin'])) {
								$user->adminadmin = TRUE;
								$changed = TRUE;
							} else {
								$user->adminadmin = FALSE;
								$changed = TRUE;
							}
						}

						if ($changed == TRUE) {
							if ($user->save()) {
								$greenmsg .= "<p>User information saved.</p>";
							} else {
								$redmsg .= "<p>Failed to save user information.</p>";
							}
						}

						if (isset($greenmsg) && $greenmsg != "") {
							$greenmsg = '<div class="notification green">' . $greenmsg . '</div>';
						}
						if (isset($redmsg) && $redmsg != "") {
							$redmsg = '<div class="notification red">' . $redmsg . '</div>';
						}
					}

					$pagecontent .= $redmsg . $greenmsg;

					//GENERATING SOME OPTIONS
					//division list
					$divisions = '<option value="0">None</option>';
					$divs = $sql->query("SELECT * FROM wolfvtc_divisions");
					while ($row = $divs->fetch_assoc()) {
						$hidden = "";
						if ($row['public'] == FALSE) {
							$hidden = " (private)";
						}
						$default = "";
						if ($row['id'] == $user->division) {
							$default = " selected";
						}
						$divisions .= '<option value="' . $row['id'] . '"' . $default . '>' . $row['name'] . $hidden . '</option>';
					}

					//division approved
					$divapp = '<input type="checkbox" name="divisionapproved" value="yes"';
					if ($user->divisionapproved == TRUE) {
						$divapp .= " checked>";
					} else {
						$divapp .= ">";
					}

					//division admin
					if ($sessus->admindivisions == TRUE) {
						$divad = '<input type="checkbox" name="divisionadmin" value="yes"';
						if ($user->divisionadmin == TRUE) {
							$divad .= " checked>";
						} else {
							$divad .= ">";
						}
						$divad .= " User is an admin for the division.";
					} else {
						if ($user->divisionadmin == TRUE) {
							$divad = "Yes";
						} else {
							$divad = "No";
						}
					}

					//banned
					if ($user->banned == TRUE) {
						$bannedby = new user($sql, "id", $user->bannedby);
						if ($bannedby->load()) {
							$bannedby = $bannedby->username;
						} else {
							$bannedby = "invalid user";
						}
						$banned = "Yes, at " . $user->bannedtime . " for " . $user->bannedreason . " by " . $bannedby;
					} else {
						$banned = "No";
					}

					//verified
					$verified = '<input type="checkbox" name="verified" value="yes"';
					if ($user->verified == TRUE) {
						$verified .= " checked>";
					} else {
						$verified .= ">";
					}

					//ADMIN PERMISSION CHANGES
					$adminperms = "";
					if ($sessus->adminadmin == TRUE) {
						//editusers
						$adminusers = '<input type="checkbox" name="adminusers" value="yes"';
						if ($user->adminusers == TRUE) {
							$adminusers .= " checked>";
						} else {
							$adminusers .= ">";
						}

						//edit pages
						$adminpages = '<input type="checkbox" name="adminpages" value="yes"';
						if ($user->adminpages == TRUE) {
							$adminpages .= " checked>";
						} else {
							$adminpages .= ">";
						}

						//edit divisions
						$admindivisions = '<input type="checkbox" name="admindivisions" value="yes"';
						if ($user->admindivisions == TRUE) {
							$admindivisions .= " checked>";
						} else {
							$admindivisions .= ">";
						}

						//news
						$adminnews = '<input type="checkbox" name="adminnews" value="yes"';
						if ($user->adminnews == TRUE) {
							$adminnews .= " checked>";
						} else {
							$adminnews .= ">";
						}

						//superadminthingy
						$adminadmin = '<input type="checkbox" name="adminadmin" value="yes"';
						if ($user->adminadmin == TRUE) {
							$adminadmin .= " checked>";
						} else {
							$adminadmin .= ">";
						}

						$adminperms = '<tr>
							<th></th>
							<td><h4>Permissions</h4></td>
							<td></td>
						</tr>
						<tr>
							<th>Users</th>
							<td>' .  $adminusers . ' Can approve and edit users</td>
							<td></td>
						</tr>
						<tr>
							<th>Pages</th>
							<td>' .  $adminpages . ' Can create and edit pages</td>
							<td></td>
						</tr>
						<tr>
							<th>Divisions</th>
							<td>' .  $admindivisions . ' Can create and edit divisions</td>
							<td></td>
						</tr>
						<tr>
							<th>News</th>
							<td>' .  $adminnews . ' Can create and edit news articles</td>
							<td></td>
						</tr>
						<tr>
							<th>Superadmin</th>
							<td>' .  $adminadmin . ' Can edit permissions and other settings</td>
							<td></td>
						</tr>';
					}

					//thing itself
					$pagecontent .= '<form action="admin.php?action=users&id=' . intval($_GET['id']) . '" method="post">
					<table class="form">
						<tr>
							<th></th>
							<td><h4>General info</h4></td>
							<td></td>
						</tr>
						<tr>
							<th>ID</th>
							<td>' . intval($_GET['id']) . '</td>
							<td></td>
						</tr>
						<tr>
							<th>Username</th>
							<td>' . $user->username . '</td>
							<td></td>
						</tr>
						<tr>
							<th>Email</th>
							<td>' . $user->email . '</td>
							<td></td>
						</tr>
						<tr>
							<th>Password</th>
							<td><input type="checkbox" name="password" value="generate"> Generate new password</td>
							<td></td>
						</tr>
						<tr>
							<th>Verified</th>
							<td>' . $verified . ' User is verified</td>
							<td></td>
						</tr>
						<tr>
							<th>Banned</th>
							<td>' . $banned . '</td>
							<td></td>
						</tr>
						<tr>
							<th></th>
							<td><h4>Division</h4></td>
							<td></td>
						</tr>
						<tr>
							<th>Division</th>
							<td><select name="division">' . $divisions . '</select></td>
							<td></td>
						</tr>
						<tr>
							<th>Approved</th>
							<td>' .  $divapp . ' User is approved into the division</td>
							<td></td>
						</tr>
						<tr>
							<th>Division Admin</th>
							<td>' .  $divad . '</td>
							<td></td>
						</tr>
						' . $adminperms . '
						<tr>
							<th><input type="hidden" name="save" value="yes"></th>
							<td><input type="submit" value="Save" size="35"> or <span class="cancel"><a href="admin.php?action=users">cancel</a></span></td>
							<td></td>
						</tr>
					</table>
					</form>';
				} else {
					$pagecontent .= '<div class="notification red"><p>User not found.</p></div>';
				}
			} else {
				$pagecontent .= '<h3>User list</h3>';

				if ($list = $sql->query("SELECT * FROM wolfvtc_users")) {
					if ($list->num_rows == 0) {
						$pagecontent .= '<div class="notification red"><p>No users (then how are you here? o.O)</p></div>';
					} else {
						$pagecontent .= '<table class="table">
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>Email</th>
							<th>Signed up</th>
							<th>Verified</th>
							<th>Banned</th>
							<th>Actions</th>
						</tr>';

						while ($row = $list->fetch_assoc()) {
							$pagecontent .= '<tr>
							<td>' . $row['id'] . '</td>
							<td>' . $row['username'] . '</td>
							<td>' . $row['email'] . '</td>
							<td>' . $row['membersince'] . '</td>';

							if ($row['verified'] == TRUE) {
								$verified = "Yes";
							} else {
								$verified = "No";
							}
							$pagecontent .= '<td>' . $verified . '</td>';

							if ($row['banned'] == TRUE) {
								$banned = "Yes";
							} else {
								$banned = "No";
							}
							$pagecontent .= '<td>' . $banned . '</td>
							<td>
								<a href="admin.php?action=users&id=' . $row['id'] . '" class="linkify">Edit user</a>
								<br>
								<a href="admin.php?action=bans&do=add&id=' . $row['id'] . '" class="linkify">Edit ban</a>
							</td>
							</tr>';
						}
						$pagecontent .= '</table>';
					}
				} else {
					$pagecontent .= '<div class="notification red"><p>Failed to load users.</p></div>';
				}
			}
		} else {
			$pagecontent .= '<div class="notification red"><p>You do not have permission to view this page.</p></div>';
		}
	} else {
		//
		// ADMIN PANEL FRONT PAGE
		//

		$pagecontent .= '<h3>Admin Control Panel</h3>';

		if ($sessus->adminusers == TRUE || $sessus->adminpages == TRUE || $sessus->admindivisions == TRUE || $sessus->adminnews == TRUE || $sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=jobs">Jobs</a></div>';
		}

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

		if ($sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=index">Front Page</a></div>';
		}

		if ($sessus->adminpages == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=menu">Manage Menu</a></div>';
		}

		if ($sessus->admindivisions == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=divisions">Manage Divisions</a></div>';
		}

		if ($sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=city">Cities</a></div>';
		}

		if ($sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=cargo">Cargo</a></div>';
		}

		if ($sessus->adminusers == TRUE || $sessus->adminpages == TRUE || $sessus->admindivisions == TRUE || $sessus->adminnews == TRUE || $sessus->adminadmin == TRUE) {
			$pagecontent .= '<div class="admbutton"><a href="admin.php?action=stats">Statistics</a></div>';
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