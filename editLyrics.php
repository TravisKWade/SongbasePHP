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

	$error = "";

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['lyrics'])){
			$lyric = str_replace("\n", '<br />', $_POST['lyrics']);
			$lyric = str_replace("\r", "", $lyric);
			$lyric = str_replace("'", "\'", $lyric);

			$rs = $db->updateLyricsForSong($_SESSION['groupID'], $_GET['song'], $lyric);
		
			if($rs != null) {
				header("location:" . $_GET['from']);
			} else {
				$error = "There was a problem updating the lyrics";
			}
		} else {
			$error = "All fields are required";
		}
	}

	$lyric = str_replace("<br />", "\n", $lyrics->lyrics);
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
		<div class="title">Songbase - Edit Lyrics</div>
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
	<div class="error">
		<? echo $error;?>
	</div>
	<div class="lyricsBox">
		Song Lyrics: <br /><br />
		<form action="editLyrics.php?song=<? echo $song->getSongID() ?>&from=<? echo $_SERVER['HTTP_REFERER'] ?>" method="post">
			<textarea name="lyrics" rows="30" cols="80"><? echo $lyric ?></textarea>
			<div class="submitLyrics">
				<input type="submit" name="submit" value="Update Lyrics" class="songbaseButton">
			</div>
		</form>
	</div>
	
</body>
</html>