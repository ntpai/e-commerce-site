<?php
require_once "../app/Product.php";
$updation_status = false;

$id = null;

if(isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = $_POST['name'] ?? null;
    $category = $_POST['category'] ?? null;    
    $description = $_POST['description'] ?? null;
    $stock = isset($_POST['stock']) ? intval($_POST['stock']) : null;
    $price = isset($_POST['price']) ? intval($_POST['price']) : null;
    $status = $_POST['status'] ?? null;

    error_log("Updating product ID: $id");

    if ($name !== null) {
        update_product_name($name, $id);
        $updation_status = true;
    }   
    if ($category !== null) {
        update_category($category, $id);
        $updation_status = true;
    }
    if ($description !== null) {
        update_description($description, $id);
        $updation_status = true;
    }
    if ($stock !== null) {
        update_stock($stock, $id);
        $updation_status = true;
    }
    if ($price !== null) {
        update_price($price, $id);
        $updation_status = true;
    }
    if ($status !== null) {
        update_status($status, $id);
        $updation_status = true;
    }

} elseif (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    delete_product($id);
    header('Location: products.php?action=delete&status=success');
    exit();
}

if ($updation_status) {
    global $id;
    error_log("Product ID: $id updated successfully.");
    header('Location: products.php?action=modify&status=success');
    exit();
} else {
    global $id;
    error_log("No updates were made for product ID: $id");
    header('Location: products.php?action=modify&status=failed');
    exit();
}
?>