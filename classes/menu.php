<?php

class menu {
	public $id;

	public $name;
	public $url;
	public $weight;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}

	public function load() {
		if (intval($this->id) != 0) { //make an integer from the id variable. Checking if it's actually an integer by checking that it doesn't equal 0. If it fails to make an integer from it, intval will return 0.
			if ($loaded = $this->sql->query("SELECT * FROM wolfvtc_menu WHERE id='" . intval($this->id) . "'")) {
				$loaded = $loaded->fetch_assoc();
				$this->id = $loaded['id'];

				$this->name = $loaded['name'];
				$this->url = $loaded['url']
				$this->weight = $loaded['weight'];

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
			if ($save = $this->sql->prepare("UPDATE wolfvtc_menu SET (name=?, url=?, weight=?) WHERE id=?")) { // update info
				$save->bind_param("ssii", e($this->name), e($this->url), intval($this->weight), intval($this->id));
				if ($save->execute) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_menu (name, url, weight) VALUES (?, ?, ?)")) { // add new link
				$save->bind_param("ss", e($this->name), e($this->url), intval($this->weight));
				if ($save->execute) {
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