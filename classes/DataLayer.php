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

	public function createSongForUser($userID, $name, $composerID, $month, $day, $year) {
		$sql = "insert into songs (user_id, name, composer_id, month_written, day_written, year_written) values ({$userID}, '{$name}', {$composerID}, {$month}, {$day}, {$year})";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $rs;
		}
			
		return;
	}

	/***************************
		Song Lyric functions
	****************************/

	public function getLyricsForSong($songID) {
		$sql = "select * from lyrics where song_id = {$songID}";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function createLyricsForSong($userID, $songID, $lyrics) {
		$sql = "insert into lyrics (user_id, song_id, lyrics) values ({$userID}, {$songID}, '{$lyrics}')";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $rs;
		}
			
		return;
	}

	public function updateLyricsForSong($userID, $songID, $lyrics) {
		$sql = "update lyrics set lyrics = '{$lyrics}' where song_id = {$songID} and user_id = {$userID}";
		$rs = $this->db->query($sql);

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

	public function getRecordingsForAlbum($albumID) {
		$sql = "select * from recordings where album_id = {$albumID}";
		$rs = $this->db->query($sql);
		
		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getLastRecordingIDForSong($songID, $artistID, $albumID) {
		$sql = "select id from recordings where song_id = {$songID} and artist_id = {$artistID} and album_id = {$albumID} order by timestamp desc";
		$rs = $this->db->query($sql);
		
		if ($rs->num_rows > 0) {
			$row = $rs->fetch_assoc();
			return $row['id'];
		}
			
		return;
	}

	public function getRecordingsForAlbumWithOrder($albumID) {
		$sql = "select distinct recordings.id, recordings.song_id, recordings.artist_id, recordings.album_id, recordings.day_recorded, recordings.month_recorded, recordings.year_recorded, recordings.user_id, album_song_order.ordinal from recordings inner join album_song_order on recordings.id = album_song_order.recording_id where recordings.album_id = {$albumID} order by album_song_order.ordinal";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function createRecordingForUser($userID, $songID, $artistID, $albumID, $month, $day, $year) {
		$insertSQL = "insert into recordings (user_id, song_id, artist_id, album_id";
		$valuesSQL = ") values ({$userID}, {$songID}, {$artistID}, {$albumID}";

		if ($month != null) {
			$insertSQL = $insertSQL .  ", month_recorded";
			$valuesSQL = $valuesSQL . ", {$month}";
		}

		if ($day != null) {
			$insertSQL = $insertSQL .  ", day_recorded";
			$valuesSQL = $valuesSQL . ", {$day}";
		}

		if ($year != null) {
			$insertSQL = $insertSQL .  ", year_recorded";
			$valuesSQL = $valuesSQL . ", {$year}";
		}

		$valuesSQL = $valuesSQL . ")";
		$sql = $insertSQL . $valuesSQL;
		
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $rs;
		}
			
		return;
	}

	public function updateRecordingForUser($userID, $recordingID, $artistID, $albumID, $month, $day, $year) {
		$sql = "update recordings set artist_id = {$artistID}, album_id = {$albumID}, day_recorded = {$day}, year_recorded = {$year}, month_recorded = {$month} where id = {$recordingID} and user_id = {$userID}";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $rs;
		}
			
		return;
	}

	public function updateRecordingOrderForAlbum($userID, $recordingArray) {
		$index = 1;
		foreach($recordingArray as $recording) {
			if (!$this->checkRecordingOrderForAlbum($recording->getRecordingID(), $recording->getAlbumID())){
				$sql = "insert into album_song_order (album_id, recording_id, song_id, ordinal, user_id) values ({$recording->getAlbumID()}, {$recording->getRecordingID()}, {$recording->getSongID()}, {$index}, {$userID})";
			} else {
				$sql = "update album_song_order set ordinal = {$index} where recording_id = {$recording->getRecordingID()} and album_id = {$recording->getAlbumID()}";
			}

			$rs = $this->db->query($sql);
			$index = $index + 1;
		}
	}

	public function checkRecordingOrderForAlbum($recordingID, $albumID) {
		$sql = "select * from album_song_order where recording_id = {$recordingID} and album_id = {$albumID}";
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

	public function getArtistIDForName($userID, $artistName) {
		$sql = "select id from artists where user_id = {$userID} and name = '{$artistName}'";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			$artRow = $rs->fetch_assoc();
			return $artRow['id'];
		}
			
		return;
	}

	public function createArtistForUser($userID, $artistName) {
		$sql = "insert into artists (user_id, name) values ({$userID}, '{$artistName}')";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $this->getArtistIDForName($userID, $artistName);
		}
			
		return;
	}

	public function updateArtistForUser($userID, $artistName, $artistID) {
		$sql = "update artists set name = '{$artistName}' where id = {$artistID} and user_id = {$userID}";
		$rs = $this->db->query($sql);

		if ($rs != null) {
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

	public function getAlbumsForUser($userID) {
		$sql = "select * from albums where user_id = {$userID} order by name";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getAlbumsForArtist($artistID) {
		$sql = "select * from albums where artist_id = {$artistID} order by year_released";
		$rs = $this->db->query($sql);

		if ($rs->num_rows > 0) {
			return $rs;
		}
			
		return;
	}

	public function getAlbumIDForName($userID, $albumName, $artistID) {
		$sql = "select id from albums where user_id = {$userID} and name = '{$albumName}' and artist_id = {$artistID}";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			$albumRow = $rs->fetch_assoc();
			return $albumRow['id'];
		}
			
		return;
	}

	public function createAlbumForUser($userID, $artistID, $name, $year) {
		$sql = "insert into albums (user_id, artist_id, name, year_released) values ({$userID}, {$artistID}, '{$name}', {$year})";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $this->getAlbumIDForName($userID, $name, $artistID);
		}
			
		return;
	}

	public function updateAlbumForID($albumID, $artistID, $name, $year) {
		$sql = "update albums set artist_id = {$artistID}, name = '{$name}', year_released = {$year} where id = {$albumID}";
		$rs = $this->db->query($sql);

		if ($rs != null) {
			return $rs;
		}
			
		return;
	}

}

?>











