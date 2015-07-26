<?php

public function addCargo($sql, $cargo) {
	if ($stmt = $sql->prepare("INSERT INTO wolfvtc_cargo (name) VALUES (?)")) {
		$stmt->bind_param("s", e($cargo));
		if ($stmt->execute()) {
			return TRUE;
		} else {
			return FALSE;
		}
	} else {
		return FALSE;
	}
}