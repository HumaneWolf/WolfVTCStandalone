<div class="logo">
	<a href="index.php"><img src="img/logo.png"></a>
	<h1><a href="index.php"><?php echo $website['name']; ?></a></h1>
</div>
<div class="menu">
	<ul>
		<?php
		$menu = $sql->query("SELECT * FROM wolfvtc_menu ORDER BY weight ASC");

		while ($row = $menu->fetch_assoc()) {
			echo '<li><a href="' . $row['url'] . '">' . $row['name'] . '</a></li>';
		}
		?>
	</ul>
</div>