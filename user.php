<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page is used to create or delete admin user, it belongs to the management system.
	*/

	// Database connection.
    require('connect.php');

     // UPDATE quote if author, content and id are present in POST.
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Create'){

	    if ($_POST && isset($_POST['name']) && isset($_POST['password']) && trim($_POST['name']) != "" && trim($_POST['password']) != "") {
	        // Sanitize user input to escape HTML entities and filter out dangerous characters.
	        $name  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	        $password  = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	        // Build the parameterized SQL query and bind to the above sanitized values.
	        $query     = "INSERT INTO admin (user_name, password, create_date) VALUES (:name, :password, sysdate())";
	        $statement = $db->prepare($query);
	        $statement->bindValue(':name', $name);
	        $statement->bindValue(':password', $password);

	        // Execute the INSERT.
	        if($statement->execute()){
	        	// Redirect after update.
	        	header("Location: user.php");
	        	exit;
	        } else{
	        	// Redirect after update.
	        	header("Location: user.php");
	        	exit;
	        	}
	        }else{
	        	echo "<script> alert('User Name and password are required fields！');location.href='user.php';</script>";
		        exit;
	        }
	    }

	    $query_user = "SELECT * FROM admin ORDER BY create_date DESC";

	    // A PDO::Statement is prepared from the query
	    $statement_user = $db->prepare($query_user);
	    //$statement_category->bindValue(':id', $category_id, PDO::PARAM_INT);

	    $statement_user = $db->prepare($query_user);

	    // Execution on the DB server is delayed until we execute().
	    //$statement->execute();
	    $statement_user->execute();
	

    // Format time
    $format = ' M d, Y, H:i a';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Life diary Platform - Home Page</title>
	<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1><a href="index.php" class='active'>Diary About My Daily Life</a></h1>
			<span id="admin"><a href="create.php">New Diary</a></span>
		</div>
		<form action="search_admin.php" method="post" >
			<div id="search"><input class="search_input" type="text" name="search" value="" placeholder="What do you want to see?" /><input class="search" type="submit" name="command" value="Search Diary" /></div>
		</form>
		<ul id="menu">
			<li><a href="admin.php">Diary Management</a></li>
			<li><a href="categories.php">Category Management</a></li>
			<li><a href="sortby.php">Sort management</a></li>
			<li><a href="user.php" class='active'>User management</a></li>
		</ul>
		<div id="all_blogs">
			<div class="blog_post">
			<form action="user.php" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Create New Admin User</legend>
					<p>
					<label for="name">User Name:</label>
					<input name="name" id="name" value="" />
					<label for="password">Password:</label>
					<input type="Password" name="password" id="password" value="" />
					<input type="submit" name="command" value="Create" />
					</p>
				</fieldset>
			</form>
				<fieldset>
				<legend>Existing Categories</legend>
					<table class="category_table">
					<thead>
					<tr>
						<th>User ID</th>
						<th>User Name</th>
						<th>Password</th>
						<th>Create Time</th>
						<th>Delete</th>
						<th>Update</th>
					</tr>
					</thead>
					<tbody>
					<?php while($row_user = $statement_user->fetch()): ?>
					<tr>
						<td><?= $row_user['user_id'] ?></td>
						<td><?= $row_user['user_name'] ?></td>
						<td><input type="Password" name="password" value="<?= $row_user['password'] ?>"></td>
						<td><?= $row_user['create_date'] ?></td>
						<td><span><a href="user_delete.php?id=<?= $row_user['user_id'] ?>" >Delete</a></span></td>
						<td><span><a href="user_update.php?id=<?= $row_user['user_id'] ?>" >Update</a></span></td>
					</tr>
					<?php endwhile ?>
					</tbody>
				</table>
			</fieldset>	
			</div>
		</div>
	<?php require_once("footer.php")?>
	</div>
</body>
</html>
