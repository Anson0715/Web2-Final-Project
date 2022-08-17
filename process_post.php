<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page used to display error messages when create diary.
	*/
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Error Message</title>
	<link rel="stylesheet" href="style.css" type="text/css">
</head>
<body>
<div id="wrapper">
	<div id="header">
		<h1><a href="index.php">Diary About My Daily Life</a></h1>
	</div>
	<h1>An error occured while processing your post.</h1>
	<p>Both the title and content must be at least one character. </p>
	<a href="admin.php">Return Home</a>
	<?php require_once("footer.php")?>
</div>
</body>
</html>
