<?php

class anno {
	public $id;

	public $divid;
	public $userid;

	public $datetime;

	public $title;
	public $text;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}


	public function load() {
		if (isset($this->id) && $id != "") {
			if ($load = $this->sql->query("SELECT * FROM wolfvtc_announcements WHERE id=" . intval($this->id))) {
				if ($load->num_rows == 1) {
					$load = $load->fetch_assoc();

					$this->divid = $load['divid'];
					$this->userid = $load['userid'];

					$this->datetime = $load['datetime'];

					$this->title = $load['title'];
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
			if ($save = $this->sql->prepare("UPDATE wolfvtc_announcements SET (divid=?, userid=?, datetime=?, title=?, 'text'=?) WHERE id=?")) {
				$save->bind_param("iisssi", intval($this->divid), intval($this->userid), e($this->datetime), e($this->title), $this->text, intval($this->id));
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
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_announcements (divid, userid, datetime, title, 'text') VALUES (?, ?, ?, ?, ?)")) {
				$save->bind_param("iisss", intval($this->divid), intval($this->userid), e($this->datetime), e($this->title), $this->text);
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