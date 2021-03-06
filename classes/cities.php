<?php

function addCity($sql, $city) {
	if ($stmt = $sql->prepare("INSERT INTO wolfvtc_cities (name) VALUES (?)")) {
		$stmt->bind_param("s", e($city));
		if ($stmt->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}

function city($sql, $city) {
	if ($stmt = $sql->query("SELECT * FROM wolfvtc_cities WHERE id=" . intval($city))) {
		$stmt = $stmt->fetch_assoc();
		return $stmt['name'];
	} else {
		return FALSE;
	}
}