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

					$fm->updateFolderForAlbum($_SESSION['groupID'], $artist->getArtistID(), $artist->getName(), $album->getAlbumID(), $album->getName(), $_POST['name']);
				} else {
					$error = "Folder not created.";
				}

				header("location:albumDetails.php?al=" . $_GET['al']);
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
		}
	}
?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div>
		Songbase - EDIT ALBUM
	</div>

	User: <? echo $_SESSION['user']; ?>
	<form action="logout.php">
		<input type="submit" value="Logout" />
	</form>
	<div>
		Songbase
	</div>
	<ul>
		<li><a href="songs.php">Songs</a></li>
		<li><a href="artists.php">Artists</a></li>
		<li><a href="albums.php">Albums</a></li>
		<li><a href="composers.php">Composers</a></li>
	</ul>

	<? echo $error;?>
	<div id="new_song_form">
		<form action="editAlbum.php?al=<? echo $album->getAlbumID() ?>" method="post">
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
					<td>Year Released</td>
					<td><input type="text" name="year" value="<? echo $album->getYearReleased() ?>" /></td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Update Album">
		</form>
	</div>
</body>
</html>