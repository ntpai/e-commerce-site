<?php
session_start();
require_once '../app/Orders.php';

function print_order_row($order_list) {

    foreach($order_list as $order) {    
        $order_id = $order['id'];
        $user_id = $order['user_id'];
        $method = $order['method'];
        $order_date = $order['order_date'];
        $status = $order['status'];
        
        echo "<div class='table-row'>
                <div>$order_id</div>
                <div>$user_id</div>
                <div>$method</div>
                <div>$order_date</div>
                <div>$status</div>
                <div><a href='view_order.php?id=".$order_id."'>View</a></div>
              </div>
            ";
    }
}

$orders = fetch_orders();

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
                float:left;
                justify-content: space-between;
                margin-bottom: 2rem;
                padding :1rem;
                align-items: center;
            }
            * {
                text-decoration: none;
                color:black;
            }
            nav * {
                color: var(--text-color);
                font-family: 'Arvo', serif;
                margin: 0 40px 0 5px;
            }
            .orders-container {
                width: 80%;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                margin: auto;
                border-radius: 10px;
                background-color: var(--50);
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            }
            .orders-container h2 {
                margin-bottom: 2rem;
            }
            .table-header, .table-row {
                display: grid;
                grid-template-columns: repeat(6, 1fr);
                gap: 1rem;
                font-weight: bold;
                padding: 1rem;
                border-bottom: 1px solid hsl(0, 0%, 87%);
                padding-bottom: 0.5rem;
                margin-bottom: 1rem;
            }
        </style>
    </head>
    <body>
        <nav>
            <h1><a href="index.php">RETAILO</a></h1>
            <h3>Orders Panel</h3>
        </nav>
        <div class="orders-container">
            <h2>Orders</h2>
            <div class="table-header">
                <div>Order ID</div>
                <div>User ID</div>
                <div>Method</div>
                <div>Order Date</div>
                <div>Status</div>
                <div>Action</div>
            </div>
            
            <?php
                if($orders) {
                    foreach($orders as $order) {
                        print_order_row($order);
                    }
                } else {
                    error_log('No orders found in the database.');
                    echo "<p>No orders found.</p>";
                }
            ?>
        </div>
    </body>
</html>