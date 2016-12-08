<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	}

	$db = new DataLayer();
	
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
	<title>Songbase</title>
</head>
<body>
	<div>
		Songbase - ALBUMS
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
	<?
		foreach($albumArray as $album) {
			echo "<a href='editAlbum.php?al={$album->getAlbumID()}'>EDIT</a> - <a href='albumDetails.php?al={$album->getAlbumID()}'>{$album->getName()}</a><br />";
		}
	?>
</body>
</html>