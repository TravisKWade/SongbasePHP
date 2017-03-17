<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Artist.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getArtistsForUser($_SESSION['groupID']);
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
		<div class="title">Songbase - Artists</div>
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
	<div class="artistContent">
		<h2>Artist List</h2>
		<? 
			if ($rs != null) {
				$rows = $rs->num_rows;
				if ($rows > 0) {
					while($row = $rs->fetch_assoc()) {
						$artist = new Artist($row);
						echo "<a href='artistDetails.php?art={$artist->getArtistID()}'>{$artist->getName()}</a><br />";
					}
				} else {
					echo "There are no artists yet";
				} 
			} else {
				echo "There are no artists yet";
			}
		?>
	</div>

	<div class="newArtist">
		<form action="newArtist.php">
	    	<input type="submit" value="New Artist" class="songbaseButton" />
		</form>
	</div>
	
</body>
</html>