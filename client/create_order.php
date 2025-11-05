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
require_once '../app/Order.php';
require_once '../app/Product.php';
require_once '../app/Payment.php';

$user_id = $_SESSION['user_id'];
$method_of_delivery = $_POST['delivery_method'];
$payment_id = $_POST['payment_id'];

// cart data
$all_in_stock = true;
$unavailable_products = [];
$total_amount = 0;


$cart_data = get_cart_items($user_id);
$products = [];
foreach($cart_data as $item) {
    $product = get_product_by_id($item['product_id']);
    if(!$product) {
        $all_in_stock = false;
        error_log('Product not found: ' . $item['product_id']);
        continue;
    }
    if($item['quantity'] > $product['stock']) {
        $all_in_stock = false;
        $unavailable_products[] = $product['name'];
    }
    // for entering into order_items
    $products[] = [
        'id' => $product['id'],
        'price' => $product['price'],
        'quantity' => $item['quantity']
    ];
}

if($all_in_stock) {
    $order_status = 'Processing';
} else {
    $order_status = 'Pending';
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
$attempted = 0;
$success = 0;
foreach($products as $product) {
    $attempted++;
    if(add_order_item($order_id, $product['id'], $product['quantity'], $product['price'])) {
        $success++;
    }
}
error_log("Attemped: " . $attempted . "and success: ". $success . " into order_items db");
