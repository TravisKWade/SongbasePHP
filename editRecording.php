<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/FileManager.php");
	include("classes/Song.php");
	include("classes/Recording.php");
	include("classes/Artist.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$error = "";
	$db = new DataLayer();
	$fm = new FileManager();

	$rs = $db->getRecordingForID($_GET['rec']);
	$row = $rs->fetch_assoc();
	$recording = new Recording($row);

	$songRS = $db->getSongForUser($_SESSION['groupID'], $recording->getSongID());
	$songRow = $songRS->fetch_assoc();
	$song = new Song($songRow);

	if(!empty($_POST['submit'])){	
		$rs = $db->updateRecordingForUser($_SESSION['groupID'], $_GET['rec'], $_POST['artist'], $_POST['album'], $_POST['month'], $_POST['day'], $_POST['year']);
		
		if($rs != null) {
			if ($_FILES['file']['name']) {
				$rs = $db->getRecordingForID($_GET['rec']);
				$row = $rs->fetch_assoc();
				$recording = new Recording($row);

				$artRS = $db->getArtistForID($_SESSION['groupID'], $recording->getArtistID());
				$alRS = $db->getAlbumForID($_SESSION['groupID'], $recording->getAlbumID());

				$target_path = $target_path . basename( $_FILES['file']['name']); 

				if ($artRS != null) {
					$artist = new Artist($artRS->fetch_assoc());
				}

				if ($alRS != null) {
					$album = new Album($alRS->fetch_assoc());
				}

				$result = $fm->uploadRecording($_SESSION['groupID'], $_FILES['file']['tmp_name'], realpath(dirname(__FILE__)), $song->getName(), $recording->getRecordingID(), $album->getAlbumID(), $album->getName(), $artist->getArtistID(), $artist->getName());

				if($result == 1) {
					header("location:songDetails.php?song={$song->getSongID()}");
				} else {
					echo $result;
					$error = "the recording file was not uploaded. <br />";
				}
			} else {
				header("location:songDetails.php?song={$song->getSongID()}");
			}
		} else {
			$error = "There was a problem updating the recording";
		}
		
	}

	$artistArray = array();
	$artRS = $db->getArtistsForUser($_SESSION['groupID']);

	if ($artRS != null) {
		while($artRow = $artRS->fetch_assoc()) {
			$artist = new Artist($artRow);
			$artistArray[$artist->getArtistID()] = $artist;
		}
	}

	$albumArray = array();

	if($_GET['art'] == null) {
		$alRS = $db->getAlbumsForArtist($recording->getArtistID());
	} else {
		$alRS = $db->getAlbumsForArtist($_GET['art']);
	}

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
	<link rel="stylesheet" type="text/css" href="styles/recording.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Edit Recording</div>
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
	<div class="error">
		<? echo $error ?>
	</div>
	<div class="recordingContent">
		<div class="songTitle">
			<? echo $song->getName() ?>
		</div>
		<form action="editRecording.php?rec=<? echo $_GET['rec'] ?>" method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Artist:</td>
					<td>
						<select name="artist" id="artistSelect">
						<?
							foreach($artistArray as $artist) {
								if($artist->getArtistID() == $recording->getArtistID() && $_GET['art'] == null) {
									$recordingArtist = $artist;
									echo "<option value=\"{$artist->getArtistID()}\" selected>{$artist->getName()}</option>";
								} else if($_GET['art'] != null && $_GET['art'] == $artist->getArtistID()) {
									echo "<option value=\"{$artist->getArtistID()}\" selected>{$artist->getName()}</option>";
								} else {
									echo "<option value=\"{$artist->getArtistID()}\">{$artist->getName()}</option>";
								}
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Album:</td>
					<td>
						<select name="album">
						<?
							foreach($albumArray as $album) {
								if ($album->getAlbumID() == $recording->getAlbumID()) {
									$recordedAlbum = $album;
									echo "<option value=\"{$album->getAlbumID()}\" selected>{$album->getName()}</option>";
								} else {
									echo "<option value=\"{$album->getAlbumID()}\">{$album->getName()}</option>";
								}
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Month Recorded:</td>
					<td><input type="text" name="month" value="<? echo $recording->getMonthRecorded() ?>" /></td>
				</tr>
				<tr>
					<td>Day Recorded:</td>
					<td><input type="text" name="day" value="<? echo $recording->getDayRecorded() ?>" /></td>
				</tr>
				<tr>
					<td>Year Recorded</td>
					<td><input type="text" name="year" value="<? echo $recording->getYearRecorded() ?>" /></td>
				</tr>
				<tr><td><br /></td></tr>

				<?
					if($recordingFileExists == 1) {
				?>
				<tr>
					<td>Recording Exists</td>
					<td></td>
				</tr>
				<?
					} else {
				?>
				<tr>
					<td>Upload Recording **</td>
					<td><input type="file" name="file" id="file" /></td>
				</tr>
				<?
					}
				?>
			</table>
			<div class="recordingSubmit">
				<input type="submit" name="submit" value="Update Recording" class="songbaseButton">
			</div>
		</form>
	</div>
</body>
</html>