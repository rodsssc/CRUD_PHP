<?php
// Include the database connection
$connect = include 'db_connect.php';

// Initialize messages
$error_message = '';
$success_message = '';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the product ID from the form input and sanitize it
    $product_id = mysqli_real_escape_string($connect, $_POST['product_id']);

    // Validate the product ID
    if (empty($product_id)) {
        $error_message = "Product ID is required!";
    } else {
        // Prepare the DELETE query using prepared statements
        $query = "DELETE FROM Products WHERE id = ?";
        $stmt = mysqli_prepare($connect, $query);

        if ($stmt) {
            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "i", $product_id);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Check if a row was actually deleted
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $success_message = "Product deleted successfully!";
                } else {
                    $error_message = "No product found with the provided ID.";
                }
            } else {
                $error_message = "Error executing query: " . mysqli_error($connect);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error preparing the statement: " . mysqli_error($connect);
        }
    }
}

// Close the database connection
if (isset($connect) && is_object($connect)) {
    mysqli_close($connect);
}
?>