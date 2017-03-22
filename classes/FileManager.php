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
		HELPERS
	*********************/

	public function getArtistPath($userID, $artistID, $artistName) {
		return 'songs/'.$userID.'/'.str_replace(" ", "_", $artistName)."_".$artistID;
	}

	public function getAlbumPath($userID, $artistID, $artistName, $albumID, $albumName) {
		return $this->getArtistPath($userID, $artistID, $artistName) . "/".str_replace(" ", "_", $albumName)."_".$albumID;
	}

	public function getRecordingPath($userID, $songName, $recordingID, $artistID, $artistName, $albumID, $albumName) {
		return $this->getAlbumPath($userID, $artistID, $artistName, $albumID, $albumName) . "/" . str_replace(" ", "_", $songName)."_".$recordingID.".mp3";
	}

	public function getAlbumImagePath ($userID, $artistID, $artistName, $albumID, $albumName) {
		return $this->getAlbumPath($userID, $artistID, $artistName, $albumID, $albumName)."/cover.png";
	}

   	/********************
		Artist
	*********************/

	public function createFolderForArtist($userID, $artistID, $artistName) {
		$this->checkUserFolder($userID);
		$path = $this->getArtistPath($userID, $artistID, $oldName);

		if (!is_dir($path)) {
		    mkdir($path, 0777, true);
		}
	}

	public function updateFolderForArtist($userID, $oldName, $newName, $artistID) {
		$path = $this->getArtistPath($userID, $artistID, $oldName);
		$newPath = $this->getArtistPath($userID, $artistID, $newName);

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
		$path = $this->getAlbumPath($userID, $artistID, $artistName, $albumID, $albumName);

		if (!is_dir($artistPath)) {
			$this->createFolderForArtist($userID, $artistID, $artistName);
		} 

		if (!is_dir($path)) {
			mkdir($path, 0777, true);
		}
	}

	public function updateFolderForAlbum($userID, $artistID, $artistName, $albumID, $oldName, $newName) {
		$oldPath = $this->getAlbumPath($userID, $artistID, $artistName, $albumID, $oldName);
		$newPath = $this->getAlbumPath($userID, $artistID, $artistName, $albumID, $newName);

		if (!is_dir($oldPath)) {
			$this->createFolderForAlbum($userID, $artistID, $artistName, $albumID, $newName);
		} else {
			rename($oldPath, $newPath);
		}
	}

	/********************
		Album Image
	*********************/

	public function getImagePathForAlbum($userID, $artistID, $artistName, $albumID, $albumName) {
		$imagePath = $this->getAlbumImagePath($userID, $artistID, $artistName, $albumID, $albumName);
		
		if(!file_exists($imagePath)) {
			return "0";
		} else {
			return $imagePath;
		}
	}

	public function uploadImageForAlbum($userID, $artistID, $artistName, $albumID, $albumName, $file) {
		$imagePath = $this->getAlbumImagePath($userID, $artistID, $artistName, $albumID, $albumName);

		if (file_exists($imagePath)) {
			return 0;
		}

		move_uploaded_file($file, $imagePath);

		if ($_FILES['file']['error'] === UPLOAD_ERR_OK) { 
			return "1";
		} else { 
			echo $_FILES['file']['error'];
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
			echo $phpFileUploadErrors[$_FILES['file']['error']]; 
		}
	
	}

	/********************
		Recording
	*********************/

	public function uploadRecording($userID, $file, $basePath, $songName, $recordingID, $albumID, $albumName, $artistID, $artistName) {
		$fullPath = $this->getRecordingPath($userID, $songName, $recordingID, $artistID, $artistName, $albumID, $albumName);

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
		$fullPath = $this->getRecordingPath($userID, $songName, $recordingID, $artistID, $artistName, $albumID, $albumName);

		if (file_exists($fullPath)) {
			return 1;
		} 

		return 0;
	}

	public function renameSongNameForRecording($userID, $songOldName, $songNewName, $recordingID, $albumID, $albumName, $artistID, $artistName) {
		$fullPath = $this->getRecordingPath($userID, $songOldName, $recordingID, $artistID, $artistName, $albumID, $albumName);
		$fullNewPath = $this->getRecordingPath($userID, $songNewName, $recordingID, $artistID, $artistName, $albumID, $albumName);

		if (file_exists($fullPath)) {
			rename($fullPath, $fullNewPath);
		} 
	}

	public function getURLForRecording($userID, $songName, $recordingID, $albumID, $albumName, $artistID, $artistName) {
		if($this->checkForExistingRecording($userID, $songName, $recordingID, $albumID, $albumName, $artistID, $artistName)) {
			$fullPath = $this->getRecordingPath($userID, $songName, $recordingID, $artistID, $artistName, $albumID, $albumName);

			return $fullPath;
		} else {
			return 0;
		}
	}
}

?>











