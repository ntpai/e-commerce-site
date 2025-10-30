<!-- dashboard.php -->
<?php
session_start();


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="css/dashboard.css?" rel="stylesheet"/>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arvo">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
        <title>Admin Dashboard</title>
    </head>
    <body>
        <nav>
            <div>
                <h1>RETAILO</h1>
            </div>
            <div class="nav-links">
                <a href="products.php">Products</a>
                <a href="orders.php">Orders</a>
                <a href="users.php">Users</a>
            </div>
        </nav>
        <div class="overview">
        </div>
    </body>
        <script src="js/dashboard.js"></script>   
</html>