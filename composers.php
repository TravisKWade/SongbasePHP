<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Composer.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getComposersForUser($_SESSION['groupID']);
?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/composer.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Composers</div>
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
	<div class="composerContent">
	<h2>Composer List </h2>

	<? 
		if ($rs != null) {
			$rows = $rs->num_rows;
			if ($rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$composer = new Composer($row);
					echo "<div class='editComposer'>";
					echo "<form action='editComposer.php'>";
	    			echo "<input type='hidden' value='{$composer->getComposerID()}' name='comp' />";
	    			echo "<input type='submit' value='' class='editButton' />";
	    			echo $composer->getName();
					echo "</form>";
					echo "</div>";
				}
			} else {
				echo "There are no composers yet";
			} 
		} else {
			echo "There are no composers yet";
		}
	?>
	</div>
	<div class="newComposer">
		<form action="newComposer.php">
	    	<input type="submit" value="New Composer" class="songbaseButton" />
		</form>
	</div>
</body>
</html>