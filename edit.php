<?php
	/*
		Final Project
		Yubo Yang
		Augest 1, 2022
		Description: This page is used to edit diary by admin users. 
	*/
	
	// Authorization and Database connection.
    require('connect.php');

    // Sanitize the id. Like above but this time from INPUT_GET.
	$diary_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // When click update button
	if($_POST && ($_POST['command'] === 'Update')){
		if(isset($_FILES['image'])){
			// Obtain the image
		    // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
		    // Default upload path is an 'uploads' sub-folder in the current folder.
		    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
		       $current_folder = "./";
		       
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
		        } 
		        else{
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

		    if ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['category'])){
		        // Sanitize user input to escape HTML entities and filter out dangerous characters.
		        $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		        $image_url = filter_var($image, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		        $diary_id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
		        $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);



		        if(trim($title) != '' && trim($content) != ''){
		        	// Build the parameterized SQL query and bind to the above sanitized values.
			        $query_update = "UPDATE diaries SET title = :title, content = :content, image = :image, category_id = :category_id WHERE diary_id = :id";
			        $statement = $db->prepare($query_update);
			        $statement->bindValue(':id', $diary_id, PDO::PARAM_INT);
			        $statement->bindValue(':title', $title);
			        $statement->bindValue(':content', $content);
			        $statement->bindValue(':image', $image_url);
			        $statement->bindValue(':category_id', $category_id);

			        $statement->execute();

			        header("Location: show_admin.php?id=$diary_id");
			        exit;	
		        } else{
		        	// Redirect after update.
		        	echo "<script> alert('Title or Content are required fields！');history.go(-1);</script>";	
		        }
		    }			
		}
		else if(!isset($_FILES['image'])){
			if ($_POST && isset($_POST['title']) && isset($_POST['content']) && isset($_POST['id']) && isset($_POST['category'])){
				// Sanitize user input to escape HTML entities and filter out dangerous characters.
		        $title   = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		        $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
		        $diary_id      = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
		        $category_id = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

		        if(trim($title) != '' && trim($content) != ''){
		        	// Build the parameterized SQL query and bind to the above sanitized values.
			        $query_update = "UPDATE diaries SET title = :title, content = :content, category_id = :category_id WHERE diary_id = :id";
			        $statement = $db->prepare($query_update);
			        $statement->bindValue(':title', $title);
			        $statement->bindValue(':content', $content);
			        $statement->bindValue(':category_id', $category_id);
			        $statement->bindValue(':id', $diary_id, PDO::PARAM_INT);

			        $statement->execute();

			        header("Location: show_admin.php?id=$diary_id");
			        exit;	
		        } 
		        else{
			        // Redirect after update.
			        echo "<script> alert('Title or Content are required fields！');history.go(-1);</script>";	
		        }
			}
		}
	}
	else if($_POST && $_POST['command'] === 'Delete'){
			if ($_POST && isset($_POST['id'])){
				// Sanitize the id. Like above but this time from INPUT_GET.
		    	$diary_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

	    		// Build the parameterized SQL query and bind to the above sanitized values.
	        	$query_delete_diary     = "DELETE FROM diaries WHERE diary_id = :id";
	        	$query_delete_comment     = "DELETE FROM comments WHERE diary_id = :id";

	        	$statement_delete_diary = $db->prepare($query_delete_diary);
	        	$statement_delete_comment = $db->prepare($query_delete_comment);

	        	$statement_delete_diary->bindValue(':id', $diary_id, PDO::PARAM_INT);
	        	$statement_delete_comment->bindValue(':id', $diary_id, PDO::PARAM_INT);

	       		// Execute the Delete.
	        	$statement_delete_diary->execute();
	        	$statement_delete_comment->execute();

	        	// Redirect after update.
	        	header("Location: show_admin.php?id=$diary_id");
	        	exit;
			} 
	} 
	else if($_POST && $_POST['command'] === 'Cancel'){
			if ($_POST && isset($_POST['id'])){
				$diary_id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
				// Redirect after update.
	        	header("Location: show_admin.php?id=${diary_id}");
	        	exit;
			}
    } 

    if (isset($_GET['id'])) { // Retrieve quote to be edited, if id GET parameter is in URL.
	    	// Sanitize the id. Like above but this time from INPUT_GET.
		    $diary_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

		    // Build the parametrized SQL query using the filtered id.
		    $query ="SELECT d.diary_id, d.title d_title, d.content d_content, d.image, d.category_id, d.create_date d_create_date, d.update_date, ca.name FROM diaries d, categories ca WHERE d.category_id = ca.category_id AND d.diary_id = :id";

		    $statement_diary = $db->prepare($query);
		    $statement_diary->bindValue(':id', $diary_id, PDO::PARAM_INT);

		    // Execute the SELECT and fetch the single row returned.
		    $statement_diary->execute();

		    // Judge whether there is a record for the id entered by URL.
		    if($statement_diary->rowCount() < 1){
			    // Redirect after update.
			    header("Location: admin.php");
			    exit;
		    } 

		    $query_category = "SELECT * FROM categories";

		    $statement_category = $db->prepare($query_category);

		    // Execute the SELECT and fetch the single row returned.
		    $statement_category->execute();
		}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>My Diary - Editing <?= $blog['title'] ?></title>
	<link rel="stylesheet" href="styles.css" type="text/css">
</head>
<body>
	<div id="wrapper">
		<div id="header">
			<h1>My Diary - Editing</h1>
		</div>
		<ul id="menu">
			<li><a href="admin.php">Back Diary List</a></li>
			<li><a href="create.php">New Diary</a></li>
		</ul>
		<div id="all_blogs">
			<form action="edit.php" method="post" enctype="multipart/form-data">
			<fieldset>
				<legend>Edit My Diary</legend>
				<?php while($diary = $statement_diary->fetch()): ?>
				<p>
					<label for="title">Title(required)</label>
					<input name="title" id="title" value="<?= $diary['d_title'] ?>" />
				</p>
				<p>
					<label for="content">Content(required)</label>
					<textarea name="content" id="content"><?= $diary['d_content'] ?></textarea>
				</p>
				<p>
					<label for="image">Picture</label>
					<?php if(trim($diary['image']) != ""): ?>
					<div><a href="<?= $diary['image'] ?>"><img class='image' src="<?= $diary['image'] ?>" alt="picture" /></a></div>
					<a href="delete_picture.php?id=<?= $diary['diary_id'] ?>"><span>Delete</span></a>
					<?php else: ?>

					<input type="file" name="image" id="image" />
					<?php endif ?>
				</p>
				<p>
					<label for="category">Category:</label>
					<select id="category" name="category">
						<option value="<?= $diary['category_id'] ?>" selected="selected"><?= $diary['name'] ?></option>
						<?php while($category = $statement_category->fetch()): ?>
						<option value="<?= $category['category_id'] ?>"><?= $category['name'] ?></option>
						<?php endwhile ?>
					</select>
				</p>
				<p>
					<input type="hidden" name="id" value="<?= $diary['diary_id'] ?>" />
					<input type="submit" name="command" value="Update" />
					<input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this diary?')" />
					<input type="submit" name="command" value="Cancel" />
				</p>
				<?php endwhile ?>
			</fieldset>
			</form>
		</div>
		<?php require_once("footer.php")?>
	</div>
</body>
</html>
