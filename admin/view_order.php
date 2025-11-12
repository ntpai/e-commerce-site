<?php
session_start();

require_once '../app/Orders.php';
require_once '../app/Product.php';

// condition to update the order status
if(isset($_POST['status'])) {
    $status = $_POST['status'];
    $order_id = $_GET['id'] ?? 0;
    if(update_order_status($status, $order_id)) {
        $message = urlencode("Order status updated successfully.");
        header("Location: orders.php?message=".$message);
        exit;
    }
    else {
        $message = urlencode("Failed to update order status.");
        header("Location: orders.php?message=".$message);
        exit;    
    }
}

function print_products(array $product_name) {
    
    foreach($product_name as $product){    
        echo "<tr>
                <td colspan='2'>". $product . "</td>
            </tr>";
    }
}


$order_id = intval($_GET['id']);

/**
 * @var mixed 
 * gets the data like user id, total amount, method of delivery, 
 * order_date, order status
 */
$order_data = get_order_by_id($order_id); 

/**
 * @var mixed
 * gets the products in the order, quantity of a product and the price calculated for the product.
 */
$order_items = get_order_items($order_id);


/**
 * Fetch all product name first and then assign to an array.
 */
$product_names = [];
foreach($order_items as $item) {
    $product = get_product_name($item['product_id']);
    $product_names[] = $product;
}

// to add: payment variable from payment table

$user_id = $order_data['user_id'];
$method = $order_data['method'];
$total_amount = $order_data['total_amount'];

$date_format = new DateTime($order_data['order_date']);
$order_date = $date_format->format('H:i:s d-m-Y');

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <title>Admin Dashboard</title>
        <style>
            @import url('css/color-palette.css?');
            * {
                padding: 0;
                margin: 0;
            }
            body {
                max-width: 100vw;
                overflow-x: hidden;
                background-color: var(--bg-color);
            }
            nav {
                width: 100%;
                height: 8vh;
                background-color: var(--50);
                display: flex;  
                justify-content: space-between;
                margin-bottom: 2rem;
                padding : 1rem 0;
                align-items: center;
            }
            a {
                text-decoration: none;
                color:black;
            }
            nav * {
                display: flex;
                color: var(--text-color);
                font-family: 'Arvo', serif;
                margin: 0 10px;
            }
            nav h3{
                margin-right: 30px;
            }

            .order-details {
                width: 50%;
                background-color: var(--50);
                padding: 1rem;
                margin: auto;
                border-radius: 8px;
                font-style: normal;
                
            }
            .order-details span {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
            .order-details span select, 
            .order-details span button {
                padding: 0.4rem 0.6rem;
                font-size: 1rem;
                border-radius: 5px;
                border: none;
            }
            .order-details span button {
                cursor: pointer;
            }
            h3{
                font-weight: normal;
                margin: 1rem 0;
            }

            .container-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-bottom: 0.5rem;
                margin-bottom: 1rem;
            }
            table{
                width: 100%;
                border: none;
            }
            th, td {
                text-align: left;
                padding: 8px;
            }
            ol li {
                margin: 0 0 0.5rem 3rem;
            }
        </style>
    </head>
    <body>
        <nav>
            <h1><a href="index.php">RETAILO</a></h1>
            <h3>Orders Panel</h3>
        </nav>
        <div class="order-details">
            <div class="container-header">
                <h2>Order # <?= htmlspecialchars($_GET['id'] ?? ''); ?></h2>
                <span>
                    <h3>Order status</h3>
                    <form method="post">
                        <select name="status">
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit">Update</button>
                    </form>
                </span>
            </div>
            <table>
                <tr>
                    <th>User ID</th>
                    <td># <?= htmlspecialchars($user_id) ?></td>
                </tr>
                <tr>
                    <th>Order Placed on</th>
                    <td><?= htmlspecialchars($order_date) ?></td>
                </tr>
                <tr>
                    <th>Method</th>
                    <td><?= htmlspecialchars($method) ?></td>
                </tr>
                <tr>
                    <th>Total Amount</th>
                    <td><?= htmlspecialchars($total_amount) ?></td>
                </tr>
                <tr>
                    <th>Payment option</th>
                    <td>to be added!</td>
                </tr>
                <tr>
                    <td colspan='2'><strong>Products</strong></td>
                </tr>
                <?php 
                    print_products([null]);
                ?>
            </table>
        </div>
    </body>
</html>