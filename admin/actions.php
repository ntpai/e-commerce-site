<?php


require_once '../app/Product.php';

$product_count = get_product_count();
$active_products = get_active_products();

$response = [
    "status" => "success",
    "total_products" => $product_count,
    "active_products" => $active_products,
];

header('Content-Type: application/json');
echo json_encode($response);
exit;