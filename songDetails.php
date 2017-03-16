<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/FileManager.php");
	include("classes/Song.php");
	include("classes/Composer.php");
	include("classes/Recording.php");
	include("classes/Artist.php");
	include("classes/Album.php");
	include("classes/SongLyrics.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();
	$fm = new FileManager();

	$rs = $db->getSongForUser($_SESSION['groupID'], $_GET['song']);
	$row = $rs->fetch_assoc();
	$song = new Song($row);

	$lyricsRS = $db->getLyricsForSong($song->getSongID());
	
	if ($lyricsRS != null) {
		$lyricRow = $lyricsRS->fetch_assoc();
		$lyrics = new SongLyrics($lyricRow);
	} else {
		$lyrics = null;
	}

	$compRS = $db->getComposerForID($_SESSION['groupID'], $song->getComposerID());
	$compRow = $compRS->fetch_assoc();
	$composer = new Composer($compRow);

	$recordingArrayArray = array();
	$recRS = $db->getRecordingsForSong($song->getSongID());

	if ($recRS != null) {
		while($recRow = $recRS->fetch_assoc()) {
			$recording = new Recording($recRow);
			$recordingArray[$recording->getRecordingID()] = $recording;
		}
	}

?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/songDetails.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Song Details</div>
		<div class="logout">
			<form action="logout.php">
				<? echo $_SESSION['user']; ?>&nbsp;&nbsp;&nbsp;
				<input type="submit" value="Log Out" class="logoutButton"/>
			</form>
		</div>
	</div>
	<div class="menu">
		<ul>
			<li><a href="songs.php">Songs</a></li>
			<li><a href="artists.php">Artists</a></li>
			<li><a href="albums.php">Albums</a></li>
			<li><a href="composers.php">Composers</a></li>
		</ul>
	</div>

	<div class="songTitle">
		<? echo $song->getName() ?>
	</div>
	<div class="songDetails">
		<h2>Song Details</h2>
		<table>
			<tr>
				<td class="tableHeader">Composer:</td>
				<td class="tableDetails"><? echo $composer->getName() ?></td>
			</tr>
			<tr>
				<td class="tableHeader">Date Written:</td>
				<td class="tableDetails"><? echo $song->getDateWritten() ?></td>
			</tr>
		</table>
	</div>
	<div class="options">
		<div class="editSong">
			<form action="editSong.php">
				<input type="hidden" name="song" value="<? echo $song->getSongID() ?>" />
		    	<input type="submit" value="Edit Song" class="songbaseButton" />
			</form>
		</div>
		<div class="songLyrics">
			<?
			if($lyrics != null) {
				echo "<form action='lyrics.php'><input type='hidden' name='song' value='{$song->getSongID()}' /><input type='submit' value='Song Lyrics' class='songbaseButton' /></form>";
			} else {
				echo "<form action='newLyrics.php'><input type='hidden' name='song' value='{$song->getSongID()}' /><input type='submit' value='Add Song Lyrics' class='songbaseButton' /></form>";
			}
			?>
		</div>
	</div>
	<?

		if(count($recordingArray) > 0) {
	?>
	<div class="recordingDetails">
		<h2>Recordings </h2>

		<table>
			<tr>
				<td></td>
				<td class="tableHeader">Artist</td>
				<td class="tableHeader">Album</td>
				<td class="tableHeader">Date</td>
				<td></td>
			</tr>
	<?

	
			foreach($recordingArray as $recording) {
				$artRS = $db->getArtistForID($recording->getUserID(), $recording->getArtistID());

				$artRow = $artRS->fetch_assoc();
				$artist = new Artist($artRow);

				$alRS = $db->getAlbumForID($recording->getUserID(), $recording->getAlbumID());

				$alRow = $alRS->fetch_assoc();
				$album = new Album($alRow);
				
				$mp3Path = $fm->getURLForRecording($_SESSION['groupID'], $song->getName(), $recording->getRecordingID(), $album->getAlbumID(), $album->getName(), $artist->getArtistID(), $artist->getName());

				if ($mp3Path != '0') {
					$audio = "<td><audio controls><source src='{$mp3Path}' type='audio/mpeg'>Your browser does not support the audio element.</audio></td>";
				} else {
					$audio = "<td></td>";
				}

				echo "<tr>";
				echo "<td>";
				echo "<form action='editRecording.php'>";
				echo "<input type='hidden' name='rec' value='{$song->getSongID()}' />";
				echo "<input type='submit' value='' class='editButton' /></form>";
				echo "</td>";
				echo "<td><a href='artistDetails.php?art={$artist->getArtistID()}'> {$artist->getName()} </a></td>";
				echo "<td><a href='albumDetails.php?al={$album->getAlbumID()}'> {$album->getName()} </a></td>";
				echo "<td>{$recording->getDateRecorded()}</td>";
				echo $audio;
				echo "</tr>";
			} 
	
	?>
		</table>

	<?
		}
	?>
	</div>
	
	<div class="newRecording">
		<form action="newRecording.php">
			<input type="hidden" name="song" value="<? echo $song->getSongID() ?>" />
	    	<input type="submit" value="Add New Recording" class="songbaseButton" />
		</form>
	</div>

</body>
</html>