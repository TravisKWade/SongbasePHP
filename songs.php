<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Song.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	} 

	$db = new DataLayer();

	$rs = $db->getAllSongsForUser($_SESSION['groupID']);
?>
<html>
<head>
	<title> Songbase </title>
	<link rel="stylesheet" type="text/css" href="styles/songbase.css" />
	<link rel="stylesheet" type="text/css" href="styles/header.css" />
	<link rel="stylesheet" type="text/css" href="styles/menu.css" />
	<link rel="stylesheet" type="text/css" href="styles/songs.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - Song List</div>
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
	<div class="newSong">
		<form action="newSong.php">
	    	<input type="submit" value="New Song" class="songbaseButton" />
		</form>
	</div>
	<div class="shortcut">
		<a href="#num">#</a>
		<a href="#A">A</a>
		<a href="#B">B</a>
		<a href="#C">C</a>
		<a href="#D">D</a>
		<a href="#E">E</a>
		<a href="#F">F</a>
		<a href="#G">G</a>
		<a href="#H">H</a>
		<a href="#I">I</a>
		<a href="#J">J</a>
		<a href="#K">K</a>
		<a href="#L">L</a>
		<a href="#M">M</a>
		<a href="#N">N</a>
		<a href="#O">O</a>
		<a href="#P">P</a>
		<a href="#Q">Q</a>
		<a href="#R">R</a>
		<a href="#S">S</a>
		<a href="#T">T</a>
		<a href="#U">U</a>
		<a href="#V">V</a>
		<a href="#W">W</a>
		<a href="#X">X</a>
		<a href="#Y">Y</a>
		<a href="#Z">Z</a>
	</div>
	<div class="songList">
		<h1>Song List</h1>

		<? 
			if ($rs != null) {
				$rows = $rs->num_rows;
				if ($rows > 0) {
					$currentLetter = "";
					while($row = $rs->fetch_assoc()) {
						$song = new Song($row);
						$firstLetter = substr($song->getName(), 0, 1);

						if($firstLetter != $currentLetter) {
							if (is_numeric($firstLetter) && is_numeric($currentLetter)) {
								// we do nothing here, we're on the number songs
							} else if (is_numeric($firstLetter) && !is_numeric($currentLetter)) {
								echo "<div class='letterBreak' id='num'><h1>#</h1></div>";
								$currentLetter = $firstLetter;
							} else {
								echo "<div class='letterBreak' id='{$firstLetter}'><h1>{$firstLetter}</h1></div>";
								$currentLetter = $firstLetter;
							}
						}

						echo "<a href='songDetails.php?song={$song->getSongID()}'> {$song->getName()} </a><br />";
					}
				} else {
					echo "There are no songs yet";
				} 
			} else {
				echo "There are no songs yet";
			}
		?>
	</div>
</body>
</html>