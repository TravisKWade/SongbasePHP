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
	<title>Songbase</title>
</head>
<body>
	<div>
		Songbase - NEW COMPOSER
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
	<div id="new_artist_form">
		<form action="newComposer.php" method="post">
			<table>
				<tr>
					<td>Composer Name:</td>
					<td>
						<input type="text" name="name" /></td>
					</td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Add New Composer">
		</form>
	</div>
</body>
</html>