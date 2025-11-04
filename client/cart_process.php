<?php 
session_start();

require_once '../app/Cart.php';
require_once '../app/Product.php';

$action = $_POST['action'];
$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id'];

$message = "";
$status = false;

if($product_id && $action === 'add_to_cart'){
    $product_id = intval($_GET['product_id']);
    $quantity = intval($_GET['quantity']);

    if(!$user_id || !$product_id || $quantity <= 0){
        $message = 'error message! Invalid input.';
        error_log($message);
    }
    if(get_cart_items($user_id)){
        update_item_quantity($user_id, $product_id, $quantity);
        $message = 'success! Item quantity updated in cart.';
        error_log("Updated quantity of product ID: $product_id for user ID: $user_id to $quantity");
    }
    if(add_item($user_id, $product_id, $quantity)){
        $message = 'success! Item added to cart.';
        error_log($message);  $status = true;
    } else {
        $message = 'error! Failed to add item to cart.';
        error_log($message);
    }
}

if($product_id && $action === 'remove_from_cart'){
    $product_id = intval($_GET['product_id']);
    if(!$user_id || !$product_id){
        $message = 'error message! Invalid input.';
        error_log($message);
    }
    if(remove_item($user_id, $product_id)){
        $message = "success! Item removed from cart.";
        error_log($message); $status = true;
    } else {
        $message= "error! Failed to remove item from cart.";
        error_log($message);
    }

}

$ref=$_GET['ref'] ?? 'cart';
header("Location: ".$ref .".php?status=" . ($status ? "success" : "error") . "&message=" . urlencode($message));
exit;