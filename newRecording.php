<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Song.php");
	include("classes/Recording.php");
	include("classes/Artist.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$error = "";
	$db = new DataLayer();

	if(!empty($_POST['submit'])){	
		$rs = $db->createRecordingForUser($_SESSION['groupID'], $_GET['song'], $_POST['artist'], $_POST['album'], $_POST['month'], $_POST['day'], $_POST['year']);
		
		if($rs != null) {
			header("location:songDetails.php?song={$_GET['song']}");
		} else {
			$error = "There was a problem creating the recording";
		}
		
	}

	$rs = $db->getSongForUser($_SESSION['groupID'], $_GET['song']);
	$row = $rs->fetch_assoc();
	$song = new Song($row);

	$artistArray = array();
	$artRS = $db->getArtistsForUser($_SESSION['groupID']);

	if ($artRS != null) {
		while($artRow = $artRS->fetch_assoc()) {
			$artist = new Artist($artRow);
			$artistArray[$artist->getArtistID()] = $artist;
		}
	}

	$albumArray = array();
	$alRS = $db->getAlbumsForUser($_SESSION['groupID']);

	if ($alRS != null) {
		while ($alRow = $alRS->fetch_assoc()) {
			$album = new Album($alRow);
			$albumArray[$album->getAlbumID()] = $album;
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
		Songbase - NEW RECORDING
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
	<? echo $error ?>
	<? echo $song->getName() ?>
	<div id="new_song_form">
		<form action="newRecording.php?song=<? echo $_GET['song'] ?>" method="post">
			<table>
				<tr>
					<td>Artist:</td>
					<td>
						<select name="artist">
						<?
							foreach($artistArray as $artist) {
								echo "<option value=\"{$artist->getArtistID()}\">{$artist->getName()}</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Album:</td>
					<td>
						<select name="album">
						<?
							foreach($albumArray as $album) {
								echo "<option value=\"{$album->getAlbumID()}\">{$album->getName()}</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Month Recorded:</td>
					<td><input type="text" name="month" /></td>
				</tr>
				<tr>
					<td>Day Recorded:</td>
					<td><input type="text" name="day" /></td>
				</tr>
				<tr>
					<td>Year Recorded</td>
					<td><input type="text" name="year" /></td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Add New Recording">
		</form>
	</div>

	<a href="newAlbum.php">New Album</a> <br /><br />
	<a href="newArtist.php">New Artist</a>
</body>
</html>