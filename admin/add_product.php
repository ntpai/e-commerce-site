<?php

$message = "";
require_once '../app/Product.php';
require_once '../app/Image.php';

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $image_data = $_FILES['image']['tmp_name'];
    $image_type = $_FILES['image']['type'];

    $name= $_POST['name'];
    $category= $_POST['category'];
    $description= $_POST['description'];
    $price= $_POST['price'];
    $stock= $_POST['stock']; 
    if(!$name || !$category || !$description || !$price || !$stock || !$image_data) 
    {
        error_log("All fields are required.");
        exit;
    }
    $product = add_product($name, $category, $description, $price, $stock); 
    $product_id = get_product_id($name);
    if(!$product_id) {
        error_log("Failed to add product.");
        exit;
    }
    $image_id = add_image($name, $product_id, $image_type, $image_data);
    if(!$image_id) {
        error_log("Failed to upload image.");
        delete_product($product_id); // Rollback product addition if image upload fails
    }
    $message = "Product added successfully.";
    $success = true;

}
if($success) {
    header('refresh: 1; url=products.php');
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Admin Panel</title>
    <link href="css/main.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <style>
        @import url('css/color-palette.css');
        .container {
            width: 40%;
            height: fit-content;
            margin: 2rem auto;
            padding: 1rem;
            border-radius: 8px;
            background-color: var(--50);
        }
        .container h2{
            margin-bottom: 1rem;
        }
        form {
            display: block;
        }
        label {
            font-weight: 500;
        }
        input:not([type="file"]){
            width: 40%;
            padding: 0.5rem;
            margin-top: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid black;
            border-radius: 4px;
        }
        input[type="file"] {
            border: none;
            padding: 0;
            margin: 1rem 0 1rem 0;
        }
        .buttons {
            width: 40%;
            padding: 0.5rem;
        }
    </style>
</head>
<body>
    <nav>
        <div>
            <h1>RETAILO</h1>
        </div>
        <div>
            <h3>Products Panel</h3>
        </div>
    </nav>
    <div class="container">
        <h2>Add New Product</h2>
        <?php if(isset($message) ): ?>
            <em><?php echo htmlspecialchars($message); ?></em><br>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Product Name:</label><br>
            <input type="text" id="name" name="name" required><br><br>

            <label for="category">Category:</label><br>
            <input type="text" id="category" name="category" required><br><br>

            <label for="description">Description:</label><br>
            <textarea id="description" name="description" rows="4" cols="40" required></textarea><br><br>

            <label for="price">Price:</label><br>
            <input type="number" id="price" name="price" step="1" required><br><br>

            <label for="stock">Stock Quantity:</label><br>
            <input type="number" id="stock" name="stock" step="1" value="0" required><br><br>

            <label for="image">Product Image:</label><br>
            <input type="file" id="image" name="image" accept="image/*" required><br><br>

            <button class="buttons" type="submit" name="add_product">Add Product</button>
        </form> 
    </div>
</body>
</html>
