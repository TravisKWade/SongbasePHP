<?php

	class Composer {
		public $name = "";
		public $composerID;
		public $userID;

		/********************
			Constructor
		*********************/
		public function __construct($arrayValues) {
			$this->setName($arrayValues['name']);
			$this->setComposerID($arrayValues['id']);
			$this->setUserID($arrayValues['user_id']);
		}

		/********************
			Setters
		*********************/
		public function setName($name) {
			$this->name = $name;
		}

		public function setComposerID($composerID) {
			$this->composerID = $composerID;
		}

		public function setUserID($userID) {
			$this->userID = $userID;
		}

		/********************
			Getters
		*********************/
		public function getName() {
			return $this->name;
		}

		public function getComposerID(){
			return $this->composerID;
		}

		public function getUserID(){
			return $this->userID;
		}
	}
?>