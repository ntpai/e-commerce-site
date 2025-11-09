<?php

/**
 * 
 * Contains data such as revenue from payment table,
 * top selling products, and list of products, data for graph
 * 
 */


require_once '../app/Product.php';
require_once '../app/Payment.php';
require_once '../app/Orders.php';
require_once '../app/DBcontrol.php';

// product details 

// Dashboard requires total count of products which are active/inactive.

$sql = "SELECT COUNT(*) AS total_products FROM products";
$db = new DBcontrol();
$result = $db->query($sql);
$total_products = 0;

if($result) {
    $row = $result->fetch_assoc();
    $total_products = intval($row['total_products']);
}

$active_products = intval(get_active_products()); // function might return string in some case
$inactive_products = $total_products - $active_products;

$result->free_result();

$data = [
    'labels' => ['Active Products' , 'Inactive Products'],
    'values' => [$active_products, $inactive_products]
];

$json_data = json_encode($data);

//  5 most sold products.

$sql = "SELECT id, name, category, sold_count FROM products WHERE status = 'active' ORDER BY sold_count DESC LIMIT 5";
$top_sold = $db->query($sql);

// Total revenue calculated from payment method which has been marked success

$sql = "SELECT sum(amount) as total_amount FROM payments WHERE status = 'Success'";
$result_assoc = $db->query($sql);
$row =  $result_assoc->fetch_row();

$total_amount = $row['total_amount'] ?? 0;   