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
	$recordingArray = array();

	$albumRS = $db->getAlbumForID($_SESSION['groupID'], $_GET['al']);
	$albumRow = $albumRS->fetch_assoc();
	$album = new Album($albumRow);

	$artRS = $db->getArtistForID($_SESSION['groupID'], $album->getArtistID());
	$artRow = $artRS->fetch_assoc();
	$artist = new Artist($artRow);

	$albumRecordingsOrderRS = $db->getRecordingsForAlbumWithOrder($album->getAlbumID());

	if ($albumRecordingsOrderRS != null) {
		$index = 1;
		while($albumRecordingRow = $albumRecordingsOrderRS->fetch_assoc()) {
			$recording = new Recording($albumRecordingRow);
			$recording->setOrdinal($albumRecordingRow['ordinal']);
			$recordingArray[$index] = $recording;
			$index = $index + 1;
		}
	} else {
		$albumRecordingsRS = $db->getRecordingsForAlbum($album->getAlbumID());

		if ($albumRecordingsRS != null) {
			$index = 1;
			while($albumRecordingRow = $albumRecordingsRS->fetch_assoc()) {
				$recording = new Recording($albumRecordingRow);
				$recordingArray[$index] = $recording;

				$index = $index + 1;
			}
		}
	}

	$count = $_GET['count'];

	if(!empty($_POST['up'])) {
		if ($count > 1) {
			$tempRec = $recordingArray[$count-1];
			$recordingArray[$count-1] = $recordingArray[$count];
			$recordingArray[$count] = $tempRec;
		}

		$db->updateRecordingOrderForAlbum($_SESSION['groupID'], $recordingArray);

	} else if(!empty($_POST['down'])) {
		if ($count < count($recordingArray)-2) {
			$tempRec = $recordingArray[$count+1];
			$recordingArray[$count+1] = $recordingArray[$count];
			$recordingArray[$count] = $tempRec;
		}
		
		$db->updateRecordingOrderForAlbum($_SESSION['groupID'], $recordingArray);
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
		<div class="title">Songbase - Edit Album</div>
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
			$count = 1;
			foreach($recordingArray as $recording) {
				$songRS = $db->getSongForUser($_SESSION['userID'], $recording->getSongID());
				$songRow = $songRS->fetch_assoc();
				$song = new Song($songRow);
		?>
		<form action="editAlbumSongOrder.php?al=<? echo $album->getAlbumID() ?>&rec=<? echo $recording->getRecordingID() ?>&count=<? echo $count ?>" method="post">
			<? 
				if ($count < 10) {
					echo "&nbsp;&nbsp;{$count}";
				} else {
					echo $count;
				} 
			?>.
			<input type="submit" name="up" value="Up" class="upButton">
			<input type="submit" name="down" value="Down" class="downButton">
			<? echo $song->getName() ?>
		</form>
		<?
				$count = $count + 1;
			}
		?>
	</div>
</body>
</html>