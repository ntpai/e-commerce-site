<?php 
session_start();
if (!isset($_SESSION["user_id"])) {
    // Correct header format: 'Location: <url>' (no space before colon)
    // URL-encode the ref param for safety
    header('Location: signin.php?ref=' . urlencode('cart.php'));
    exit;
}

require_once "../app/Cart.php";
require_once "../app/Product.php";
require_once "../app/Image.php";

// Fetch cart items
$cartItems = get_cart_items($_SESSION["user_id"]);

function image_src($product_id) {
    $image_data = get_image($product_id);
    $image_type = get_image_type($product_id);

    if(!$image_data) {
        error_log("Image not found for product ID: " . $product_id);
        $image_data = file_get_contents('../images/image_default.jpg');
        $image_type = 'image/jpg';
    }

    $image_base64 = base64_encode($image_data);
    return 'data:' . $image_type . ';base64,' . $image_base64;
}

function show_cart_items($cartItems) {
    $subtotal = 0;
    while ($row = $cartItems->fetch_assoc()) {
        $product = get_product_by_id($row['product_id']);
        if ($product){
        $total = $product['price'] * $row['quantity'];
        $subtotal += $total;
        }
        ?>
        <div class="cart-item" style="display:flex;align-items:center;">
            <div id="item">
                <img id="item-img" 
                src="<?= htmlspecialchars(image_src($row['product_id'])) ?>" alt="Product Image">
                <div class="item-name">
                    <h4><?= htmlspecialchars($product['name']) ?></h4>
                </div>
            </div>
            <h4 id="price">&#8377;<?= htmlspecialchars($product['price']) ?></h4>
            <h4 id="qty"><?= htmlspecialchars($row['quantity']) ?></h4>
            <h4 id="total">&#8377;<?= $total ?></h4>
            <form action="cart_process.php" method="post">
                <input type="hidden" name="action" value="remove_from_cart">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['product_id']) ?>">
                <h4><button id="remove-btn">remove</button></h4>
            </form>
    </div>
        <?php
    }
    return $subtotal;
}

$status = isset($_GET['status']) ? $_GET['status'] : null;
$message = isset($_GET['message']) ? $_GET['message'] : null;
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Your Cart</title>
        <link  rel="stylesheet" href="css/cart.css?v=<?= time() ?>" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
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
            <h1>
                <a style="text-decoration: none; color: black;" href="index.php">RETAILO</a>
            </h1>
            <h3>Cart</h3>
        </nav>
        <div class="cart-container">
            <h1 style="text-align:center;">Your Cart</h1>
            <div class="cart">
                <div class="cart-header">
                    <span id="item">Item</span>
                    <span id="price">Price</span>
                    <span id="qty">Quantity</span>
                    <span id="total">Total</span>
                </div>
                <?php 
    
                if ($cartItems && $cartItems->num_rows > 0) {
                    $subtotal = show_cart_items($cartItems);
                } else {
                    echo "<p style='text-align:center;'>Your cart is empty.</p>";
                    $subtotal = 0;
                } 
                ?> 
            </div> 
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>&#8377;<?=  number_format($subtotal, 2); ?></span>
                </div>
                <div class="summary-row grand-total">
                    <span>Grand total:</span>
                    <span>&#8377;<?=  number_format($subtotal, 2) ?></span>
                </div>
                <?php  if ($subtotal > 1000): ?>
                <div class="free-shipping">
                    <p>Congrats, you're eligible for <b>Free Shipping</b></p>
                </div>
                <?php endif; ?>
                <div>
                    <button class="checkout-btn"><a href="order.php">Check out</a></button>
                </div>  
            </div> 
            <?php if ($message != null && $status === 'success'): ?>
                <div class="message">
                    <p><?= htmlspecialchars($message) ?></p>
                </div>
            <?php endif; ?>
        </div> 
    </body>
</html>