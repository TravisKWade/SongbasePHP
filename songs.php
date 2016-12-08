<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Song.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getAllSongsForUser($_SESSION['groupID']);
?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div>
		Songbase - SONG LIST
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
	<a href="newSong.php">New Song</a> <br /><br />
	Song List <br />

	<? 
		if ($rs != null) {
			$rows = $rs->num_rows;
			if ($rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$song = new Song($row);
					echo "<a href='editSong.php?song={$song->getSongID()}'> EDIT </a> -- <a href='songDetails.php?song={$song->getSongID()}'> {$song->getName()} </a><br />";
				}
			} else {
				echo "There are no songs yet";
			} 
		} else {
			echo "There are no songs yet";
		}
	?>
</body>
</html>