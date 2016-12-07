<?php
	ob_start();
	session_start();
	include('classes/DataLayer.php');
	$error = "";

	error_reporting(E_ALL);
	ini_set('display_errors', TRUE);

	if(isset($_SESSION['user']) && isset($_SESSION['userID'])) {
		header("Location:index.php");
	} 

	if(!empty($_POST['submit'])){	
		if(!empty($_POST['email']) && !empty($_POST['password'])){	
			$db = new DataLayer();

			$rs = $db->loginUser($_POST['email'], $_POST['password']);

			if ($rs != null) {
				$row = $rs->fetch_assoc();
				$_SESSION['user'] = $row['username'];
				$_SESSION['userID'] = $row['id'];
				$_SESSION['groupID'] = $row['access_user_id'];
				header("Location:index.php");
			} else {
				$error = "Email or password is incorrect.";
			}
		} else {
			$error = "Please enter your email and password";
		}
	} 
?>

<html>
<head>
	<title>Songbase</title>
</head>
<body>
	<div id="new_account_form">
		<form action="login.php" method="post">
			<? echo $error ?>
			<table>
				<tr>
					<td>Email:</td>
					<td><input type="text" name="email" /></td>
				</tr>
				<tr>
					<td>password:</td>
					<td><input type="password" name="password" /></td>
				</tr>
			</table>
			<input type="submit" name="submit" value="Login">
		</form>
	</div>
</body>
</html>