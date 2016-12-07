<?php

	class Artist {
		public $artistID;
		public $name = "";
		public $userID;

		/********************
			Constructor
		*********************/
		public function __construct($arrayValues) {
			$this->setArtistID($arrayValues['id']);
			$this->setName($arrayValues['name']);
			$this->setUserID($arrayValues['user_id']);
		}

		/********************
			Setters
		*********************/
		public function setArtistID($id) {
			$this->songID = $id;
		}

		public function setName($name) {
			$this->name = $name;
		}

		public function setUserID($userID) {
			$this->userID = $userID;
		}

		/********************
			Getters
		*********************/
		public function getArtistID() {
			return $this->artistID;
		}

		public function getName() {
			return $this->name;
		}

		public function getUserID(){
			return $this->userID;
		}
	}
?>