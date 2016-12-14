<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Artist.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();
	$albumArray = array();

	$artistRS = $db->getArtistForID($_SESSION['groupID'], $_GET['art']);
	$artistRow = $artistRS->fetch_assoc();
	$artist = new Artist($artistRow);

	$alRS = $db->getAlbumsForArtist($_GET['art']);

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
	<br /><br />
	<? echo $artist->getName() ?><br /><br />
	Albums<br />
	<? 
		foreach($albumArray as $album) {
			echo "<a href='albumDetails.php?al={$album->getAlbumID()}'>{$album->getName()}</a>";
			if ($album->getYearReleased() != "") {
				echo " (" . $album->getYearReleased() . ")";
			}
			echo "<br />";
		}
	?>
	<br /><br />
	<? echo "<a href='editArtist.php?art={$artist->getArtistID()}''>Edit Artist - {$artist->getName()}</a>" ; ?>	
	<br /><br />
	<a href="newAlbum.php">New Album</a> <br /><br />

</body>
</html>