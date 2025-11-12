<?php
session_start();
require_once '../app/Product.php';
require_once '../app/Image.php';

// Fetch all products order by category

$product_array = get_all_products_category();

$message = isset($_GET['message']) ? $_GET['message'] : null;

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

        .message {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 300px;
            max-width: 90vw;
            z-index: 1000;
            padding: 0.5rem 2rem;
            border: 1px solid black ;
            border-radius: 8px;
            background-color: #e6f9e6;
            color: black;
            text-align: center;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            font-size: 1.1rem;
            animation: fadeInOut 3s forwards;
        }
        @keyframes fadeInOut {
            0% { opacity: 0; top: 0; }
            10% { opacity: 1; top: 20px; }
            90% { opacity: 1; top: 20px; }
            100% { opacity: 0; top: 0; }
        }
    </style>
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            var msg = document.querySelector('.message');
            if(msg) {
                setTimeout(function() {
                    // to remove the GET params from url
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 3000);
            }
        });
    </script>
</head>
<body>
    <nav>
        <h1><a href="index.php">RETAILO</a></h1>
        <h3>Products</h3>
    </nav>
    <?php if ($message): ?>
        <div class="message">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>
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
    <p style="text-align: center;font-size: large;">
        <a style="text-decoration: underline; color: blue;" href="search.php">Search for products </a>
    </p>
</body>
</html>