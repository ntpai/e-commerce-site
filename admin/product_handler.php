<?php
session_start();
require_once "../app/Product.php";

$updation_status = false;

if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = isset($_POST['name']) ? trim($_POST['name']) : null;
    $category = isset($_POST['category']) ? trim($_POST['category']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $stock = isset($_POST['stock']) && $_POST['stock'] !== '' ? intval($_POST['stock']) : null;
    $price = isset($_POST['price']) && $_POST['price'] !== '' ? intval($_POST['price']) : null;
    $status = isset($_POST['status']) ? trim($_POST['status']) : null;

    error_log("Updating product ID: $id");

    $attempted = 0;
    $successes = 0;

    if ($name !== null && $name !== '') {
        $attempted++;
        if (update_product_name($name, $id)) $successes++;
    }

    if ($category !== null && $category !== '') {
        $attempted++;
        if (update_category($category, $id)) $successes++;
    }

    if ($description !== null && $description !== '') {
        $attempted++;
        if (update_description($description, $id)) $successes++;
    }

    if ($stock !== null) {
        $attempted++;
        if (update_stock($stock, $id)) $successes++;
    }

    if ($price !== null) {
        $attempted++;
        if (update_price($price, $id)) $successes++;
    }

    if ($status !== null && $status !== '') {
        $attempted++;
        if (update_status($status, $id)) $successes++;
    }

    if ($attempted === 0) {
        // Nothing to update
        $updation_status = false;
        error_log('No fields provided to update for product ID: ' . $id);
    } elseif ($successes === $attempted) {
        $updation_status = true; // all attempted updates succeeded
    } else {
        // Partial or full failure
        $updation_status = false;
        error_log("Update attempted: $attempted, succeeded: $successes for product ID: $id");
    }

} elseif (isset($_POST['delete'])) {
    $id = intval($_POST['id']);
    delete_product($id);
    header('Location: products.php?action=delete&status=success');
    exit();
}

if ($updation_status) {
    error_log("Product updated successfully.");
    header('Location: products.php?action=modify&status=success');
    exit();
} else {
    error_log("No updates were made for product");
    header('Location: products.php?action=modify&status=failed');
    exit();
}
?>