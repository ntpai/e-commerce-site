<?php 

session_start();
require_once "../app/Cart.php";

$user_id = $_SESSION['user_id'] ?? null;
$product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
$action = $_POST['action'] ?? null;

$status = true;

if($action === 'add_to_cart' && $product_id ) {
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    if(item_exits($user_id, $product_id)) {
        update_item_quantity($user_id, $product_id, $quantity);
        $message = "success! Item quantity updated in cart.";
    }
    elseif(add_item($user_id, $product_id, $quantity)){
        $message = "success! Item added to cart.";
    } else {
        $status = false;
        $message = "error! Failed to add item to cart.";
    }
}

if($product_id && $action === 'remove_from_cart'){
    if(!$user_id || !$product_id){
        $message = "error message! Invalid input.";
    }
    if(remove_item($user_id, $product_id)){
        $message = "success! Item removed from cart.";
    } else {
        $status = false;
        $message= "error! Failed to remove item from cart.";
    }
}

header('Location: products.php?message=' . urlencode($message));