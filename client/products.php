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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
    <style>
        @import url('css/color.css?');
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
        .products-container {
            display: flex;
            flex-wrap: wrap;
            padding: 2rem;  
            justify-content: flex-start;
            gap: 2rem;
        }

        .product-card {
            width: 200px;
            height: 300px;            
            background-color: var(--50);
            border: 1px solid var(--bg-color);
            transition: 0.3s ease-in-out;
            box-shadow: var(--box-shadow-effect);
        }
        .product-card:hover{
            transform: scale(1.01);
            box-shadow: 0 12px 20px 0 rgba(0, 0, 0, 0.5);
        }
        .product-card img {
            width: 100%;
            height: 60%;
            padding: 4px 0;
        }
        .product-card p {
            text-align: center;
            padding: 4px 0;
        }
        .product-card button {
            display: block;
            width: 50%;
            margin: 0 auto;
            padding: 10px 20px;
            background-color: black;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
        }
        .product-card button:hover {
            background-color: #333;
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