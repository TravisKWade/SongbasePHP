<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/FileManager.php");
	include("classes/Artist.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	}

	$db = new DataLayer();
	$fm = new FileManager();
	$error = "";

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['name']) && !empty($_POST['year'])){
			$rs = $db->createAlbumForUser($_SESSION['groupID'], $_POST['artist'], $_POST['name'], $_POST['year']);
		
			if($rs != null) {
				$artRS = $db->getArtistForID($_SESSION['groupID'], $_POST['artist']);
				if ($artRS != null) {
					$artRow = $artRS->fetch_assoc();
					$artist = new Artist($artRow);
					
					$fm->createFolderForAlbum($_SESSION['groupID'], $artist->getArtistID(), $artist->getName(), $rs, $_POST['name']);
					header("location:" . $_GET['from']);
				} else {
					$error = "Folder not created.";
				}

			} else {
				$error = "There was a problem creating the album";
			}
		} else {
			$error = "All fields are required";
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
		<form action="newAlbum.php?from=<? echo $_SERVER['HTTP_REFERER'] ?>" method="post">
			<table>
				<tr>
					<td>Artist:</td>
					<td>
						<select name="artist">
						<?
							foreach($artistArray as $artist) {
								echo "<option value=\"{$artist->getArtistID()}\">{$artist->getName()}</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Album Name:</td>
					<td><input type="text" name="name" /></td>
				</tr>
				<tr>
					<td>Year Released</td>
					<td><input type="text" name="year" /></td>
				</tr>
			</table>
			<div class="submitAlbum">
				<input type="submit" name="submit" value="Add New Album" class="songbaseButton">
			</div>
		</form>
	</div>
</body>
</html>