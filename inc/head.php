<meta charset="UTF-8">
<?php
if (isset($pagetitle)) {
	echo '<title>' . $pagetitle . ' - ' . $website['name'] . '</title>';
} else {
	echo '<title>' . $website['name'] . '</title>';
}
?>
<link rel="stylesheet" type="text/css" href="style/notifications.css">
<link rel="stylesheet" type="text/css" href="style/main.css">
<link rel="stylesheet" type="text/css" href="style/custom.css">