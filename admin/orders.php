<?php
session_start();
require_once '../app/Orders.php';


$message = isset($_GET['message']) ? $_GET['message'] : null;

function print_order_row($order_assoc) {

    foreach($order_assoc as $order_row) {    
        $order_id = $order_row['id'];
        $user_id = $order_row['user_id'];
        $method = $order_row['method'];
        $order_date = $order_row['order_date'];
        $status = $order_row['status'];
        
        echo "<div class='table-row'>
                <div>".$order_id . "</div>
                <div>".$user_id. "</div>
                <div>".$method . "</div>
                <div>".$order_date. "</div>
                <div>".$status ."</div>
                <div><a id='view-btn' href='view_order.php?id=".$order_id."'>View</a></div>
              </div>
            ";
    }
}

$orders = fetch_orders()
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
            .table-row {
                font-weight: normal;
            }
            #view-btn {
                padding: 0.5rem 1rem;
                background-color: var(--primary-color);
                border-radius: 5px;
                font-size: 0.9rem;
            }
            .message {
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                min-width: 300px;
                max-width: 90vw;
                z-index: 1000;
                padding: 1rem 2rem;
                border: 1px solid black;
                border-radius: 8px;
                background-color: #fff0f0;
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
            <h3>Orders Panel</h3>
        </nav>
        <?php if($message): ?>
            <div class="message">
                <p><?php echo htmlspecialchars($message); ?></p>
            </div>
        <?php endif; ?>
        <div class="orders-container">
            <h2>Orders</h2>
            <div class="table-header">
                <div>Order ID</div>
                <div>User ID</div>
                <div>Method</div>
                <div>Order Date</div>
                <div>Status</div>
            </div>
            
            <?php
                if($orders != null) {
                    print_order_row($orders);
                } else {
                    error_log('No orders found in the database.');
                    echo "<p>No orders found.</p>";
                }
            ?>
        </div>
    </body>
</html>