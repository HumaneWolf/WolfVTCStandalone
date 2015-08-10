<?php

//Footer copyright year
$copy = new config($sql);

$copy->name = "startDate";
$copy->load();

echo '<span class="copyright">&copy; ' . $website['name'] . ' ' . date("Y", strtotime($copy->value)) . ' - ' . date("Y") . '</span>';
?>
<span class="powered">Powered by <a href="https://github.com/HumaneWolf/WolfVTC">WolfVTC</a></span>