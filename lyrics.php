<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Song.php");
	include("classes/SongLyrics.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getSongForUser($_SESSION['groupID'], $_GET['song']);
	$row = $rs->fetch_assoc();
	$song = new Song($row);

	$lyricsRS = $db->getLyricsForSong($song->getSongID());
	
	if ($lyricsRS != null) {
		$lyricRow = $lyricsRS->fetch_assoc();
		$lyrics = new SongLyrics($lyricRow);
	} else {
		$lyrics = null;
	}

?>
<html>
<head>
	<title> Songbase </title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div>
		Songbase - SONG LYRICS
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
	
	<?
		if($lyrics != null) {
			echo $lyrics->getLyrics();
		} else {
			echo "There was a problem getting the lyrics";
		}
	?>
	
	<br /><br />
	<a href="editLyrics.php?song=<? echo $song->getSongID() ?>"> Edit Lyrics</a> 
</body>
</html>