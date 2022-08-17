<?php
    /*
        Final Project
        Yubo Yang
        Augest 1, 2022
        Description: This page used to delete a specific category.
    */

	// Authorization and Database connection.
    require('connect.php');

	// Sanitize the id. Like above but this time from INPUT_GET.
    $category_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
echo $category_id;
echo "dsdsdsdsdsdsds";
    // Build the parametrized SQL query using the filtered id.
    //$query = "SELECT * FROM blogs WHERE id = :id";
    $query = "DELETE FROM categories WHERE category_id = :id ";

    $statement = $db->prepare($query);
    $statement->bindValue(':id', $category_id, PDO::PARAM_INT);

    // Execute the SELECT and fetch the single row returned.
    $statement->execute();

    header("Location: categories.php");

?>