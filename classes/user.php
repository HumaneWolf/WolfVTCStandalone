<?php

class user {
	public $by;

	public $id;

	public $username;
	public $email;

	public $password;
	public $passkey;

	public $membersince;

	public $division;
	public $divisionapproved;
	public $divisionadmin;

	public $adminusers;
	public $adminpages;
	public $admindivisions;
	public $adminnews;
	public $adminadmin;

	public $banned;
	public $bannedtime;
	public $bannedby;
	public $bannedreason;

	public $sessionstart;

	public $verified;
	public $mailpurpose;
	public $mailkey;

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
			$where .= "username LIKE ?";
		} elseif ($this->by == "email") {
			$where .= "email LIKE ?";
		}

		if ($stmt = $this->sql->prepare("SELECT id,
									username,
									email,
									password,
									passkey,
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
									sessionstart,
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
								$this->passkey,
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
								$this->sessionstart,
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
			$stmt = $this->sql->prepare("UPDATE wolfvtc_users SET (username=?,
									email=?,
									password=?,
									passkey=?,
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
									sessionstart=?,
									verified=?,
									mailpurpose=?,
									mailkey=?) 
									WHERE id=?");
			$stmt->bind_param('sssssibbbbbbbbsissbisi',
								e($this->username),
								e($this->email),
								$this->password,
								$this->passkey,
								e($this->membersince),
								intval($this->division),
								$this->divisionapproved,
								$this->divisionadmin,
								$this->adminusers,
								$this->adminpages,
								$this->admindivisions,
								$this->adminnews,
								$this->adminadmin,
								$this->banned,
								$this->bannedtime,
								intval($this->bannedby),
								e($this->bannedreason),
								e($this->sessionstart),
								$this->verified,
								intval($this->mailpurpose),
								$this->mailkey,
								intval($this->id));
			if ($stmt->execute()) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			$stmt = $this->sql->prepare("INSERT INTO wolfvtc_users (username,
									email,
									password,
									passkey,
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
									sessionstart,
									verified,
									mailpurpose,
									mailkey) 
									VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
			$stmt->bind_param('sssssibbbbbbbbsissbis',
								$this->username,
								$this->email,
								$this->password,
								$this->passkey,
								$this->membersince,
								intval($this->division),
								$this->divisionapproved,
								$this->divisionadmin,
								$this->adminusers,
								$this->adminpages,
								$this->admindivisions,
								$this->adminnews,
								$this->adminadmin,
								$this->banned,
								$this->bannedtime,
								intval($this->bannedby),
								$this->bannedreason,
								$this->sessionstart,
								$this->verified,
								intval($this->mailpurpose),
								$this->mailkey);
			if ($stmt->execute()) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	public function changePW($newpw) { //make a new passkey and encrypt pw.
		if (strlen($newpw) >= 6 && strlen($newpw) <= 150) {
			$newpwkey = randomString(50);
			$this->passkey = $newpwkey;
			$this->password = hash("sha512", $newpwkey . $newpw);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	public function checkPW($pwinput) { // check if pw input is correct compared to users pw.
		if (strlen($newpw) >= 6 && strlen($newpw) <= 150) {
			if ($this->password == hash("sha512", $passkey . $pwinput)) {
				return TRUE;
			} else {
				return FALSE;
			}
		} else {
			return FALSE;
		}
	}
}