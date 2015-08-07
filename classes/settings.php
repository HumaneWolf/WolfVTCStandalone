<?php

class config {
	public $name;
	public $value;

	private $loaded = FALSE;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}

	public function load() {
		if ($load = $this->sql->prepare("SELECT value FROM wolfvtc_config WHERE name=?")) {
			$load->bind_param("s", $this->name);
			$load->execute();
			
			$load->store_result();
			$load->bind_result($this->value);
			$load->fetch();
			return TRUE;
			$this->loaded = TRUE;
		} else {
			return FALSE;
		}
	}

	public function save() {
		if ($loaded == TRUE) {
			if ($save = $this->sql->prepare("UPDATE wolfvtc_config SET (value=?) WHERE name=?")) {
				$save->bind_param("ss", $this->value, $this->name);
				if ($save->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_config (name, value) VALUES (?, ?)")) {
				$save->bind_param("ss", $this->name, $this->value);
				if ($save->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
	}
}