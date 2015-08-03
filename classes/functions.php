<?php

//RANDOM STRING
function randomString($chars) {
	$viable = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$viablelen = strlen($viable);
	$rs = "";
	if (isset($chars)) {
		for ($i = 0; $i < $chars; $i++) { //Run $chars times.
			$rs .= $viable[rand(0, $viablelen - 1)]; //Add random letters
		}
	} else {
		return FALSE;
	}
	return $rs;
}

function e($input) {
	return htmlspecialchars($input);
}

function isMail($input) {
	if (filter_var($input, FILTER_VALIDATE_EMAIL) && strlen($input) <= 150) {
		return TRUE;
	} else {
		return FALSE;
	}
}

function currentTime() {
	return date("Y-m-d H:i:s");
}

function newLine($text) {
	$text = str_replace("\n", "<br>", $text);
	return $text;
}