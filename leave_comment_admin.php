<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page used to leave comments by admin users, and admin users can manage comments here.
	*/

	// Database connection.
    require('connect.php');

	// Sanitize the id. Like above but this time from INPUT_GET.
    $diary_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Create'){
	    if ($_POST && !empty($_POST['guest_name']) && !empty($_POST['content'])) {
	        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
	        $guest_name = filter_input(INPUT_POST, 'guest_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	        //  Build the parameterized SQL query and bind to the above sanitized values.
	        $query = "INSERT INTO comments (guest_name, content, diary_id, create_date) VALUES (:guest_name, :content, $diary_id, sysdate())";
	        $statement = $db->prepare($query);


	        //  Bind values to the parameters
	        $statement->bindValue(':guest_name', $guest_name);
	        $statement->bindValue(':content', $content);

	        //  Execute the INSERT.
	        //  execute() will check for possible SQL injection and remove if necessary
	        if($statement->execute()){
	            // Redirect after insert.
	            header("Location: show_admin.php?id=$diary_id");
	            exit;
	        }
	    }else{
	    	// Redirect after update.
			echo"<script>alert('Both the your name and content are required Fields.');history.go(-1);</script>";
	    }
	}elseif ($_POST['command'] == 'Cancel') {
		// Redirect after insert.
	    header("Location: show_admin.php?id=$diary_id");
	    exit;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="styles.css" type="text/css">
	<title>Leave a comment</title>
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1>Leave a comment</h1>
		</div>
		<div id="all_blogs">
			<form method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Comment content:</legend>
					<p>
					<label for="guest_name">Let me know who you are:</label>
					<input name="guest_name" id="title" />
					</p>
					<p>
					<label for="content">Content</label>
					<textarea name="content" id="content"></textarea>
					</p>
					<p>
					<input type="submit" name="command" value="Create" />
					<input type="submit" name="command" value="Cancel" />
					</p>
				</fieldset>
			</form>
		</div>
		<?php require_once("footer.php")?>
	</div>
</body>
</html>