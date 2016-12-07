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

	$albumRS = $db->getAlbumForID($_SESSION['groupID'], $_GET['al']);
	$albumRow = $albumRS->fetch_assoc();
	$album = new Album($albumRow);

	$artRS = $db->getArtistForID($_SESSION['groupID'], $album->getArtistID());
	$artRow = $artRS->fetch_assoc();
	$artist = new Artist($artRow);
?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<script src="scripts/jquery.js"></script>
</head>
<body>
	User: <? echo $_SESSION['user']; ?>
	<form action="logout.php">
		<input type="submit" value="Logout" />
	</form>
	<div>
		Songbase
	</div>
	<ul>
		<li><a href="songs.php">Songs</a></li>
		<li><a href="artists.php">Artists</a></li>
		<li><a href="composers.php">Composers</a></li>
	</ul>

	<? echo $album->getName() ?><br />
	<? echo $artist->getName() ?>
	<br /><br />
	Album Songs
	<br />
	<?
		$albumSongRS = $db->getSongsForAlbum($album->getAlbumID());

		if ($albumSongRS != null) {
			while($albumSongRow = $albumSongRS->fetch_assoc()) {
				$recRS = $db->getRecordingForID($albumSongRow['recording_id']);
				$recRow = $recRS->fetch_assoc();
				$recording = new Recording($recRow);

				$songRS = $db->getSongForUser($_SESSION['userID'], $albumSongRow['song_id']);
				$songRow = $songRS->fetch_assoc();
				$song = new Song($songRow);

				echo "{$albumSongRow['ordinal']}. {$song->getName()}";
				echo "<br />";
			}
		}
	?>
	<br /><br />
	<a href="editAlbum.php?al=<? echo $_GET['al'] ?>">edit album</a>
</body>
</html>