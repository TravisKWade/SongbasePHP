<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/FileManager.php");
	include("classes/Song.php");
	include("classes/Artist.php");
	include("classes/Album.php");
	include("classes/Recording.php");
	include("classes/Composer.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	}

	$db = new DataLayer();
	$fm = new FileManager();
	$error = "";

	$songRS = $db->getSongForUser($_SESSION['groupID'], $_GET['song']);
	$songRow = $songRS->fetch_assoc();
	$song = new Song($songRow);

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['name']) && !empty($_POST['composer']) && !empty($_POST['month']) && !empty($_POST['day']) && !empty($_POST['year'])){
			$nameChanged = 0;
			$oldName = "";

			if ($_POST['name'] != $song->getName()) {
				$nameChanged = 1;
				$oldName = $song->getName();
			}

			$rs = $db->updateSongForUser($_SESSION['groupID'], $song->getSongID(), $_POST['name'], $_POST['composer'], $_POST['month'], $_POST['day'], $_POST['year']);
		
			if ($nameChanged) {
				// get all of the recordings for the song
				$recRS = $db->getRecordingsForSong($song->getSongID());

				// loop through and call file manager to do the re-naming
				if ($recRS != null) {
					while($recRow = $recRS->fetch_assoc()) {
						$recording = new Recording($recRow);

						// get artist info
						$artRS = $db->getArtistForID($_SESSION['groupID'], $recording->getArtistID());
						$artRow = $artRS->fetch_assoc();
						$artist = new Artist($artRow);

						// get album info
						$alRS = $db->getAlbumForID($_SESSION['groupID'], $recording->getAlbumID());
						$alRow = $alRS->fetch_assoc();
						$album = new Album($alRow);

						// rename
						$fm->renameSongNameForRecording($_SESSION['groupID'], $oldName, $_POST['name'], $recording->getRecordingID(), $album->getAlbumID(), $album->getName(), $artist->getArtistID(), $artist->getName());
					}
				}
			}

			if($rs != null) {
				header("location:songs.php");
			} else {
				$error = "There was a problem updating the song";
			}
		} else {
			$error = "All fields are required";
		}
	}

	// get the composer data
	$composerArray = array();
	$compRS = $db->getComposersForUser($_SESSION['groupID']);

	if ($compRS != null) {
		while($compRow = $compRS->fetch_assoc()) {
			$composer = new Composer($compRow);
			$composerArray[$composer->getComposerID()] = $composer;
		}
	}
?>

<html>
<head>
	<title>Songbase</title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/songDetails.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Edit Song</div>
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
	<div class="songDetails">
		<form action="editSong.php?song=<? echo $song->getSongID() ?>" method="post">
			<table>
				<tr>
					<td>Song Name:</td>
					<td><input type="text" name="name" value="<? echo $song->getName() ?>" style="width:250px" /></td>
				</tr>
				<tr>
					<td>Composer:</td>
					<td>
						<select name="composer">
						<?
							foreach($composerArray as $comp) {
								if ($song->getComposerID() == $comp->getComposerID()) {
									echo "<option value=\"{$comp->getComposerID()}\" selected>{$comp->getName()}</option>";
								} else {
									echo "<option value=\"{$comp->getComposerID()}\">{$comp->getName()}</option>";
								}
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Month Written:</td>
					<td><input type="text" name="month" value="<? echo $song->getMonthWritten() ?>" style="width:250px" /></td>
				</tr>
				<tr>
					<td>Day Written:</td>
					<td><input type="text" name="day" value="<? echo $song->getDayWritten() ?>" style="width:250px" /></td>
				</tr>
				<tr>
					<td>Year Written</td>
					<td><input type="text" name="year" value="<? echo $song->getYearWritten() ?>" style="width:250px" /></td>
				</tr>
			</table>
			<div class="submitSong">
				<input type="submit" name="submit" value="Update Song" class="songbaseButton">
			</div>
		</form>
	</div>
</body>
</html>