<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/FileManager.php");
	include("classes/Song.php");
	include("classes/Composer.php");
	include("classes/Recording.php");
	include("classes/Artist.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();
	$fm = new FileManager();

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['name']) && !empty($_POST['year'])){

			$albumRS = $db->getAlbumForID($_SESSION['groupID'], $_GET['al']);
			$albumRow = $albumRS->fetch_assoc();
			$album = new Album($albumRow);

			$rs = $db->updateAlbumForID($_GET['al'], $_POST['artist'], $_POST['name'], $_POST['year']);
		
			if($rs != null) {
				$artRS = $db->getArtistForID($_SESSION['groupID'], $_POST['artist']);
				if ($artRS != null) {
					$artRow = $artRS->fetch_assoc();
					$artist = new Artist($artRow);

					if (!empty($_FILES['file']['name'])) {
						$result = $fm->uploadImageForAlbum($_SESSION['groupID'], $artist->getArtistID(), $artist->getName(), $_GET['al'], $_POST['name'], $_FILES['file']['tmp_name']);
					}

					if($result == "1") {
						header("location:albumDetails.php?al=" . $_GET['al']);
					} else {
						echo $result;
						$error = "There was a problem uploading the album image";
					}
				} else {
					$error = "Folder not created.";
				}
			} else {
				$error = "There was a problem updating the album";
			}
		} else {
			$error = "All fields are required";
		}
	}

	$albumRS = $db->getAlbumForID($_SESSION['groupID'], $_GET['al']);
	$albumRow = $albumRS->fetch_assoc();
	$album = new Album($albumRow);


	$artistArray = array();
	$artRS = $db->getArtistsForUser($_SESSION['groupID']);

	if ($artRS != null) {
		while($artRow = $artRS->fetch_assoc()) {
			$artist = new Artist($artRow);
			$artistArray[$artist->getArtistID()] = $artist;

			if($artist->getArtistID() == $album->getArtistID()) {
				$selectedArtist = $artist;
			}
		}
	}

	$imageCoverExists = $fm->getImagePathForAlbum($_SESSION['groupID'], $selectedArtist->getArtistID(), $selectedArtist->getName(), $albumID, $albumName);
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
	<div class="error">
		<? echo $error;?>
	</div>
	<div class="albumContent">
		<h2>Album Info</h2>
		<form action="editAlbum.php?al=<? echo $album->getAlbumID() ?>" method="post" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Artist:</td>
					<td>
						<select name="artist">
						<?
							foreach($artistArray as $artist) {
								if ($artist->getArtistID() == $album->getArtistID()) {
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
					<td>Album Name:</td>
					<td><input type="text" name="name" value="<? echo $album->getName() ?>"/></td>
				</tr>
				<tr>
					<td>Year Released:</td>
					<td><input type="text" name="year" value="<? echo $album->getYearReleased() ?>" /></td>
				</tr>
				<tr>
					<td>Upload Album Cover:</td>
					<td><? if($imageCoverExists == "0") { ?><input type="file" name="file" id="file" /><? } ?>  </td>
				</tr>
			</table>
			<div class="submitAlbum">
				<input type="submit" name="submit" value="Update Album" class="songbaseButton">
			</div>
		</form>
	</div>
</body>
</html>