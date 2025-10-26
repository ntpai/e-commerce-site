<?php

header('Content-Type: application/json');

require_once '../app/Product.php';

$product_count = get_product_count();
$active_products = get_active_products();
$inactive_products = get_inactive_products(); 

$response = [
    "status" => "success",
    "total_products" => $product_count,
    "active_products" => $active_products,
    "inactive_products" => $inactive_products,
];

echo json_encode($response);
exit;