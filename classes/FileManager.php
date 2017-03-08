<?php

class FileManager {

	/********************
		Constructor
	*********************/
	public function __construct() {

	}

	function __destruct() {
  
   	}

	/********************
		User
	*********************/

	private function checkUserFolder($userID) {
		if (!is_dir('songs/'.$userID)) {
		    mkdir('songs/'.$userID, 0777, true);
		}
	}

   	/********************
		Artist
	*********************/

	public function createFolderForArtist($userID, $artistID, $artistName) {
		$this->checkUserFolder($userID);
		$name = str_replace(" ", "_", $artistName) ."_{$artistID}";
		$path = 'songs/'.$userID.'/'.$name;

		if (!is_dir($path)) {
		    mkdir($path, 0777, true);
		}
	}

	public function updateFolderForArtist($userID, $oldName, $newName, $artistID) {

		$path = 'songs/'.$userID.'/'.str_replace(" ", "_", $oldName)."_".$artistID;
		$newPath = 'songs/'.$userID.'/'.str_replace(" ", "_", $newName)."_".$artistID;

		if (!is_dir($path)) {
			$this->createFolderForArtist($userID, $artistID, $newName);
		} else {
			rename($path, $newPath);
		}
	}

	/********************
		Album
	*********************/

	public function createFolderForAlbum($userID, $artistID, $artistName, $albumID, $albumName) {
		$artistPath = 'songs/'.$userID.'/'.str_replace(" ", "_", $artistName)."_".$artistID;
		$path = $artistPath."/".str_replace(" ", "_", $albumName)."_".$albumID;

		if (!is_dir($artistPath)) {
			$this->createFolderForArtist($userID, $artistID, $artistName);
		} 

		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
	}
}

?>











