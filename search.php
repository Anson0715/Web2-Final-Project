<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page used to display the search result by common users.
	*/
	
	// Database connection.
    require('connect.php');

   	if($_POST && $_POST['command'] === 'Search Diary'){
   		if($_POST && isset($_POST['search']) && trim($_POST['search']) != ""){
   			// Sanitize user input to escape HTML entities and filter out dangerous characters.
	        $search_input   = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

			//$query ="SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, ca.name FROM diaries d, categories ca WHERE d.category_id = ca.category_id AND (d.title LIKE '%$search_input%' OR d.content LIKE '%$search_input%') ORDER BY (SELECT item FROM sortby) DESC";

		    $query_category = "SELECT * FROM categories ORDER BY create_date DESC";

		    $query_sortby ="SELECT item, description FROM sortby";

		    // A PDO::Statement is prepared from the query
		    //$statement = $db->prepare($query);
		    $statement_category = $db->prepare($query_category);
		    $statement_sortby = $db->prepare($query_sortby);

		    // Bind parameter
		    //$statement->bindValue(':search_input', '%'.$search_input.'%', PDO::PARAM_STR);

		    // Execution on the DB server is delayed until we execute().
		    //$statement->execute();
		    $statement_category->execute();
		    $statement_sortby->execute();

		    $row_sortby = $statement_sortby->fetch();

		    $query ="SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, ca.name FROM diaries d, categories ca WHERE d.category_id = ca.category_id AND (d.title LIKE '%$search_input%' OR d.content LIKE '%$search_input%') ORDER BY {$row_sortby['item']} DESC";
        	$statement = $db->prepare($query);
    		$statement->execute();
   		}else{
		    echo"<script>alert('You did not input anything!');history.go(-1);</script>";

   		}
   	}


    // Format time
    $format = ' M d, Y, H:i a';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Search Result</title>
	<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1><a href="index.php">Diary About My Daily Life</a></h1>
			<h3>Search Result:</h3>
		</div>
		<ul id="menu">
			<li><a href="index.php">Home</a></li>
		</ul>
		<div id="all_blogs">
				<div class="sortedby">Sorted By: <?= $row_sortby['description'] ?>.</div>
				<?php if($statement->rowCount() >= 1): ?>
				<?php while($row = $statement->fetch()): ?>
				<div class="blog_post">
				<h2><a href="show.php?id=<?= $row['diary_id'] ?>"><?= $row['d_title'] ?></a></h2>
				<p>
					<small><?= date($format, strtotime($row['d_create_date'])) ?></small>
				<br />
					<small class="category">Category: <?= $row['name'] ?></small>
				</p>
					<div class='blog_content'>
					<?php if(strlen($row['d_content']) < 200): ?>
						<?= $row['d_content'] ?>
					<?php else: ?>
						<?= mb_strimwidth($row['d_content'], 0, 199, "...") ?><a href="show.php?id=<?= $row['diary_id'] ?>">Read more</a>
					<?php endif ?>
					</div>
					<?php if(trim($row['image']) != ""): ?>
					<div><a href="<?= $row['image'] ?>"><img class='image' src="<?= $row['image'] ?>" alt="picture" /></a></div>
					<?php endif ?>
					<span class="view_comment"><a href="show.php?id=<?= $row['diary_id'] ?>">view comments...</a></span>
				</div>
				<?php endwhile ?>
				<?php else: ?>
					<h2>Cann't Search a record!</h2> 
				<?php endif ?>

		</div>
	<?php require_once("footer.php")?>
	</div>
</body>
</html>
