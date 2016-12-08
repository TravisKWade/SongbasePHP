<?php

	class Recording {
		public $recordingID;
		public $songID;
		public $artistID;
		public $albumID;
		public $dayRecorded;
		public $monthRecorded;
		public $yearRecorded;
		public $ordinal;
		public $userID;

		/********************
			Constructor
		*********************/
		public function __construct($arrayValues) {
			$this->setRecordingID($arrayValues['id']);
			$this->setSongID($arrayValues['song_id']);
			$this->setArtistID($arrayValues['artist_id']);
			$this->setAlbumID($arrayValues['album_id']);
			$this->setDayRecorded($arrayValues['day_recorded']);
			$this->setMonthRecorded($arrayValues['month_recorded']);
			$this->setYearRecorded($arrayValues['year_recorded']);
			$this->setOrdinal($arrayValues['ordinal']);
			$this->setUserID($arrayValues['user_id']);
		}

		/********************
			Setters
		*********************/
		public function setRecordingID($id) {
			$this->recordingID = $id;
		}

		public function setSongID($songID) {
			$this->songID = $songID;
		}

		public function setArtistID($artistID) {
			$this->artistID = $artistID;
		}

		public function setAlbumID($albumID) {
			$this->albumID = $albumID;
		}

		public function setDayRecorded($day) {
			$this->dayRecorded = $day;
		}

		public function setMonthRecorded($month) {
			$this->monthRecorded = $month;
		}

		public function setYearRecorded($year) {
			$this->yearRecorded = $year;
		}

		public function setOrdinal($ordinal) {
			$this->ordinal = $ordinal;
		}

		public function setUserID($userID) {
			$this->userID = $userID;
		}

		/********************
			Getters
		*********************/
		public function getRecordingID() {
			return $this->recordingID;
		}

		public function getSongID() {
			return $this->songID;
		}

		public function getArtistID() {
			return $this->artistID;
		}

		public function getAlbumID() {
			return $this->albumID;
		}

		public function getDayRecorded() {
			return $this->dayRecorded;
		}

		public function getMonthRecorded() {
			return $this->monthRecorded;
		}

		public function getYearRecorded() {
			return $this->yearRecorded;
		}

		public function getOrdinal() {
			return $this->ordinal;
		}

		public function getUserID(){
			return $this->userID;
		}

		public function getDateRecorded() {
			if ($this->getDayRecorded() != '') {
				return "{$this->getTextMonthForInt($this->getMonthRecorded())} {$this->getDayRecorded()}, {$this->getYearRecorded()}";
			} else {
				return "{$this->getTextMonthForInt($this->getMonthRecorded())} {$this->getYearRecorded()}";
			}
		}

		public function getTextMonthForInt($month) {
			if ($month == 1) {
				return "January";
			} else if ($month == 2) {
				return "Febuary";
			} else if ($month == 3) {
				return "March";
			} else if ($month == 4) {
				return "April";
			} else if ($month == 5) {
				return "May";
			} else if ($month == 6) {
				return "June";
			} else if ($month == 7) {
				return "July";
			} else if ($month == 8) {
				return "August";
			} else if ($month == 9) {
				return "September";
			} else if ($month == 10) {
				return "October";
			} else if ($month == 11) {
				return "November";
			} else if ($month == 12) {
				return "December";
			}
		}
	}
?>