<?php
	ob_start();
	session_start();
	include("classes/DataLayer.php");

	if(!isset($_SESSION['user']) || !isset($_SESSION['userID'])) {
		header("location:login.php");
	}

	$db = new DataLayer();
	$error = "";

	if(!empty($_POST['submit'])){
		if(!empty($_POST['name'])){
			$rs = $db->createComposerForUser($_SESSION['groupID'], $_POST['name']);
			
			if($rs != null) {
				header("location: composers.php");
			} else {
				$error = "There was a problem creating the composer";
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
	<link rel="stylesheet" type="text/css" href="styles/composer.css" />
	<link rel="shortcut icon" href="images/favicon.ico">
	<script src="scripts/jquery.js"></script>
</head>
<body>
	<div class="header">
		<div class="title">Songbase - New Composer</div>
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
		<h2>Composer Info </h2>
		<div class="error">
			<? echo $error;?>
		</div>
		<form action="newComposer.php" method="post">
			<table>
				<tr>
					<td>Composer Name:</td>
					<td>
						<input type="text" name="name" /></td>
					</td>
				</tr>
			</table>
			<div class="submitComposer">
				<input type="submit" name="submit" value="Add New Composer" class="songbaseButton">
			</div>
		</form>
	</div>
</body>
</html>