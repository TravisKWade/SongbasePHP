<?php

	class Song {
		public $songID;
		public $name = "";
		public $composerID;
		public $dayWritten;
		public $monthWritten;
		public $yearWritten;
		public $userID;

		/********************
			Constructor
		*********************/
		public function __construct($arrayValues) {
			$this->setSongID($arrayValues['id']);
			$this->setName($arrayValues['name']);
			$this->setComposerID($arrayValues['composer_id']);
			$this->setYearWritten($arrayValues['year_written']);
			$this->setMonthWritten($arrayValues['month_written']);
			$this->setDayWritten($arrayValues['day_written']);
			$this->setUserID($arrayValues['user_id']);
		}

		/********************
			Setters
		*********************/
		public function setSongID($id) {
			$this->songID = $id;
		}

		public function setName($name) {
			$this->name = $name;
		}

		public function setComposerID($composerID) {
			$this->composerID = $composerID;
		}

		public function setUserID($userID) {
			$this->userID = $userID;
		}

		public function setYearWritten($year) {
			$this->yearWritten = $year;
		}

		public function setMonthWritten($month) {
			$this->monthWritten = $month;
		}

		public function setDayWritten($day) {
			$this->dayWritten = $day;
		}

		/********************
			Getters
		*********************/
		public function getSongID() {
			return $this->songID;
		}

		public function getName() {
			return $this->name;
		}

		public function getComposerID(){
			return $this->composerID;
		}

		public function getUserID(){
			return $this->userID;
		}

		public function getYearWritten(){
			return $this->yearWritten;
		}

		public function getMonthWritten(){
			return $this->monthWritten;
		}

		public function getDayWritten(){
			return $this->dayWritten;
		}

		public function getDateWritten() {
			if ($this->getDayWritten() != '') {
				return "{$this->getTextMonthForInt($this->getMonthWritten())} {$this->getDayWritten()}, {$this->getYearWritten()}";
			} else {
				return "{$this->getTextMonthForInt($this->getMonthWritten())} {$this->getYearWritten()}";
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