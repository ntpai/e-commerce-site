<?php

require_once '../app/Product.php';

if(isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $product_data = get_product_by_id($id);
    $name = $product_data['name'];
    $category = $product_data['category'];
    $description = $product_data['description'];
    $stock = $product_data['stock'];
    $price = $product_data['price'];
}

?>
<DOCTYPE html>
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
            a {
                text-decoration: none;
            }
            nav * {
                color: var(--text-color);
                font-family: 'Arvo', serif;
                margin: 0 40px 0 5px;
            }
            .modify-container {
                width: 30%;
                padding: 2rem;
                display: flex;
                flex-direction: column;
                margin: auto;
                border-radius: 10px;
                background-color: var(--50);
                box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            }

            .modify-container h2, input, textarea {
                margin-bottom: 1rem;
            }
            .modify-container input {
                width: 100%;
                height: 2rem;  
            }
            .inline-inputs {
                display: flex;
                align-items: first baseline;
                gap:0.5rem;
                line-height: 2.5rem;
            }
            .inline-inputs input{
                width: 5rem;
                height: 1.5rem;
            }
            .buttons {
                display: flex;
                justify-content: space-evenly;
            }
            .buttons button{
                width: 8rem;
                height: 1.8rem;
                align-self: center;
            }
        </style>
    </head>
    <body>
        <nav>
            <h1><a href="index.php">RETAILO</a></h1>
            <h3>Product modification Panel</h3>
        </nav>
        <div class="modify-container">
            <h2>Modify Product #<?= htmlspecialchars($_GET['id']) ?></h2>
            
            <form method="POST" action="product_handler.php?>">
                <label for="id-number">Id</label>
                <input type="text" name="id-number" value="<?= htmlspecialchars($_GET['id']) ?>" disabled>
                <label for="name">Name</label>
                <input type="text" name="name" placeholder=" <?= htmlspecialchars($name)  ?>">
                <label for="category">Category</label>
                <input type="text" name="Category" placeholder=" <?= htmlspecialchars($category)?>">
                <label for="description">Description</label>
                <textarea name="description" rows="4" cols="41" placeholder="<?= htmlspecialchars($description) ?>"></textarea>
                <div class="inline-inputs">
                    <label for="price">Price</label>
                    <input type="number" name="price" placeholder="<?= htmlspecialchars($price) ?>">
                    <label for="stock">Stock</label>
                    <input type="number" name="stock" placeholder="<?= htmlspecialchars($stock) ?>">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="buttons">
                    <button value="update">Update</button>
                    <button value="delete">Delete</button>
                </div>
            </form>
        </div>
    </body>
</html>