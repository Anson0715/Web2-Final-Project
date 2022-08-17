<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page used to manage sort criteria only by Admin users.
	*/

	// Database connection.
    require('connect.php');

     // UPDATE quote if author, content and id are present in POST.
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Set'){

	    if ($_POST && isset($_POST['sortby']) && isset($_POST['description']) && trim($_POST['sortby']) != "" && trim($_POST['description']) != "") {
	        // Sanitize user input to escape HTML entities and filter out dangerous characters.
	        $item  = filter_input(INPUT_POST, 'sortby', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	        $description  = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	        // Build the parameterized SQL query and bind to the above sanitized values.
	        $query     = "UPDATE sortby SET item = :item, description = :description";

	        $statement = $db->prepare($query);
	        $statement->bindValue(':item', $item);
	        $statement->bindValue(':description', $description);

	        // Execute the INSERT.
	        if($statement->execute()){
	        	// Redirect after update.
	        	header("Location: sortby.php");
	        	exit;
	        } else{
	        	// Redirect after update.
	        	header("Location: sortby.php");
	        	exit;
	        	}
	        }
	    }

	    $query_sortby = "SELECT * FROM sortby";

	    // A PDO::Statement is prepared from the query
	    $statement_sortby = $db->prepare($query_sortby);
	    //$statement_category->bindValue(':id', $category_id, PDO::PARAM_INT);

	    $statement_sortby = $db->prepare($query_sortby);

	    // Execution on the DB server is delayed until we execute().
	    //$statement->execute();
	    $statement_sortby->execute();
	

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
			<li><a href="sortby.php" class='active'>Sort management</a></li>
			<li><a href="user.php">User management</a></li>
		</ul>
		<div id="all_blogs">
			<div class="blog_post">
			<form action="sortby.php" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Set Sorted By:</legend>
					<p>
						<select id="sortby" name="sortby">
							<option value="">Please Set</option>
							<option value="d.Diary_ID">Diary ID</option>
							<option value="d.Title">Diary Title</option>
							<option value="d.Create_Date">Create Time</option>
							<option value="d.Update_Date">Update Time</option>
						</select>
						<label for="description">Description(required)</label>
						<input type="text" name="description" id="description" />
						<input type="submit" name="command" value="Set" />
					</p>
				</fieldset>
			</form>
				<fieldset>
				<legend>Existing Sorted By:</legend>
					<?php while($row_sortby = $statement_sortby->fetch()): ?>
						<h2>Sort By <?= $row_sortby['description'] ?>, Column: <?= $row_sortby['item'] ?></h2>
					<?php endwhile ?>
			</fieldset>	
			</div>
		</div>
	<?php require_once("footer.php")?>
	</div>
</body>
</html>
