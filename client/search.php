<?php
session_start();
require_once '../app/Product.php';
require_once '../app/Image.php';

$categories = get_categories();

if(isset($_GET)){
    $query = trim($_GET['query'] ?? '');
    $category = trim($_GET['category'] ?? '');
    if($category === 'none'){
        $search_results = search($query, null);
    }
    $search_results = search($query, $category);
}
$counter = 0;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Search Products</title>
        <link rel="stylesheet" href="css/product_card.css">
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
            a{
                text-decoration: none;
                color: inherit;
            }
            nav {
                background-color: var(--50);
                height: 8vh;
                padding: 10px 20px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 2rem;
            } 
            form{
                display: flex;
                justify-content: center;
                gap: 10px;
            }
        </style>
    </head>
    <body>
        <nav>
            <h1>
                <a  href="index.php">RETAILO</a>
            </h1>
            <h3>Search Products</h3>
        </nav>
        <div class="search-container">
            <form method="GET">
                <input type="text" name="query" placeholder="Search for products..." required>
                <select name="category">
                    <option value="none">All Categories</option>
                    <?php foreach($categories as $category): ?>
                        <option value="<?= htmlspecialchars($category) ?>"><?= htmlspecialchars($category) ?></option>    
                    <?php endforeach; ?>
                </select>
                <button type="submit">Search</button>
            </form>
            <?php if($search_results): ?>
                <div class="products-container">
                    <?php foreach($search_results as $product): ?>
                        <?php 
                            $counter++;
                            $image_binary = get_image($product['id']);
                            $image_type = get_image_type($product['id']);
                            if (!$image_binary) {
                                $image_binary = file_get_contents('assets/image_default.jpg');
                                $image_type = 'image/jpeg';
                            }
                            $image_encode = base64_encode($image_binary);
                            
                        ?>
                        <div class="product-card">
                            <img src="data: <?= htmlspecialchars($image_type) ?>;base64,<?= $image_encode ?>" 
                            alt="<?= htmlspecialchars($product['name']) ?>">
                            <p><?= htmlspecialchars($product['name']) ?></p>
                            <button><a href="product_view.php?id=<?=$product['id']?>">View</a></button>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <h3 style="margin: 2rem auto; color: red;">No products found!</h3>
            <?php endif; ?>        
        </div>

    </style>
</html>