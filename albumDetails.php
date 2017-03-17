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
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/album.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Albums</div>
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
	
	<div class="albumTitle">
		<? echo $album->getName() ?>
	</div>
	<div class="artistTitle">
		<? echo $artist->getName() ?>
	</div>
	<div class="albumReleaseDate">
		<? echo $album->getYearReleased() ?>
	</div>

	<div class="albumContent">
		<h2>Album Songs</h2>
		<?
			if ($albumRecordingsRS != null) {
				while($albumRecordingRow = $albumRecordingsRS->fetch_assoc()) {
					$recording = new Recording($albumRecordingRow);

					$songRS = $db->getSongForUser($_SESSION['userID'], $recording->getSongID());
					$songRow = $songRS->fetch_assoc();
					$song = new Song($songRow);

					echo "<a href='songDetails.php?song={$song->getSongID()}'>{$song->getName()}</a>";
					echo "<br />";
				}
			}

			echo "<div class='notes'>{$orderError}</div>";
		?>
	</div>
	<div class="options">
		<div class="editAlbum">
			<form action="editAlbum.php">
				<input type="hidden" name="al" value="<? echo $_GET['al'] ?>" />
		    	<input type="submit" value="Edit Album" class="songbaseButton" />
			</form>
		</div>
		<div class="editAlbumOrder">
			<form action="editAlbumSongOrder.php">
				<input type="hidden" name="al" value="<? echo $_GET['al'] ?>" />
		    	<input type="submit" value="Edit Album Song Order" class="songbaseButton" />
			</form>
		</div>
	</div>
	<div class="notes">** to edit the songs on the album, edit the recordings</div>
</body>
</html>