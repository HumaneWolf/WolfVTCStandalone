<?php

class division {
	public $id;

	public $name;
	public $description;

	public $public;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}

	public function load() {
		if ($load = $this->sql->query("SELECT * FROM wolfvtc_divisions WHERE id='" . intval($this->id) . "'")) {
			if ($load->num_rows == 1) {
				$this->name = $load['name'];
				$this->description = $load['description'];

				$this->public = $load['public'];
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function save() {
		if (isset($this->id) && $this->id != "") {
			if ($save = $this->sql->prepare("UPDATE wolfvtc_divisions SET (name=?, description=?, public=?) WHERE id=?")) {
				$save->bind_param("ssbi", e($this->name), e($this->description), $this->public, intval($this->id));
				if ($save->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_divisions (name, description, public) VALUES (?, ?, ?)")) {
				$save->bind_param("ssb", e($this->name), e($this->description), $this->public);
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