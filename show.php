<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page is used to show the diary details, it used for common users. And when common users click title or 'view comments' in the home page, it also goes to this page. 
	*/
	
	// Database connection.
    require('connect.php');

	// Sanitize the id. Like above but this time from INPUT_GET.
    $diary_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Build the parametrized SQL query using the filtered id.
    //$query = "SELECT * FROM blogs WHERE id = :id";
    $query = "SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, ca.category_id, ca.name FROM diaries d, categories ca WHERE d.category_id = ca.category_id AND d.diary_id = :id ";

    $query_category = "SELECT * FROM categories ORDER BY create_date DESC";

    $statement = $db->prepare($query);
    $statement->bindValue(':id', $diary_id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned.
    $statement->execute();

    // Judge whether there is a record for the id entered by URL.
    if($statement->rowCount() < 1){
	        	// Redirect after update.
	        	header("Location: index.php?");
	        	exit;
    } else{
    	$diary = $statement->fetch();
    	}

    $query_comment = "SELECT * FROM comments WHERE diary_id = :id ORDER BY create_date DESC";

    $statement_comment = $db->prepare($query_comment);
    $statement_comment->bindValue(':id', $diary_id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned.
    $statement_comment->execute();

    // Judge whether there is a record for the id entered by URL.
    if($statement_comment->rowCount() < 1){
    	$message = "No one has commented yet, share with your friends!";
    }


    // Format time
    $format = ' M d, Y, H:i a';
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Diary - <?= $diary['d_title'] ?></title>
	<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<?php require_once("header.php")?>
		</div>
		<ul id="menu">
			<li><a href="index.php">Home</a></li>
		</ul>
		<div id="all_blogs">
			<div class="blog_post">
				<h2><?= $diary['d_title'] ?></h2>
					<div class='blog_content'>
						<?= $diary['d_content'] ?>
						<?php if(trim($diary['image']) != ""): ?>
						<div><a href="<?= $diary['image'] ?>"><img class='image' src="<?= $diary['image'] ?>" alt="picture" /></a></div>
						<?php endif ?>
						<span>Category: <?= $diary['name'] ?></span>
					</div>
			</div>
			<div id="all_comments">
				<div id="leave_comment"><div><h2>Comments: </h2></div><span id="type_comment"><a href="leave_comment.php?id=<?= $diary['diary_id'] ?>">Leave a comment</a></span></div>
				<?php if($statement_comment->rowCount() < 1): ?>
    				<p>No one has commented yet, share with your friends!</p>
    			<?php else: ?>
    				<?php while($comment = $statement_comment->fetch()): ?>
					<div class="comment">
						<p>
							<small><?= date($format, strtotime($comment['create_date'])) ?> -By: <?= $comment['guest_name']?></small>
						</p>
						<p><?= $comment['content'] ?></p>
					</div>
					<?php endwhile ?>
				<?php endif?>
			</div>
		</div>
	<?php require_once("footer.php")?>
	</div>
</body>
</html>
