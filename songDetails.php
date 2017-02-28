<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Song.php");
	include("classes/Composer.php");
	include("classes/Recording.php");
	include("classes/Artist.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getSongForUser($_SESSION['groupID'], $_GET['song']);
	$row = $rs->fetch_assoc();
	$song = new Song($row);

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
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div>
		Songbase - SONG DETAILS
	</div>

	User: <? echo $_SESSION['user']; ?>
	<form action="logout.php">
		<input type="submit" value="Logout" />
	</form>
	<ul>
		<li><a href="songs.php">Songs</a></li>
		<li><a href="artists.php">Artists</a></li>
		<li><a href="albums.php">Albums</a></li>
		<li><a href="composers.php">Composers</a></li>
	</ul>

	<? echo $song->getName() ?>
	<br /><br />
	Song Details
	<table>
		<tr>
			<td>Composer</td>
			<td><? echo $composer->getName() ?></td>
		</tr>
		<tr>
			<td>Date Written</td>
			<td><? echo $song->getDateWritten() ?></td>
		</tr>
	</table>

	<br /><br />
	Recordings <br />

	<table>
		<tr>
			<td></td>
			<td>Artist</td>
			<td>Album</td>
			<td>Date</td>
		</tr>
	<?
		foreach($recordingArray as $recording) {
			$artRS = $db->getArtistForID($recording->getUserID(), $recording->getArtistID());

			$artRow = $artRS->fetch_assoc();
			$artist = new Artist($artRow);

			$alRS = $db->getAlbumForID($recording->getUserID(), $recording->getAlbumID());

			$alRow = $alRS->fetch_assoc();
			$album = new Album($alRow);
	
			echo "<tr>";
			echo "<td><a href='editRecording.php?rec={$recording->getRecordingID()}'> EDIT </a></td>";
			echo "<td><a href='artistDetails.php?art={$artist->getArtistID()}'> {$artist->getName()} </a></td>";
			echo "<td><a href='albumDetails.php?al={$album->getAlbumID()}'> {$album->getName()} </a></td>";
			echo "<td>{$recording->getDateRecorded()}</td>";
			echo "</tr>";
		} 
	?>
	</table>
	<br /><br />
	<a href="newRecording.php?song=<? echo $_GET['song'] ?>"> Add Recording </a><br /><br />
</body>
</html>