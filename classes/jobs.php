<?php

class job {
	public $id;

	public $userid;

	public $fromcity;
	public $tocity;

	public $cargo;

	public $distance;
	public $earnings;

	public $notes;

	public $fuelcosts;
	public $travelcosts;
	public $repaircosts;

	public $addedtime;

	public $approved;
	public $approvedby;
	public $approvedtime;

	public $division;

	private $sql;

	public function __construct($sql) {
		$this->sql = $sql;
	}

	public function load() {
		if (isset($this->id) && $this->id != "") {
			if (intval($this->id) != 0) { //explained this somewhere else, go look for it
				$load = $this->sql->query("SELECT * FROM wolfvtc_jobs WHERE id='" . intval($this->id) . "'");
				if ($load->num_rows == 1) {
					$load = $load->fetch_assoc();

					$this->userid = $load['userid'];

					$this->fromcity = $load['fromcity'];
					$this->tocity = $load['tocity'];

					$this->cargo = $load['cargo'];

					$this->distance = $load['distance'];
					$this->earnings = $load['earnings'];

					$this->notes = $load['notes'];

					$this->fuelcosts = $load['fuelcosts'];
					$this->travelcosts = $load['travelcosts'];
					$this->repaircosts = $load['repaircosts'];

					$this->addedtime = $load['addedtime'];

					$this->approved = $load['approved'];
					$this->approvedby = $load['approvedby'];
					$this->approvedtime = $load['approvedtime'];

					$this->division = $load['division'];
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
		if (isset($this->id) && $this->id != "") {
			if ($save = $this->sql->prepare("UPDATE wolfvtc_jobs SET 
					(userid=?, fromcity=?, tocity=?, cargo=?, distance=?, earnings=?, notes=?, fuelcosts=?, travelcosts=?, repaircosts=?, addedtime=?, approved=?, approvedby=?, approvedtime=?, division=?) WHERE id=?")) {
				$save->bind_param("iiiiiisiiisbisii", intval($this->userid), intval($this->fromcity), intval($this->tocity), intval($this->cargo), intval($this->distance), intval($this->earnings), e($this->notes), intval($this->fuelcosts), intval($this->travelcosts), intval($this->repaircosts), $this->addedtime, $this->approved, intval($this->approvedby), $this->approvedtime, intval($this->division), intval($this->id);
				if ($save->execute()) {
					return TRUE;
				} else {
					return FALSE;
				}
			} else {
				return FALSE;
			}
		} else {
			if ($save = $this->sql->prepare("INSERT INTO wolfvtc_jobs 
					(userid, fromcity, tocity, cargo, distance, earnings, notes, fuelcosts, travelcosts, repaircosts, addedtime, approved, approvedby, approvedtime, division)
					VALUES 
					(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)")) {
				$save->bind_param("iiiiiisiiisbisi", intval($this->userid), intval($this->fromcity), intval($this->tocity), intval($this->cargo), intval($this->distance), intval($this->earnings), e($this->notes), intval($this->fuelcosts), intval($this->travelcosts), intval($this->repaircosts), $this->addedtime, $this->approved, intval($this->approvedby), $this->approvedtime, intval($this->division));
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