<?php

	class Album {
		public $albumID;
		public $name;
		public $artistID;
		public $yearReleased;
		public $userID;

		/********************
			Constructor
		*********************/
		public function __construct($arrayValues) {
			$this->setAlbumID($arrayValues['id']);
			$this->setname($arrayValues['name']);
			$this->setArtistID($arrayValues['artist_id']);
			$this->setYearReleased($arrayValues['year_released']);
			$this->setUserID($arrayValues['user_id']);
		}

		/********************
			Print
		*********************/
		public function __toString() {
			$string = "Album ID: ".$this->albumID."<br />";
			$string = $string."Album Name: ".$this->name."<br />";
			$string = $string."Artist ID: ".$this->artistID."<br />";
			$string = $string."Year Released: ".$this->yearReleased."<br />";

			return $string;
		}

		/********************
			Setters
		*********************/
		public function setAlbumID($id) {
			$this->albumID = $id;
		}

		public function setName($name) {
			$this->name = $name;
		}

		public function setArtistID($artistID) {
			$this->artistID = $artistID;
		}

		public function setYearReleased($year) {
			$this->yearReleased = $year;
		}

		public function setUserID($userID) {
			$this->userID = $userID;
		}

		/********************
			Getters
		*********************/
		public function getAlbumID() {
			return $this->albumID;
		}

		public function getName() {
			return $this->name;
		}

		public function getArtistID() {
			return $this->artistID;
		}

		public function getYearReleased() {
			return $this->yearReleased;
		}

		public function getUserID(){
			return $this->userID;
		}
	}
?>