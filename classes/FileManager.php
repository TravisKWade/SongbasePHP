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

	public function updateFolderForAlbum($userID, $artistID, $artistName, $albumID, $oldName, $newName) {
		$artistPath = 'songs/'.$userID.'/'.str_replace(" ", "_", $artistName)."_".$artistID;
		$oldPath = $artistPath."/".str_replace(" ", "_", $oldName)."_".$albumID;
		$newPath = $artistPath."/".str_replace(" ", "_", $newName)."_".$albumID;

		if (!is_dir($oldPath)) {
			$this->createFolderForAlbum($userID, $artistID, $artistName, $albumID, $newName);
		} else {
			rename($oldPath, $newPath);
		}
	}

	/********************
		Recording
	*********************/

	public function uploadRecording($userID, $file, $basePath, $songName, $recordingID, $albumID, $albumName, $artistID, $artistName) {
		$artistPath = $basePath.'/songs/'.$userID.'/'.str_replace(" ", "_", $artistName)."_".$artistID;
		$path = $artistPath."/".str_replace(" ", "_", $albumName)."_".$albumID;
		$recordingName = str_replace(" ", "_", $songName)."_".$recordingID;
		$fullPath = $path."/".$recordingName.".mp3";

		if (!is_dir($path)) {
			$this->createFolderForAlbum($userID, $artistID, $artistName, $albumID, $albumName);
		}

		if (file_exists($fullPath)) {
			return 0;
		}

		move_uploaded_file($file, $fullPath);

		if ($_FILES['file']['error'] === UPLOAD_ERR_OK) { 
			return 1;
		} else { 
			$phpFileUploadErrors = array(
			    0 => 'There is no error, the file uploaded with success',
			    1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
			    2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
			    3 => 'The uploaded file was only partially uploaded',
			    4 => 'No file was uploaded',
			    6 => 'Missing a temporary folder',
			    7 => 'Failed to write file to disk.',
			    8 => 'A PHP extension stopped the file upload.',
			);
			return $phpFileUploadErrors($_FILES['file']['error']); 
		}
	}

	public function checkForExistingRecording($userID, $songName, $recordingID, $albumID, $albumName, $artistID, $artistName) {
		$artistPath = 'songs/'.$userID.'/'.str_replace(" ", "_", $artistName)."_".$artistID;
		$path = $artistPath."/".str_replace(" ", "_", $albumName)."_".$albumID;
		$recordingName = str_replace(" ", "_", $songName)."_".$recordingID;
		$fullPath = $path."/".$recordingName.".mp3";

		if (file_exists($fullPath)) {
			return 1;
		} 

		return 0;
	}
}

?>











