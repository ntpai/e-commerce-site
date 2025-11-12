<?php

// this file handles the orders and order_item tables for orders

require_once 'DBcontrol.php';

function fetch_orders() {
    $db_obj = new DBcontrol();
    $query = "SELECT * FROM orders ORDER BY order_date ASC";
    $result = $db_obj->query($query);
    if($result) {
        return $result;
    }
    return null;
}

function get_order_id($user_id) : int | null {
    $db = new DBcontrol();
    $query = "SELECT id FROM orders WHERE user_id = '$user_id' AND status = 
    'Pending' ORDER BY order_date DESC LIMIT 1";
    $result = $db->query($query);
    if($result) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    return null;
}

function get_order_by_id(int $order_id): array | null {
    $db = new DBcontrol();
    $query = "SELECT * FROM orders WHERE id = '$order_id'";
    $result = $db->query($query);
    if($result) {
        return $result->fetch_assoc();
    }
    return null;
}

function update_order_status(string $status, int $order_id): bool {
    $db = new DBcontrol();
    $query = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("si", $status, $order_id);
    $res = $stmt->execute();
    return $res;
}

function get_order_items($order_id) {
    $db = new DBcontrol();
    $query = "SELECT product_id, quantity, price FROM order_items WHERE order_id = '$order_id'";
    $result = $db->query($query);
    if($result) {
        return $result;
    }
    return null;
}

function create_order(int $user_id, int $total_amount,string $method): int | false {
    $db = new DBcontrol();
    $query = "INSERT INTO orders (user_id, total_amount, method) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iis", $user_id, $total_amount, $method);
    if($stmt->execute()) {
        return true;
    }
    return false;
}
function add_order_item(int $order_id, int $product_id, int $quantity, float $price) {
    $db = new DBcontrol();
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES(?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $product_id, $quantity, $price);
    if($stmt->execute()) {
        return true;
    }
    return false;
}