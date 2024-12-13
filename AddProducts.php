<?php
// Include the database connection
$connect = include 'db_connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input data and sanitize it
    $name = mysqli_real_escape_string($connect, $_POST['name_products']);
    $price = mysqli_real_escape_string($connect, $_POST['price_products']);
    $quantity = mysqli_real_escape_string($connect, $_POST['qty_products']);

    // Validate the inputs (ensure all fields are filled)
    if (empty($name) || empty($price) || empty($quantity)) {
        $error_message = "All fields are required!";
    } else {
        // Prepare the insert query using prepared statements
        $query = "INSERT INTO Products (name_products, price_products, qty_products, date_added) VALUES (?, ?, ?, NOW())";
        $stmt = mysqli_prepare($connect, $query);

        if ($stmt) {
            // Bind the parameters to the prepared statement
            mysqli_stmt_bind_param($stmt, "sdi", $name, $price, $quantity);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error: " . mysqli_error($connect);
            }

            // Close the statement
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error preparing the statement: " . mysqli_error($connect);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            font-weight: bold;
            display: block;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            font-weight: bold;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .message {
            padding: 10px;
            margin: 10px 0;
            text-align: center;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Product</h2>

    <?php
    // Display success or error message
    if (isset($success_message)) {
        echo "<div class='message success'>$success_message</div>";
    } elseif (isset($error_message)) {
        echo "<div class='message error'>$error_message</div>";
    }
    ?>

    <!-- Back to index link -->
    <a href="index.php" class="back-link">← Back to Product List</a>

    <!-- Product form -->
    <form method="POST" action="AddProducts.php">
        <div class="form-group">
            <label for="name">Product Name</label>
            <input type="text" name="name_products" required>
        </div>
        <div class="form-group">
            <label for="price">Product Price</label>
            <input type="number" name="price_products" required step="0.01">
        </div>
        <div class="form-group">
            <label for="quantity">Product Quantity</label>
            <input type="number" name="qty_products" required>
        </div>
        <button type="submit" name="submit">Add Product</button>
    </form>
</div>

</body>
</html>
<?php
// Close the database connection safely
if (isset($connect) && is_object($connect)) {
    mysqli_close($connect);
}
?>
