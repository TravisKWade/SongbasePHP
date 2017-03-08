<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/FileManager.php");
	include("classes/Artist.php");
	include("classes/Album.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();
	$fm = new FileManager();
	$error = "";

	$rs = $db->getArtistForID($_SESSION['groupID'], $_GET['art']);
	$rows = $rs->num_rows;

	$row = $rs->fetch_assoc();
	$artist = new Artist($row);
	
	if(!empty($_POST['submit'])){	
		if(!empty($_POST['name'])){
			$fm->updateFolderForArtist($_SESSION['groupID'], $artist->getName(), $_POST['name'], $artist->getArtistID());
			$rs = $db->updateArtistForUser($_SESSION['groupID'], $_POST['name'], $_GET['art']);
		
			if($rs != null) {
				header("location: artists.php");
			} else {
				$error = "There was a problem updating the artist";
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
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div>
		Songbase - EDIT ARTIST
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
	<br /><br />
	<? echo $error;?>
	<div id="edit_artist_form">
		<form action="editArtist.php?art=<? echo $artist->getArtistID() ?>" method="post">
			<table>
				<tr>
					<td>Artist Name:</td>
					<td>
						<input type="text" name="name" value="<? echo $artist->getName() ?>" /></td>
					</td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Update Artist">
		</form>
	</div>

</body>
</html>