<?php

class comment {
	public $id;

	public $annoid;
	public $userid;

	public $text;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}

	public function load() {
		if (isset($this->id) && $id != "") {
			if ($load = $this->sql->query("SELECT * FROM wolfvtc_annocomments WHERE id=" . intval($this->id))) {
				if ($load->num_rows == 1) {
					$load = $load->fetch_assoc();

					$this->annoid = $load['annoid'];
					$this->userid = $load['userid'];

					$this->datetime = $load['datetime'];

					$this->text = $load['text'];

					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function save() {
		if (isset($this->id) && $id != "") {
			if ($save = $this->sql->prepare("UPDATE wolfvtc_annocomments SET (annoid=?, userid=?, datetime=?, 'text'=?) WHERE id=?")) {
				$save->bind_param("iissi", intval($this->annoid), intval($this->userid), e($this->datetime), $this->text, intval($this->id));
				if ($save->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
				} else {
					return FALSE;
				}
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_annocomments (annoid, userid, datetime, 'text') VALUES (?, ?, ?, ?)")) {
				$save->bind_param("iiss", intval($this->annoid), intval($this->userid), e($this->datetime), $this->text);
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