<?php 
require_once '../app/Cart.php';
require_once '../app/Product.php';

$action = $_GET['action'];
$product_id = get_product_id($_GET['name']);

$message = "";
$status = false;

if($product_id && $action === 'add_to_cart'){
    $user_id = intval($action['user_id']);
    $product_id = intval($action['product_id']);
    $quantity = intval($action['quantity']);

    if(!$user_id || !$product_id || $quantity <= 0){
        $message = 'error message! Invalid input.';
        error_log($message);
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
    $user_id = intval($action['user_id']);
    $product_id = intval($action['product_id']);
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

header("Location: cart.php?status=" . ($status ? "success" : "error") . "&message=" . urlencode($message));