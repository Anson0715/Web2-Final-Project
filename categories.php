<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page is used to create or delete category by admin users.
	*/

	// Database connection.
    require('connect.php');

     // UPDATE quote if author, content and id are present in POST.
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Create'){

	    if ($_POST && isset($_POST['name']) && trim($_POST['name']) != "") {
	        // Sanitize user input to escape HTML entities and filter out dangerous characters.
	        $name  = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	        // Build the parameterized SQL query and bind to the above sanitized values.
	        $query     = "INSERT INTO categories (name, create_date) VALUES (:name, sysdate())";
	        $statement = $db->prepare($query);
	        $statement->bindValue(':name', $name);

	        // Execute the INSERT.
	        if($statement->execute()){
	        	// Redirect after update.
	        	header("Location: categories.php");
	        	exit;
	        } else{
	        	// Redirect after update.
	        	header("Location: categories.php");
	        	exit;
	        	}
	        }else{
                echo "<script> alert('Category Name is required fields！');location.href='categories.php';</script>";
                exit;
            }
	    }

	    $query_category = "SELECT * FROM categories ORDER BY create_date DESC";

	    // A PDO::Statement is prepared from the query
	    $statement_category = $db->prepare($query_category);
	    //$statement_category->bindValue(':id', $category_id, PDO::PARAM_INT);

	    $statement_category = $db->prepare($query_category);

	    // Execution on the DB server is delayed until we execute().
	    //$statement->execute();
	    $statement_category->execute();
	

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
			<li><a href="categories.php" class='active'>Category Management</a></li>
			<li><a href="sortby.php">Sort management</a></li>
			<li><a href="user.php">User management</a></li>
		</ul>
		<div id="all_blogs">
			<div class="blog_post">
			<form action="categories.php" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Create New Category</legend>
					<p>
					<label for="name">Category Name</label>
					<input name="name" id="name" value="" />
					<input type="submit" name="command" value="Create" />
					</p>
				</fieldset>
			</form>
				<fieldset>
				<legend>Existing Categories</legend>
					<table class="category_table">
					<thead>
					<tr>
						<th>Category Name</th>
						<th>Create Time</th>
						<th>Operation</th>
					</tr>
					</thead>
					<tbody>
					<?php while($row_category = $statement_category->fetch()): ?>
					<tr>
						<td><?= $row_category['name'] ?></td>
						<td><?= $row_category['create_date'] ?></td>
						<td><span><a href="category_delete.php?id=<?= $row_category['category_id'] ?>" >Delete</a></span></td>
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
