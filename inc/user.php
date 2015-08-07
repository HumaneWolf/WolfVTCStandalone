<?php

if (isset($_SESSION['userid'])) {
	$sessus = new user($sql, "id", $_SESSION['userid']);
	if ($sessus->load() == TRUE) {
		$loggedin = TRUE;
	} else {
		$loggedin = FALSE;
	}
} else {
	$loggedin = FALSE;
}

if ($loggedin == TRUE) {
	$userbar = '<div class="user loggedin">
			<ul>
				<li><a href="logout.php">Log out</a></li>
				<li><a href="user.php">My account</a></li>';
	if ($sessus->adminusers == TRUE || $sessus->adminpages == TRUE || $sessus->admindivisions == TRUE || $sessus->adminnews == TRUE || $sessus->adminadmin == TRUE) {
		$userbar .= '<li><a href="admin.php">Admin CP</a></li>';
	}

	$userbar .= '<li><a href="division.php">My Division</a></li>
				<li><a href="forum.php">Forum</a></li>
				<li><a href="jobs.php">Jobs</a></li>
			</ul></div>';
	if($sessus->banned == TRUE) {
		$userbar .= '<div class="notification red" style="float:left;">
						<p>Your account is banned. Check the "My Account" page for more info.</p>
					</div>';
	}
} else {
	if (isset($loginform)) {
		//no. of drivers
		$drivers = $sql->query("SELECT count(*) FROM wolfvtc_users WHERE banned=FALSE AND verified=TRUE");
		$drivers = $drivers->fetch_assoc();
		
		$userbar =  '
			<div class="user">
				<div class="left">
					<form method="post" action="login.php">
						<h3>Log in</h3>
						<p>Username:</p>
						<input type="text" name="username" placeholder="Username">
						<p>Password:</p>
						<input type="password" name="password" placeholder="Password">
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
		$userbar = '<div class="user loggedin">
			<ul>
				<li><a href="login.php">Log in</a></li>
				<li><a href="register.php">Register</a></li>
			</ul>
			</div>';
	}
}