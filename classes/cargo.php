<?php

function addCargo($sql, $cargo) {
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

function cargo($sql, $cargo) {
	if ($stmt = $sql->query("SELECT * FROM wolfvtc_cargo WHERE id=" . intval($argo))) {
		$stmt = $stmt->fetch_assoc();
		return $stmt['name'];
	} else {
		return FALSE;
	}
}