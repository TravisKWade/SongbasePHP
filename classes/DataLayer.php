<?php

class DataLayer {
	private $db;

	/********************
		Constructor
	*********************/
	public function __construct() {
		//$this->$db = mysql_connect('branvisc.ipowermysql.com','kitawolf','marshal72');
		//$this->db = mysql_connect('localhost','root','kitawolf');

		//$this->db = new mysqli('branvisc.ipowermysql.com','kitawolf','marshal72', 'trail_guide');
		$this->db = new mysqli('localhost','root','kitawolf', 'songbase');

		// Check connection
		if ($this->db->connect_error) {
		    die("Connection failed: " . $conn->connect_error);
		} 
	}

	function __destruct() {
       //mysql_close($this->db);
		mysqli_close($this->db);
   	}

   	/********************
		User functions
	*********************/

	public function loginUser($email, $password) {
		$sql = "select * from users where username='{$email}' and password='{$password}' and active=1";
		$rs = $this->db->query($sql);
		echo $sql;
		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	/********************
		Song functions
	*********************/

	public function getAllSongsForUser($userID) {
		$sql = "select * from songs where user_id = {$userID} order by name";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getSongForUser($userID, $songID) {
		$sql = "select * from songs where user_id = {$userID} and id = {$songID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getSongsForAlbum($albumID) {
		$sql = "select * from album_song_order where album_id = {$albumID} order by ordinal";
		$rs = $this->db->query($sql);
		
		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function createSongForUser($userID, $name, $composerID, $month, $day, $year) {
		$sql = "insert into songs (user_id, name, composer_id, month_written, day_written, year_written) values ({$userID}, '{$name}', {$composerID}, {$month}, {$day}, {$year})";
		$rs = $this->db->query($sql);
	
		echo $rs;
		if ($rs != null) {
			return $rs;
		}
			
		return;
	}

	/*************************
		Composer functions
	**************************/

	public function getComposersForUser($userID) {
		$sql = "select * from composers where user_id = {$userID} order by name";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getComposerForID($userID, $composerID) {
		$sql = "select * from composers where user_id = {$userID} and id = {$composerID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	/*************************
		Recordings functions
	**************************/

	public function getRecordingsForSong($songID) {
		$sql = "select * from recordings where song_id = {$songID} order by year_recorded, month_recorded, day_recorded";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getRecordingForID($recordingID) {
		$sql = "select * from recordings where id = {$recordingID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	/*************************
		Artist functions
	**************************/

	public function getArtistForID($userID, $artistID) {
		$sql = "select * from artists where user_id = {$userID} and id = {$artistID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getArtistsForUser($userID) {
		$sql = "select * from artists where user_id = {$userID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	/*************************
		Album functions
	**************************/

	public function getAlbumForID($userID, $albumID) {
		$sql = "select * from albums where user_id = {$userID} and id = {$albumID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

}

?>











