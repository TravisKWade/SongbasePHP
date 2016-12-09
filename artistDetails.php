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

	$db = new DataLayer();
	$orderError = "";

	$albumRS = $db->getAlbumForID($_SESSION['groupID'], $_GET['al']);
	$albumRow = $albumRS->fetch_assoc();
	$album = new Album($albumRow);

	$artRS = $db->getArtistForID($_SESSION['groupID'], $album->getArtistID());
	$artRow = $artRS->fetch_assoc();
	$artist = new Artist($artRow);

	$albumRecordingsRS = $db->getRecordingsForAlbumWithOrder($album->getAlbumID());

	if ($albumRecordingsRS == null) {
		$orderError = "** The order of the songs has not been set";
		$albumRecordingsRS = $db->getRecordingsForAlbum($album->getAlbumID());
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
		Songbase - ARTIST DETAILS
	</div>

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
		<li><a href="albums.php">Albums</a></li>
		<li><a href="composers.php">Composers</a></li>
	</ul>

	<? echo $album->getName() ?><br />
	<? echo $artist->getName() ?><br />
	<? echo $album->getYearReleased() ?>
	<br /><br />
	Album Songs
	<br />
	<?

		if ($albumRecordingsRS != null) {
			while($albumRecordingRow = $albumRecordingsRS->fetch_assoc()) {
				$recording = new Recording($albumRecordingRow);

				$songRS = $db->getSongForUser($_SESSION['userID'], $recording->getSongID());
				$songRow = $songRS->fetch_assoc();
				$song = new Song($songRow);

				echo "{$song->getName()}";
				echo "<br />";
			}
		}

		echo $orderError;
	?>
	<br /><br />
	<a href="editAlbum.php?al=<? echo $_GET['al'] ?>">edit album</a> <br />
	<a href="editAlbumSongOrder.php?al=<? echo $_GET['al'] ?>">edit album song order</a><br />
	** to edit the songs on the album, edit the recordings
</body>
</html>