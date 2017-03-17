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
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/artist.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Artist Details</div>
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

	<div class="artistTitle">
		<? echo $artist->getName() ?>
	</div>
	<div class="artistContent">
		<h2>Albums</h2>
		<? 
			foreach($albumArray as $album) {
				echo "<a href='albumDetails.php?al={$album->getAlbumID()}'>{$album->getName()}</a>";
				if ($album->getYearReleased() != "") {
					echo " (" . $album->getYearReleased() . ")";
				}
				echo "<br />";
			}
		?>
	</div>
	<div class="options">
		<div class="newAlbum">
			<form action="newAlbum.php">
				<input type="submit" value="New Album" class="songbaseButton" />
			</form>
		</div>
		<div class="editArtist">
			<form action="editArtist.php">
				<input type="hidden" name="art" value="<? echo $artist->getArtistID() ?>" />
				<input type="submit" value="Edit Artist" class="songbaseButton" />
			</form>
		</div>	
	</div>

</body>
</html>