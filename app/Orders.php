<?php

// this file handles the orders and order_item tables for orders

require_once 'DBcontrol.php';

function fetch_orders(): array|null {
    $db_obj = new DBcontrol();
    $query = "SELECT * FROM orders ORDER BY order_date DESC";
    $result = $db_obj->query($query);
    if($result) {
        return $result->fetch_assoc();
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
        return $result->fetch_assoc();
    }
    return null;
}