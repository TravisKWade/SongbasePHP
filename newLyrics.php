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

	$error = "";

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['lyrics'])){
			$rs = $db->createLyricsForSong($_SESSION['groupID'], $_GET['song'], $_POST['lyrics']);
		
			if($rs != null) {
				header("location:" . $_GET['from']);
			} else {
				$error = "There was a problem adding the lyrics";
			}
		} else {
			$error = "All fields are required";
		}
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
		Songbase - ADD SONG LYRICS
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
	<? echo $error;?>
	<br />
	Song Lyrics:
	<div id="new_lyrics_form">
		<form action="newLyrics.php?song=<? echo $song->getSongID() ?>&from=<? echo $_SERVER['HTTP_REFERER'] ?>" method="post">
			<textarea name="lyrics" rows="30" cols="80"></textarea>
			<br /><br />
			<input type="submit" name="submit" value="Add Lyrics">
		</form>
	</div>
	
</body>
</html>