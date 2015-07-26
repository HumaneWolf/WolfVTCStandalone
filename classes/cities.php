<?php

public function addCity($sql, $city) {
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