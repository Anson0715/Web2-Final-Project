<?php
    /*
        Final Project
        Yubo Yang
        Augest 1, 2022
        Description: This page used to delete comments by admin users.
    */

	// Authorization and Database connection.
    require('connect.php');

	// Sanitize the id. Like above but this time from INPUT_GET.
    $comment_id = filter_input(INPUT_GET, 'comment_id', FILTER_SANITIZE_NUMBER_INT);
    $diary_id = filter_input(INPUT_GET, 'diary_id', FILTER_SANITIZE_NUMBER_INT);

    // Build the parametrized SQL query using the filtered id.
    //$query = "SELECT * FROM blogs WHERE id = :id";
    $query = "DELETE FROM comments WHERE comment_id = :id ";

    $statement = $db->prepare($query);
    $statement->bindValue(':id', $comment_id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned.
    $statement->execute();

    header("Location: show_admin.php?id=$diary_id");

?>