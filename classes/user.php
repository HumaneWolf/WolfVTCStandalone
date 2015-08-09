<?php

class user {
	public $by;

	public $id;

	public $username;
	public $email;

	public $password;

	public $membersince;

	public $division = 0;
	public $divisionapproved = FALSE;
	public $divisionadmin = FALSE;

	public $adminusers = FALSE;
	public $adminpages = FALSE;
	public $admindivisions = FALSE;
	public $adminnews = FALSE;
	public $adminadmin = FALSE;

	public $banned = FALSE;
	public $bannedtime = "";
	public $bannedby = 0;
	public $bannedreason = "";

	public $verified = FALSE;
	public $mailpurpose = 0;
	public $mailkey = "";

	private $sql;

	public function __construct ($sql, $by, $value) {
		$this->sql = $sql;

		if ($by == "id") {
			$this->id = $value;
		} elseif ($by == "username") {
			$this->username = $value;
		} elseif ($by == "email") {
			$this->email = $value;
		}
		$this->by = $by;
	}

	public function load() {
		$where = "WHERE ";
		if ($this->by == "id") {
			$where .= "id=?";
		} elseif ($this->by == "username") {
			$where .= "username=?";
		} elseif ($this->by == "email") {
			$where .= "email=?";
		}

		if ($stmt = $this->sql->prepare("SELECT id,
									username,
									email,
									password,
									membersince,
									division,
									divisionapproved,
									divisionadmin,
									adminusers,
									adminpages,
									admindivisions,
									adminnews,
									adminadmin,
									banned,
									bannedtime,
									bannedby,
									bannedreason,
									verified,
									mailpurpose,
									mailkey FROM wolfvtc_users " . $where)) {
			if ($this->by == "id") {
				$stmt->bind_param('i', intval($this->id));
			} elseif ($this->by == "username") {
				$stmt->bind_param('s', e($this->username));
			} elseif ($this->by == "email") {
				$stmt->bind_param('s', e($this->email));
			}
			$stmt->execute();
			
			$stmt->store_result();
			$stmt->bind_result($this->id,
								$this->username,
								$this->email,
								$this->password,
								$this->membersince,
								$this->division,
								$this->divisionapproved,
								$this->divisionadmin,
								$this->adminusers,
								$this->adminpages,
								$this->admindivisions,
								$this->adminnews,
								$this->adminadmin,
								$this->banned,
								$this->bannedtime,
								$this->bannedby,
								$this->bannedreason,
								$this->verified,
								$this->mailpurpose,
								$this->mailkey);

			$stmt->fetch();
	       	if ($stmt->num_rows == 1) { //if user exists, say it loaded. Else, say it didn't.
	       		return TRUE;
	       	} else {
	       		return FALSE;
	        }
	        $stmt->close();
	   	} else {
	   		return FALSE;
	   	}
	}

	public function save() {
		if (isset($this->id) && $this->id != "") {
			if ($stmt = $this->sql->prepare("UPDATE wolfvtc_users SET username=?,
									email=?,
									password=?,
									membersince=?,
									division=?,
									divisionapproved=?,
									divisionadmin=?,
									adminusers=?,
									adminpages=?,
									admindivisions=?,
									adminnews=?,
									adminadmin=?,
									banned=?,
									bannedtime=?,
									bannedby=?,
									bannedreason=?,
									verified=?,
									mailpurpose=?,
									mailkey=? 
									WHERE id=?")) {
				$stmt->bind_param('ssssiiiiiiiiisisiisi',
									e($this->username),
									e($this->email),
									$this->password,
									$this->membersince,
									intval($this->division),
									intval($this->divisionapproved),
									intval($this->divisionadmin),
									intval($this->adminusers),
									intval($this->adminpages),
									intval($this->admindivisions),
									intval($this->adminnews),
									intval($this->adminadmin),
									intval($this->banned),
									$this->bannedtime,
									intval($this->bannedby),
									e($this->bannedreason),
									intval($this->verified),
									intval($this->mailpurpose),
									e($this->mailkey),
									intval($this->id));
				if ($stmt->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			if ($stmt = $this->sql->prepare("INSERT INTO wolfvtc_users (username,
									email,
									password,
									membersince,
									division,
									divisionapproved,
									divisionadmin,
									adminusers,
									adminpages,
									admindivisions,
									adminnews,
									adminadmin,
									banned,
									bannedtime,
									bannedby,
									bannedreason,
									verified,
									mailpurpose,
									mailkey) 
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
				$stmt->bind_param('ssssiiiiiiiiisisiis',
									e($this->username),
									e($this->email),
									$this->password,
									$this->membersince,
									intval($this->division),
									intval($this->divisionapproved),
									intval($this->divisionadmin),
									intval($this->adminusers),
									intval($this->adminpages),
									intval($this->admindivisions),
									intval($this->adminnews),
									intval($this->adminadmin),
									intval($this->banned),
									$this->bannedtime,
									intval($this->bannedby),
									e($this->bannedreason),
									intval($this->verified),
									intval($this->mailpurpose),
									e($this->mailkey));
				if ($stmt->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		}
	}

	public function changePW($newpw) { //make a new passkey and encrypt pw.
		if (strlen($newpw) >= 6 && strlen($newpw) <= 150) {
			$this->password = password_hash($newpw, PASSWORD_DEFAULT);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkPW($pwinput) { // check if pw input is correct compared to users pw.
		if (strlen($pwinput) >= 6 && strlen($pwinput) <= 150) {
			if (password_verify($pwinput , $this->password)) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}

	public function logLogin() {
		if (isset($this->id)) {
			if ($log = $this->sql->prepare("INSERT INTO wolfvtc_logins (userid, datetime, ip) VALUES (?, ?, ?)")) {
				$log->bind_param("iss", $this->id, currentTime(), $_SERVER['REMOTE_ADDR']);
				if ($log->execute()) {
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
}