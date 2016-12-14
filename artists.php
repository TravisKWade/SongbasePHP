<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Artist.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getArtistsForUser($_SESSION['groupID']);
?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div>
		Songbase - ARTISTS
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
	<br /><br />
	Artist List <br />

	<? 
		if ($rs != null) {
			$rows = $rs->num_rows;
			if ($rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$artist = new Artist($row);
					echo "<a href='editArtist.php?art={$artist->getArtistID()}'> EDIT </a> -- <a href='artistDetails.php?art={$artist->getArtistID()}'>{$artist->getName()}</a><br />";
				}
			} else {
				echo "There are no artists yet";
			} 
		} else {
			echo "There are no artists yet";
		}
	?>
	<br /><br />
	<a href="newSong.php">New Artist</a>
</body>
</html>