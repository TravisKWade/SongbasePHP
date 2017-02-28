<?php

	class SongLyrics {
		public $lyricID;
		public $songID;
		public $lyrics;
		public $userID;

		/********************
			Constructor
		*********************/
		public function __construct($arrayValues) {
			$this->setLyricID($arrayValues['id']);
			$this->setSongID($arrayValues['song_id']);
			$this->setLyrics($arrayValues['lyrics']);
			$this->setUserID($arrayValues['user_id']);
		}

		/********************
			Setters
		*********************/
		public function setLyricID($id) {
			$this->lyricID = $id;
		}

		public function setSongID($id) {
			$this->songID = $id;
		}

		public function setLyrics($lyrics) {
			$this->lyrics = $lyrics;
		}

		public function setUserID($userID) {
			$this->userID = $userID;
		}


		/********************
			Getters
		*********************/
		public function getLyricID() {
			return $this->lyricID;
		}

		public function getSongID() {
			return $this->songID;
		}

		public function getLyrics() {
			return $this->lyrics;
		}

		public function getUserID(){
			return $this->userID;
		}

	}
?>