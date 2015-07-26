<?php

if (isset($_SESSION['userid'])) {
	$sessus = new user("id", $_SESSION['userid']);
	if ($sessus->load() == TRUE) {
		$loggedin = TRUE;
	} else {
		$loggedin = FALSE;
	}
} else {
	$loggedin = FALSE;
}

if ($loggedin == TRUE) {
	$user = '<div class="user loggedin">
			<ul>
				<li><a href="logout.php">Log out</a></li>
				<li><a href="user.php">Edit account</a></li>';
	if ($sessus->adminusers == TRUE || $sessus->adminpages == TRUE || $sessus->admindivisions == TRUE || $sessus->adminnews == TRUE || $sessus->adminadmin == TRUE) {
		$user .= '<li><a href="admin.php">Admin CP</a></li>';
	}
	if ($sessus->divisionadmin == TRUE) {
		$user .= '<li><a href="admin.php">Division Admin</a></li>';
	}

	$user .= '<li><a href="division.php">My Division</a></li>
			</ul>
			</div>';
} else {
	if (isset($loginform)) {
		//no. of drivers
		$drivers = $sql->query("SELECT count(*) FROM wolfvtc_users WHERE banned=FALSE AND verified=TRUE");
		$drivers = $drivers->fetch_assoc();
		
		$user =  '
			<div class="user">
				<div class="left">
					<form method="post" action="login.php">
						<h3>Log in</h3>
						<p>Username:</p>
						<input type="text" name="username" placeholder="Username">
						<p>Password:</p>
						<input type="password" name="username" placeholder="Password">
						<input type="submit" value="Log in">
					</form>
				</div>
				<div class="right">
					<h3>. . . or register</h3>
					<a href="register.php">Register</a>
					<p>Join our ' . $drivers['count(*)'] . ' other users!</p>
				</div>
			</div>';
	} else {
		$user = '<div class="user loggedin">
			<ul>
				<li><a href="login.php">Log in</a></li>
				<li><a href="register.php">Register</a></li>
			</ul>
			</div>';
	}
}