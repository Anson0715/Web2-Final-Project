<?php
    /*
        Final Project
        Yubo Yang
        Augest 1, 2022
        Description: This page is used to update admin user, it belongs to the management system.
    */

    // Authorization and Database connection.
    require('connect.php');

    // Sanitize the id. Like above but this time from INPUT_GET.
    $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

     // UPDATE quote if author, content and id are present in POST.
    if($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Update'){

        if ($_POST && isset($_POST['user_name']) && isset($_POST['password']) && trim($_POST['user_name']) != "" && trim($_POST['password']) != "") {
            // Sanitize user input to escape HTML entities and filter out dangerous characters.
            $user_name  = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $password  = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Build the parameterized SQL query and bind to the above sanitized values.
            $query     = "UPDATE admin SET user_name = :user_name, password = :password WHERE user_id = :user_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':user_name', $user_name);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':user_id', $user_id, PDO::PARAM_INT);

            // Execute the Update.
            if($statement->execute()){
                // Redirect after update.
                header("Location: user.php");
                exit;
            } else{
                // Redirect after update.
                header("Location: user.php");
                exit;
                }
            } else{
                echo "<script> alert('User Name and password are required fields！');location.href='user.php';</script>";
                exit;
            }
        }else if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['command'] == 'Cancel') {
                // Redirect after update.
                header("Location: user.php");
                exit;
        }else if(isset($_GET['id'])){

            $query_user = "SELECT * FROM admin WHERE user_id = :user_id";

            // A PDO::Statement is prepared from the query
            $statement_user = $db->prepare($query_user);
            $statement_user->bindValue(':user_id', $user_id, PDO::PARAM_INT);

            // Execution on the DB server is delayed until we execute().
            //$statement->execute();
            $statement_user->execute();

            if($statement_user->rowCount() < 1){
                // Redirect after update.
                header("Location: user.php");
                exit;
            } else{
                $user = $statement_user->fetch();
        }
    }

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
        <ul id="menu">
            <li><a href="admin.php">Diary Management</a></li>
            <li><a href="categories.php">Category Management</a></li>
            <li><a href="sortby.php">Sort management</a></li>
            <li><a href="user.php" class='active'>User management</a></li>
        </ul>
        <div id="all_blogs">
            <div class="blog_post">
            <form method="post" enctype="multipart/form-data">
                <fieldset>
                <legend>Update User Information</legend>
                <p>
                    <label for="user_name">User Name</label>
                    <input name="user_name" id="user_name" value="<?= $user['user_name'] ?>" />
                </p>
                <p>
                    <label for="password">Password</label>
                    <input type="Password" name="password" id="password" value="<?= $user['password'] ?>" />
                </p>
                <p>
                    <input type="hidden" name="id" value="<?= $user['user_id'] ?>" />
                    <input type="submit" name="command" value="Update" />
                    <input type="submit" name="command" value="Cancel" />
                </p>
            </fieldset> 
            </form>
            </div>
        </div>
    <?php require_once("footer.php")?>
    </div>
</body>
</html>
