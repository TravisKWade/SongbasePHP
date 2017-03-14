<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");
	include("classes/Composer.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	}

	$db = new DataLayer();
	$error = "";

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['name']) && !empty($_POST['composer']) && !empty($_POST['month']) && !empty($_POST['day']) && !empty($_POST['year'])){
			$rs = $db->createSongForUser($_SESSION['groupID'], $_POST['name'], $_POST['composer'], $_POST['month'], $_POST['day'], $_POST['year']);
		
			if($rs != null) {
				header("location:songs.php");
			} else {
				$error = "There was a problem creating the song";
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
</head>
<body>
	<div>
		Songbase - NEW SONG
	</div>

	User: <? echo $_SESSION['user']; ?>
	<form action="logout.php">
		<input type="submit" value="Logout" />
	</form>
	<ul>
		<li><a href="songs.php">Songs</a></li>
		<li><a href="artists.php">Artists</a></li>
		<li><a href="albums.php">Albums</a></li>
		<li><a href="composers.php">Composers</a></li>
	</ul>
	<? echo $error;?>
	<div id="new_song_form">
		<form action="newSong.php" method="post">
			<table>
				<tr>
					<td>Song Name:</td>
					<td><input type="text" name="name" /></td>
				</tr>
				<tr>
					<td>Composer:</td>
					<td>
						<select name="composer">
						<?
							foreach($composerArray as $comp) {
								echo "<option value=\"{$comp->getComposerID()}\">{$comp->getName()}</option>";
							}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Month Written:</td>
					<td><input type="text" name="month" /></td>
				</tr>
				<tr>
					<td>Day Written:</td>
					<td><input type="text" name="day" /></td>
				</tr>
				<tr>
					<td>Year Written</td>
					<td><input type="text" name="year" /></td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Add New Song">
		</form>
	</div>
</body>
</html>