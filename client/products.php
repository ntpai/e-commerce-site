<?php
session_start();
require_once '../app/Product.php';
require_once '../app/Image.php';

// Fetch all products order by category

$product_array = get_all_products_category();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Products by Category</title>
    <link rel="stylesheet" href="css/product_card.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
    <style>
        @import url('css/color.css?');
        @import url('css/products_card.css?');
        body {
            padding: 0;
            margin: 0;
            background-color: var(--bg-color);
            font-family: 'Funnel Sans', serif;
            overflow-x: hidden;
        }
        a {
            text-decoration: none; 
            color:inherit;
        } 
        nav {
            background-color: var(--50);
            height: 10vh;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }
        nav h1 {
            font-size: 2rem;
        }


    </style>
</head>
<body>
    <nav>
        <h1><a href="index.php">RETAILO</a></h1>
        <h3>Products</h3>
    </nav>
    <div class="products-container">
        <?php foreach($product_array as $product): ?>
            <div class="product-card">
                <?php
                    $image_binary = get_image($product['id']);
                    $image_type = get_image_type($product['id']);
                    if (!$image_binary) {
                        $image_binary = file_get_contents('assets/image_default.jpg');
                        $image_type = 'image/jpeg';
                    }
                    $image_encode = base64_encode($image_binary);
                ?>
                <img src="data:<?= htmlspecialchars($image_type) ?>;base64,<?= $image_encode ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                <p><?= htmlspecialchars($product['name']) ?></p>
                <button><a href="product_view.php?id=<?= $product['id'] ?>">View</a></button>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>