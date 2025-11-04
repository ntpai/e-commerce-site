<?php

require_once "../app/Product.php";
require_once "../app/Image.php";

if(!isset($_GET['id'])) {
    error_log("No product ID specified in URL");
    header("Location: index.php");
    exit;
}

$product_id = intval($_GET['id']);
$product = get_product_by_id($product_id);
if(!$product) {
    error_log("Product not found: ID " . $product_id);
    header("Location: index.php");
    exit;
}

$name = $product['name'];
$category = $product['category'];
$description = $product['description'];
$stock = $product['stock'];
$price = $product['price'];


// fetch image
$image_data = get_image($product_id);
$image_type = get_image_type($product_id);


if(!$image_data) {
    error_log("Image not found for product ID: " . $product_id);
    $image_data = file_get_contents('../images/image_default.jpg');
    $image_type = 'image/jpeg';
}

$image_base64 = base64_encode($image_data);
$image_src = 'data:' . $image_type . ';base64,' . $image_base64;

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Product View</title>
        <link rel="stylesheet" href="css/buttons.css?">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <style>
            @import url("css/color.css?");
            body {
                padding: 0;
                margin: 0;
                font-family: 'Funnel Sans', sans-serif;
                background-color: var(--bg-color);
            }
            nav {
                display: flex;
                height: 8vh;
                justify-content: space-between;
                align-items: center;
                padding: 10px 20px;
                background-color: var(--50);
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }
            nav h1 {
                font-size: 24px;
                margin: 0;
            }
            nav h3 {
                font-size: 18px;
                margin: 0;
            }
            .container {
                width: 80%;
                height: 75vh;
                display: flex;
                flex-direction: row;
                align-self: center;
                margin: 2rem auto;
                background-color: var(--50);
                box-shadow: var(--box-shadow-effect);
                border-radius: 10px;
            }
            .img-container {
                width: 50%;
                height: 100%;
                display: flex;
            }
            .img-container img {
                width: 75%;
                height: 70%;
                padding: 20px;
                margin: 20px 0 0 20px;   
            }
            .details-container {
                width: 50%;
                height: auto;
                display: flex;
                flex-direction: column;
                padding: 24px;
            }
            .details-container * {
                margin: 0 0 20px 0;
            }
            .details-container h2, .details-container p {
                color: black;
            }
            .buttons {
                width: 8rem;
                height: 2rem;
            }
            #quantity {
                width: 4rem;
                height: 1.5rem;
            }
        </style>
    </head>
    <body>
        <nav>
            <h1><a style="text-decoration: none;color: inherit; font-size: 2rem;" href="index.php">RETAILO</a></h1>
            <h3>Product Details</h3>
        </nav>
        <div class="container">
            <div class="img-container">
                <img src="<?= $image_src ?>" alt="product_image">
            </div>
            <div class="details-container">
                <h2><?= $name ?></h2>
                <p>Category:  <?= $category?></p>
                <p>Description: <br> <?= $description ?></p>
                <p>Units left: <?= $stock ?></p>
                <p>Price: <?= $price ?></p>     
                <?php if($stock > 1): ?>
                <form method="post" action="cart_process.php">
                    <input type="hidden" value="<?= $product_id?>" name="product_id">
                    <input type="hidden" name="action" value="add_to_cart"> 
                    <label for="quantity"></label>
                    <input id="quantity" type="number" value="1" name="quantity" min="1" max="<?= $stock ?>">
                    <button class="buttons">Add to cart</button>
                </form>
                <?php else: ?>
                    <h3 style="color:red;">Out of Stock</h3>
                <?php endif; ?>  

            </div>
        </div>
    </body>
</html>