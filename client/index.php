<?php 
session_start();
require_once '../app/User.php';
require_once '../app/Product.php';
require_once '../app/Image.php';

if (isset($_SESSION["user_id"])) {
    $result = get_userdata_id($_SESSION["user_id"]);
    if($result) {
        $user_data = $result->fetch_assoc();
    }
}

function set_image_src($product_id) {
    $image_base64 = get_image($product_id);
    if($image_base64) {
        $image_type = get_image_type($product_id);
        $base64_image = base64_encode($image_base64);
        return 'data:' . $image_type . ';base64,' . $base64_image;
    }
    return "data:image/jpeg;base64,assests/image_default.jpg";
}

$product_result = get_products_by_sales();
$counter = 0;
if($product_result) {
    $top_results = [];
    $image_srcs = [];
    $url_paths  = [];
    while($row = $product_result->fetch_assoc()) {
        $top_results[$counter][0] = $row['name'];
        $top_results[$counter][1] = $row['stock'];
        $image_srcs[] = set_image_src($row['id']);
        $url_paths[] = "product_view.php?id=" . $row['id'];
        $counter++;
    }
}

?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/index.css?v=<?= time() ?>" />
        <link rel="stylesheet" href="css/navbar.css?v=<?= time() ?>" />
        <link rel="stylesheet" href="css/buttons.css?v=<?= time() ?>" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Funnel+Sans">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
        <title>Retailo</title>
        <script>
            function openPanel(){
                document.getElementById('side-panel').style.display = "block";
            }
            function closePanel(){
                document.getElementById('side-panel').style.display = "none";

            }
        </script>
    </head>    
    <body>
        <nav>
            <h1>RETAILO</h1>
            <div id="menu">
                <div>
                    <form action="search.php" method="get">
                        <input type="text" name="query" placeholder="search product">
                        <input type="hidden" name="category" value="none">
                    </form>
                </div>
                <div class="user-features">
                    <a href="cart.php"><span class="material-symbols-outlined">shopping_bag</span></a>
                </div>

                <div id="side-panel" class="side-panel">
                    <a href="javascript:void(0)" class="closebtn" onclick="closePanel()">&times;</a>
                    <a href="orders.php">Orders</a>
                    <a href="payment.php">Payment</a>
                    <a href="products.php">Products</a>
                    <a href="update_account.php">Update Account</a>
                    <a href="logout.php">Logout</a>
                </div>
                <div class="menu-btn">
                    <button onclick="openPanel()"><span class="material-symbols-outlined">menu</span></button>  
                </div>

            </div>
        </nav>     
        <div>
            <div class="home">
                <img  src="assets/img8.jpg" alt="">
                <img  src="assets/img2.jpg" alt="">
            </div>
            <div class="shop-now">
                <h1>PREMIUM DEVELOPMENT BOARDS</h1>
                <p>Built for Brilliance. Engineered to Endure. Uncompromising Quality.</p>
                <button class="buttons"><a href="products.php">Shop now.</a></button>
            </div>
        </div>
        <div class="featured-products">
    
        <h2 id="products-header">Featured Products</h2>
        <div class="products">
            <?php 
            if (!empty($top_results)) {
                for ($i = 0; $i < $counter; $i++) {
                    ?>
                    <div class="product-card">
                        <img src="<?= htmlspecialchars($image_srcs[$i]) ?>" alt="Product Image">
                        <h3 style="text-align: center;"><?= htmlspecialchars($top_results[$i][0]) ?></h3>
                        <h4 style="text-align: center;">Available : <?= htmlspecialchars($top_results[$i][1]) ?></h4>
                        <button class="buttons">
                            <a href="<?= $url_paths[$i] ?>">View product</a>
                        </button>
                    </div>
                    <?php
                }
            } else {
                echo "<p>No featured products available.</p>";
            }
            ?>
        </div>
        </div>
        <footer>
            <h2>Contact us</h2>
            <br>
            <h3>&#9993; : <a href="mailto:absj@gmail.com"> absj@gmail.com</a></h3>    
        </footer>
    </body>
</html>