<?php

require_once '../app/Product.php';

$message  = "";

$delete_status = false;
$delete_message = "";
if(isset($_GET['action']) && isset($_GET['status'])) {
    $action = $_GET['action'];
    $status = $_GET['status'];
    if($action === 'delete' && $status === 'success') {
        $delete_status = true;
        $delete_message = "Product deleted successfully.";
    } else if($action === 'delete' && $status === 'failed') {
        $delete_message = "Failed to delete product. Try again.";
    }
}

$products = get_all_products();
$i = 0;

if($products->num_rows > 0) {
    while($row = $products->fetch_assoc()) {
        $products_list[] = [
            "id" => $row['id'],
            "name" => $row['name'],
            "category" => $row['category'],
            "stock" => $row['stock'],
            "price" => $row['price'],
            "created_at" => $row['created_at']
        ];
        $i++;
    }
} else {
    $counter = 0;
    $message = "No products found. Fetch error?";
}
$counter = $i;
function list_products(int $counter, $product_list ) {
    global $products_list;
    $i = 0; $j = 0;
    for(; $i < $counter; $i++) {
        echo '<div class="product-row" data-id="'. $product_list[$i]['id'] .'">
            <span><p>'.$products_list[$i]['name'].'</p></span>
            <span><p>'.$products_list[$i]['category'].'</p></span>
            <span><p>'.$products_list[$i]['stock'].'</p></span>
            <span><p>&#8377;'.$products_list[$i]['price'].'</p></span>
            <span><p>'.$products_list[$i]['created_at'].'</p></span>
            <span>
                <button><a 
                href="actions.php?action=modify&id='.$product_list[$i]['id'].'">Modify</a></button>
            </span>
        
        </div>';
    }
}   
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <title>Products - Admin Panel</title>
        <style>
            @import url('css/color-palette.css');
            *{
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                color: black;
            }
            a {
                text-decoration: none;
            }
            body{
                font-family: 'Funnel Sans';
                background-color: hsl(0, 0%, 87%);
            }
            nav {
                background-color: var(--50);
                width: 100%;
                height: 10vh;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 2rem;
            }
            nav * {
                display: inline;
            }

            .heading-section {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 1rem;
            }
            .failed-message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                min-width: 300px;
                max-width: 90vw;
                z-index: 1000;
                padding: 1rem 2rem;
                border: 1px solid #ff3b3b;
                border-radius: 8px;
                background-color: #fff0f0;
                color: #b71c1c;
                text-align: center;
                box-shadow: 0 8px 24px rgba(0,0,0,0.18);
                font-size: 1.1rem;
                animation: fadeInOut 3s forwards;
            }
            .success-message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                min-width: 300px;
                max-width: 90vw;
                z-index: 1000;
                padding: 1rem 2rem;
                border: 1px solid #4BB543;
                border-radius: 8px;
                background-color: #e6f9e6;
                color: #256029;
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
            .action {
                text-decoration: none;
                color: black;
            }
            .action:hover{
                color: hsl(0, 0%, 60%);
                text-decoration: underline; 
            }
            .products-container {
                margin: 2rem;
                padding: 2rem;
                background-color: var(--50);
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            .products-container h3 {
                margin-bottom: 2rem;
            }
            .product-list {
                width: 100%;
                height: fit-content;
                margin-top: 1rem;
                border: 1px solid var(--800);
                border-radius: 8px;
            }
            .list-header, .product-row{ 
                width: 100%;
                height: 3rem;
                padding: 1rem  2rem;
                border-bottom: 1px solid var(--800);    
            }
            .product-row:last-child{
                border-bottom: none;
            }
            .list-header span{
                width: 16%; 
                display: inline-block;
                font-weight: bold;
                text-align: left;
                padding: 0 0.4rem;
            }

            .product-row span{
                width:16%; 
                padding: 0 0.4rem;
                height: 1.5rem;
                display: inline-block;
                text-align: left;
            }
            .product-row button{
                padding: 1px;
                margin: auto 3px;
            }
        </style>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var msg = document.querySelector('.success-message, .failed-message');
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
            <div>
                <h1><a href="index.php">RETAILO</a></h1>
            </div>
            <div>
                <h3>Products Panel</h3>
            </div>
        </nav>
        <?php if($delete_message): ?>
            <?php if($delete_status): ?>
                <div class="success-message">
                    <em><?php echo htmlspecialchars($delete_message); ?></em>
                </div>
            <?php else: ?>
                <div class="failed-message">
                    <em><?php echo htmlspecialchars($delete_message); ?></em>
                </div>    
            <?php endif; ?>
        <?php endif; ?>
        <div class="products-container">
            <div class="heading-section">
                <h2>Products list</h2>
                <h4><a  class="action" href="add_product.php">Add Product</a></h4>
            </div>
            <div class="product-list">
                <div class="list-header">
                    <span>Name</span>
                    <span>Category</span>
                    <span>Stock</span>
                    <span>Price</span>
                    <span>Date Added</span>
                    <span>Actions</span><!-- edit/delete -->
                </div> 
                <?php
                if($message) {
                    echo '<div class="product-row"><span><p>'.$message.'</p></span></div>';
                } else {
                    list_products($counter,$products_list);
                }
                ?>
            </div>
        </div>

    </body>
</html>
