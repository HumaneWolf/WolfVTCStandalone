<?php

class menu {
	public $id;

	public $title;
	public $text;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}

	public function load() {
		if (intval($this->id) != 0) { //make an integer from the id variable. Checking if it's actually an integer by checking that it doesn't equal 0. If it fails to make an integer from it, intval will return 0.
			if ($loaded = $this->sql->query("SELECT * FROM wolfvtc_pages WHERE id='" . intval($this->id) . "'")) {
				$loaded = $loaded->fetch_assoc();
				$this->id = $loaded['id'];

				$this->title = $loaded['title'];
				$this->text = $loaded['text'];

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
			if ($save = $this->sql->prepare("UPDATE wolfvtc_pages SET (title=?, 'text'=?) WHERE id=?")) { // update page
				$save->bind_param("ssi", e($this->title), $this->text, intval($this->id));
				if ($save->execute) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_pages (title, 'text') VALUES (?, ?)")) { // add new page
				$save->bind_param("ss", e($this->title), $this->text);
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