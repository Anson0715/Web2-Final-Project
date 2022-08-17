<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page is the home page of Management System, from this page you can go to edit or create diary, manage comments, manage sort criteria, manage category and so on. 
	*/

	// Database connection.
    require('connect.php');

    // SQL is written as a String
    //$query = "SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, co.comment_id, co.guest_name, co.content co_content, co.diary_id co_diary_id, ca.category_id, ca.name FROM diaries d,comments co, categories ca WHERE d.diary_id = co.diary_id AND d.category_id = ca.category_id ORDER BY d_create_date DESC";

	//$query ="SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, ca.name FROM diaries d, categories ca WHERE d.category_id = ca.category_id ORDER BY d_create_date DESC";

    //$query_category = "SELECT * FROM categories ORDER BY create_date DESC";
    $query_sortby ="SELECT item, description FROM sortby";

    // A PDO::Statement is prepared from the query
    //$statement = $db->prepare($query);
    //$statement_category = $db->prepare($query_category);
    $statement_sortby = $db->prepare($query_sortby);

    // Execution on the DB server is delayed until we execute().
    //$statement->execute();
    //$statement_category->execute();
    $statement_sortby->execute();
        
    $row_sortby = $statement_sortby->fetch();

    $query ="SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, ca.name FROM diaries d, categories ca WHERE d.category_id = ca.category_id  ORDER BY {$row_sortby['item']} DESC";
    $statement = $db->prepare($query);
    $statement->execute();

    // Format time
    $format = ' F d, Y, H:i a';

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Life diary Platform - Admin</title>
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
			<li><a href="admin.php" class='active'>Diary Management</a></li>
			<li><a href="categories.php">Category Management</a></li>
			<li><a href="sortby.php">Sort management</a></li>
			<li><a href="user.php">User management</a></li>
		</ul>
		<div id="all_blogs">
				<div class="sortedby">Sorted By: <?= $row_sortby['description'] ?>.</div>
				<?php while($row = $statement->fetch()): ?>
				<div class="blog_post">
				<h2><a href="show_admin.php?id=<?= $row['diary_id'] ?>"><?= $row['d_title'] ?></a></h2>
				<p>
					<small><?= date($format, strtotime($row['d_create_date'])) ?> -<a href="edit.php?id=<?= $row['diary_id'] ?>">edit</a></small>
					<br />
					<small class="category">Category: <?= $row['name'] ?></small>
				</p>
					<div class='blog_content'>
					<?php if(strlen($row['d_content']) < 200): ?>
						<?= $row['d_content'] ?>
					<?php else: ?>
						<?= mb_strimwidth($row['d_content'], 0, 199, "...") ?><a href="show_admin.php?id=<?= $row['diary_id'] ?>">Read more</a>
					<?php endif ?>
					</div>
					<?php if(trim($row['image']) != ""): ?>
					<div><a href="<?= $row['image'] ?>"><img class='image' src="<?= $row['image'] ?>" alt="picture" /></a></div>
					<?php endif ?>
					<span class="view_comment"><a href="show_admin.php?id=<?= $row['diary_id'] ?>">View & Delete comments...</a></span>
				</div>
				<?php endwhile ?>
		</div>
	<!---<div id="footer">Web Development 2 - By BIT Student Anson</div> --->
	<?php require_once("footer.php")?>
	</div>
</body>
</html>
