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
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/lyrics.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Lyrics</div>
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

	<div class="songTitle">
		<? echo $song->getName() ?>
	</div>

	<div class="lyrics">
	
	<?
		if($lyrics != null) {
			echo $lyrics->getLyrics();
		} else {
			echo "There was a problem getting the lyrics";
		}
	?>
	</div>

	<div class="editLyrics">
		<form action="editLyrics.php">
			<input type="hidden" name="song" value="<? echo $song->getSongID() ?>" />
	    	<input type="submit" value="Edit Lyrics" class="songbaseButton" />
		</form>
	</div>

</body>
</html>