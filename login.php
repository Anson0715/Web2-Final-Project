<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page used to login to the Management System by admin users.
	*/

	// Database connection.
    require('connect.php');
	
	//start session
	session_start();
if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Login'){

	if(empty($_POST['user_name'])){
	        echo "<script> alert('Please input your user name!');location.href='admin.php';</script>";
	    }
	if(empty($_POST['password'])){
	        echo "<script> alert('Please input your password!');location.href='admin.php';</script>";
	    }
	    	    
	// Sanitize the data. Like above but this time from INPUT_GET.
   	$userName = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
   	$passWord = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	//  Build the parameterized SQL query and bind to the above sanitized values.
	$query = "SELECT * FROM admin WHERE user_name = :name LIMIT 1";
	$statement = $db->prepare($query);
	$statement->bindValue(':name', $userName, PDO::PARAM_INT);

	// Execute the SELECT and fetch the single row returned.
   	$statement->execute();

   	// Judge whether there is a record for the id entered by URL.
   	if($statement->rowCount() < 1){
		// Redirect after update.
    	echo "<script> alert('No record!'); location.href='admin.php';</script>";
    	exit;
    } else{
    	$user_record = $statement->fetch();
    	if($user_record['password'] != $passWord){
    		echo "<script> alert('Wrong Password!');location.href='admin.php';</script>";
	        exit;
    	} elseif($user_record['password'] == ($passWord)){
    		//Keeping user name
	    	$_SESSION['loginUser'] = $userName;

	    	//Redirection when login success 
	        	// Redirect after update.
	        	header("Location: admin.php");
	        	exit;
    	}
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Cancel') {
		// Redirect after insert.
	    header("Location: index.php");
	    exit;
	}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login</title>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1>Admin Login</h1>
		</div>
		<div id="all_blogs">
			<form method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Login:</legend>
					<p>
					<label for="user_name">Your user name:</label>
					<input name="user_name" id="user_name" />
					</p>
					<p>
					<label for="password">Pawssword:</label>
					<input type="password" name="password" id="password" />
					</p>
					<p>
					<input type="submit" name="command" value="Login" />
					<input type="submit" name="command" value="Cancel" />
					</p>
				</fieldset>
			</form>
		</div>
		<?php require_once("footer.php")?>
	</div>
</body>
</html>