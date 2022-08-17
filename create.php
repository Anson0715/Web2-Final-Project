<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page used to create a new diary only by admin users.
	*/

	// Authorization and Database connection.
    require('connect.php');
    
    // SQL is written as a String
	$query_category = "SELECT * FROM categories ORDER BY create_date DESC";

    // A PDO::Statement is prepared from the query
    $statement_category = $db->prepare($query_category);

    // Execution on the DB server is delayed until we execute().
    $statement_category->execute();
    //$statement_category->execute();

	if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Create'){

	    // Obtain the image
	    // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
	    // Default upload path is an 'uploads' sub-folder in the current folder.
	    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
	       $current_folder = "./";

	       echo $current_folder;
	       
	       // Build an array of paths segment names to be joins using OS specific slashes.
	       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
	       
	       // The DIRECTORY_SEPARATOR constant is OS specific.
	       return join(DIRECTORY_SEPARATOR, $path_segments);
	    }

	    // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
	    function file_is_an_image($temporary_path, $new_path) {
	        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
	        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
	        
	        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
	        $actual_mime_type        = getimagesize($temporary_path)['mime'];
	        
	        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
	        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
	        
	        return $file_extension_is_valid && $mime_type_is_valid;
	    }
	    
	    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

	    if ($image_upload_detected) { 
	        $image_filename        = $_FILES['image']['name'];
	        $temporary_image_path  = $_FILES['image']['tmp_name'];
	        $new_image_path        = file_upload_path($image_filename);
	        if (file_is_an_image($temporary_image_path, $new_image_path)) {
	            move_uploaded_file($temporary_image_path, $new_image_path);
	        } else{
	        	echo "<script> alert('Please upload an Image!');location.href='edit.php';</script>";
		        exit;
	        }
	    }

	    if ($image_filename != "") {
	    	//$image = "./uploads/" . $image_filename;
	    	$image = join(DIRECTORY_SEPARATOR, ["uploads", $image_filename]);
	    }else{
	    	$image = "";
	    }

	    if ($_POST && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['category'])) {
	        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
	        $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	        $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);

	        //  Build the parameterized SQL query and bind to the above sanitized values.
			$query = "INSERT INTO diaries (title, content, image, category_id, create_date, update_date) VALUES (:title, :content, :image, :category_id, sysdate(), sysdate())";
	        $statement = $db->prepare($query);


	        //  Bind values to the parameters
	        $statement->bindValue(':title', $title);
	        $statement->bindValue(':content', $content);
	        $statement->bindValue(':image', $image);
	        $statement->bindValue(':category_id', $category_id);

	        //  Execute the INSERT.
	        //  execute() will check for possible SQL injection and remove if necessary
	        if($statement->execute()){
	            // Redirect after insert.
	            header("Location: admin.php");
	            exit;
	        }
	    }else{
	    	// Redirect after update.
	        header("Location: process_post.php?");
	        exit;
	    }
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Diary - Post a New Diary</title>
	<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1><a href="index.php">Diary About My Daily Life</a></h1>
		</div>
		<ul id="menu">
			<li><a href="admin.php">Diary Management</a></li>
			<li><a href="create.php" class='active'>New Diary</a></li>
		</ul>
		<div id="all_blogs">
			<form action="create.php" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>New Diary Post</legend>
					<p>
					<label for="title">Title(required)</label>
					<input name="title" id="title" />
					</p>
					<p>
					<label for="content">Content(required)</label>
					<textarea name="content" id="content"></textarea>
					</p>
					<p>
					<label for="image">Image</label>
					<input type="file" name="image" id="image" />
					</p>
					<p>
						<label for="category">Category:</label>
						<select id="category" name="category">
							<option value="" selected="selected">Please choose</option>
									<?php while($category = $statement_category->fetch()): ?>
								    	<option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
								    <?php endwhile ?>
						</select>
					</p>
					<p>
					<input type="submit" name="command" value="Create" />
					</p>
				</fieldset>
			</form>
		</div>
		<?php require_once("footer.php")?>
	</div>
</body>
</html>