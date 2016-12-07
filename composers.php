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
	<script src="scripts/jquery.js"></script>
</head>
<body>
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
		<li><a href="composers.php">Composers</a></li>
	</ul>

	<br /><br />
	<a href="newComposer.php">New Composer</a> <br /><br />
	Composer List <br />

	<? 
		if ($rs != null) {
			$rows = $rs->num_rows;
			if ($rows > 0) {
				while($row = $rs->fetch_assoc()) {
					$composer = new Composer($row);
					echo "<a href='editComposer.php?comp={$composer->getComposerID()}'> EDIT </a> -- {$composer->getName()}<br />";
				}
			} else {
				echo "There are no songs yet";
			} 
		} else {
			echo "There are no songs yet";
		}
	?>
</body>
</html>