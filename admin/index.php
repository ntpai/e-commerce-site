<!-- dashboard.php -->
<?php
session_start();

require_once 'dashboard_data.php';

function list_products(mysqli_result $assoc_array) {

    foreach($assoc_array as $product) {
        echo '<div class="list-row">
            <p># '. $product['id'] .'</p>
            <p>'. $product['name'] .'</p>
            <p>'. $product['category'] .'</p>
            <p>'. $product['sold_count'] .'</p>
        </div>
        ';
    }

}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="css/dashboard.css?v=<?=time() ?>" rel="stylesheet"/>
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
            <div class="product-overview">
                <h3>Product Overview</h3>
                <p>Total count: <?= htmlspecialchars($total_products) ?></p>
                <canvas id="graph" style="max-width: 50px; width:100%;"></canvas>
            </div>
            <div class="revenue-overview">
                <h3>Revenue Overview</h3>
                <p>This Month: &#8377; <?= htmlspecialchars($total_amount) ?> </p> 
            </div>
        </div>
        <div class="product-container">
            <div class="list-header">
                <h3>ID</h3>
                <h3>Name</h3>
                <h3>Category</h3>
                <h3>Sales Figure</h3>
            </div>
            <?php list_products($top_sold)  ?>
        </div>
    </body>
</html>
<script src="https://cdnjs.com/libraries/Chart.js">
    const graphData = <?= $json_data ?>;
    const layout = {title: "Product Status Overview"};
    const ctx = document.getElementById('graph');
    new Chart(ctx, {
        type: 'pie',
        data: {
            labels: graphData.labels,
            datasets: {
                values: graphData.values,
                backgroundColor: [
                    '#008000',
                    '#808080'
                ]
            }
        }, 
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Product Status Overview'
                }
            }
        }
    })
</script>