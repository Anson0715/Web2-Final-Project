<?php
    /*
        Final Project
        Yubo Yang
        Augest 1, 2022
        Description: This page is used to delete admin user, it belongs to the management system.
    */

	// Authorization and Database connection.
    require('connect.php');

	// Sanitize the id. Like above but this time from INPUT_GET.
    $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // Build the parametrized SQL query using the filtered id.
    //$query = "SELECT * FROM blogs WHERE id = :id";
    $query = "DELETE FROM admin WHERE user_id = :id ";

    $statement = $db->prepare($query);
    $statement->bindValue(':id', $user_id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned.
    $statement->execute();

    header("Location: user.php")

?>