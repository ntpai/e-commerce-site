<?php

/*

The program handles the processing of orders.
Steps involved:

1. Obtain the user ID from session.
2. Retrive the products in the user's carts.
3. For each product, check if there is sufficient stock.
4. If stock is sufficient, create an order entry and reduce the stock and 
    increase the sold_count in the database. Change the order status to completed.
5. If any product has insufficient stock, keep the order in pending state.
6. Clear the user's cart after processing.
7. Redirect the user to orders page.

Explaination of the code:

Using the user ID from the session, the code fetches the items from the user cart.
If the products contains enough stock, it creates the order with status set to processing,
otherwise it sets the status to pending. 

The userID, method of delivery, total amount and status are created in the orders
table while each product in the cart is added to the order_items table.
And with the card details provided, the payment is recorder in payments table.

Finally, the cart is cleared and uer is redirected to orders page with appropriate status message.
*/

session_start();
require_once '../app/Cart.php';
require_once '../app/Orders.php';
require_once '../app/Product.php';
require_once '../app/Payment.php';


$user_id = $_SESSION['user_id'];
$method_of_delivery = isset($_POST['delivery_method']) ? $_POST['delivery_method'] : 'Delivery';

// cart data
$all_in_stock = true;
$unavailable_products = [];
$total_amount = 0;


$cart_data = get_cart_items($user_id);
$products = [];
foreach($cart_data as $item) {
    $product = get_product_by_id($item['product_id']);
    if(!$product) {
        error_log('Product not found: ' . $item['product_id']);
        continue;
    }
    // for entering into order_items
    $products[] = [
        'id' => $product['id'],
        'price' => $product['price'],
        'quantity' => $item['quantity']
    ];
}


foreach($products as $prod) {
    $total_amount += $prod['price'] * $prod['quantity'];
}

$order_id = create_order($user_id, $total_amount, $method_of_delivery);
if(!$order_id) {
    error_log('Error creating order. Please try again.');
    exit;
}

$order_id = get_order_id($user_id);

foreach($products as $product) {
    add_order_item($order_id, $product['id'], $product['quantity'], $product['price']);    
}

// remove items from cart
clear_cart($user_id);


?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Orders</title>
        <link rel="stylesheet" href="css/navbar.css?v=12">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <style>
            @import url('css/color.css?');
            *{
                font-family: 'Funnel Sans', sans-serif;
                margin: 0;
                padding: 0;
            }
            body{
                background-color: var(--bg-color);
            }
            .container {
                max-width: 400px;
                height: fit-content;
                margin: 50px auto;
                padding: 20px;
                background-color: var(--card-bg);
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                background-color: var(--50);
            }
            .container h2 {
                text-align: center;
                margin-bottom: 20px;
            }
            form *{
                margin-bottom: 10px;
            }
            form input, form select, form button {
                width: 100%;
                padding: 10px;
                margin-top: 10px;
                border: 1px solid #ccc;
                border-radius: 4px;
                box-sizing: border-box;
            }
        </style>
    </head>

    <body>      
        <nav>
            <h1>RETAILO</h1>
        </nav>  
        <div class="container">
            <h2>Order Created Successfully!</h2>
            <p>Your order ID is: <?=  htmlspecialchars($order_id) ?></p>
            <p>Total Amount: $<?= htmlspecialchars(number_format($total_amount, 2)) ?></p>
            <button><a href="payment_process.php?order_id=<?= $order_id ?>">Continue with Payment</a></button>
        </div>
    </body>
</html>