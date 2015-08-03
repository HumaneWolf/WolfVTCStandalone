<?php

class forum {
	public $id;

	public $divid;
	public $userid;

	public $datetime;

	public $text;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}


	public function load() {
		if (isset($this->id) && $this->id != "") {
			if ($load = $this->sql->query("SELECT * FROM wolfvtc_forum WHERE id=" . intval($this->id))) {
				if ($load->num_rows == 1) {
					$load = $load->fetch_assoc();

					$this->divid = $load['divid'];
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
			if ($save = $this->sql->prepare("UPDATE wolfvtc_forum SET (divid=?, userid=?, datetime=?, 'text'=?) WHERE id=?")) {
				$save->bind_param("iissi", intval($this->divid), intval($this->userid), e($this->datetime), e($this->text), intval($this->id));
				if ($save->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_forum (divid, userid, datetime, 'text') VALUES (?, ?, ?, ?)")) {
				$save->bind_param("iiss", intval($this->divid), intval($this->userid), e($this->datetime), e($this->text));
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