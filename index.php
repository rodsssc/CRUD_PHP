<?php
// Include the database connection
$connect = include 'db_connect.php';

// Initialize messages
$error_message = '';
$success_message = '';

// Handle the form submission for deleting a product
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize the product ID
    $product_id = mysqli_real_escape_string($connect, $_POST['product_id']);

    if (empty($product_id)) {
        $error_message = "Product ID is required!";
    } else {
        // Prepare the DELETE query
        $query = "DELETE FROM Products WHERE id = ?";
        $stmt = mysqli_prepare($connect, $query);

        if ($stmt) {
            // Bind parameters and execute
            mysqli_stmt_bind_param($stmt, "i", $product_id);
            if (mysqli_stmt_execute($stmt)) {
                if (mysqli_stmt_affected_rows($stmt) > 0) {
                    $success_message = "Product deleted successfully!";
                } else {
                    $error_message = "No product found with the provided ID.";
                }
            } else {
                $error_message = "Error executing query: " . mysqli_error($connect);
            }
            mysqli_stmt_close($stmt);
        } else {
            $error_message = "Error preparing the statement: " . mysqli_error($connect);
        }
    }
}

// Fetch all products
$query = "SELECT * FROM Products";
$response = mysqli_query($connect, $query);
$products = [];
if ($response) {
    while ($row = mysqli_fetch_assoc($response)) {
        $products[] = $row;
    }
} else {
    die("Query failed: " . mysqli_error($connect));
}

// Close connection
mysqli_close($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        h1 {
            text-align: center;
            color: #34495e;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .messages {
            text-align: center;
            margin-bottom: 20px;
        }

        .success {
            color: #27ae60;
            font-size: 1.1em;
        }

        .error {
            color: #e74c3c;
            font-size: 1.1em;
        }

        .product {
            display: flex;
            justify-content: space-between;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }

        .product:last-child {
            border-bottom: none;
        }

        .product-info {
            flex: 1;
        }

        .product-info p {
            margin: 5px 0;
        }

        .buttons-container {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .button {
            padding: 10px 20px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .delete-button {
            background-color: #e74c3c;
            color: white;
        }

        .delete-button:hover {
            background-color: #c0392b;
        }

        .update-button {
            background-color: #3498db;
            color: white;
        }

        .update-button:hover {
            background-color: #2980b9;
        }

        .add-product-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 12px 18px;
            background: #2ecc71;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            font-size: 1.1em;
        }

        .add-product-button:hover {
            background-color: #27ae60;
        }

        .product-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Product List</h1>

        <!-- Display success or error messages -->
        <div class="messages">
            <?php if (!empty($success_message)): ?>
                <p class="success"><?= htmlspecialchars($success_message) ?></p>
            <?php endif; ?>
            <?php if (!empty($error_message)): ?>
                <p class="error"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
        </div>

        <a href="AddProducts.php" class="add-product-button">Add New Product</a>

        <div class="product-container">
            <?php if (!empty($products)): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product">
                        <div class="product-info">
                            <p><strong>Name:</strong> <?= htmlspecialchars($product['name_products']) ?></p>
                            <p><strong>Price:</strong> $<?= htmlspecialchars($product['price_products']) ?></p>
                            <p><strong>Quantity:</strong> <?= htmlspecialchars($product['qty_products']) ?></p>
                        </div>

                        <div class="buttons-container">
                            <!-- Delete button -->
                            <form method="POST" action="">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                                <button type="submit" class="button delete-button">Remove</button>
                            </form>

                            <!-- Update button -->
                            <a href="UpdateProducts.php?id=<?= htmlspecialchars($product['id']) ?>" class="button update-button">Update</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
