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
		if(!empty($_POST['name'])){
			$rs = $db->createArtistForUser($_SESSION['groupID'], $_POST['name']);
			
			if($rs != null) {
				$fm->createFolderForArtist($_SESSION['groupID'], $rs, $_POST['name']);
				header("location: artists.php");
			} else {
				$error = "There was a problem creating the artist";
			}
		} else {
			$error = "All fields are required";
		}
	}
?>

<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/artist.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - New Artist</div>
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
	<div class="artistContent">
		<form action="newArtist.php?from=<? echo $_SERVER['HTTP_REFERER'] ?>" method="post">
			<table>
				<tr>
					<td>Artist Name:</td>
					<td>
						<input type="text" name="name" /></td>
					</td>
				</tr>
			</table>
			<div class="newArtist">
				<input type="submit" name="submit" value="Add New Artist" class="songbaseButton">
			</div>
		</form>
	</div>
</body>
</html>