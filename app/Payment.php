<?php

// Payment.php implements card details table and payment details
require_once 'DBcontrol.php';

function add_user_card($user_id,$card_number, $card_holder , $expiry, $cvv) {
    $db = new DBcontrol();
    $stmt = $db->prepare("INSERT INTO card_details (id, card_number, card_holder, expiry, cvv) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $user_id, $card_number, $card_holder, $expiry, $cvv);
    return $stmt->execute();
}

function delete_card($user_id, $card_number) {
    $db = new DBcontrol();
    $stmt = $db->prepare("DELETE FROM card_details WHERE id = ? AND card_number = ?");
    $stmt->bind_param("is", $user_id, $card_number);
    return $stmt->execute();
}

function get_card_details($user_id) {
    $db = new DBcontrol();
    $stmt = $db->prepare("SELECT * FROM card_details WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}

function record_card_payment($order_id, $card_id, $amount, $method = null) {
    $db = new DBcontrol();
    if($method == null) $method = 'card';
    $status = "Success";
    $stmt = $db->prepare("INSERT INTO payments (order_id, card_id, amount, payment_method, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iidss", $order_id, $card_id, $amount, $method, $status);
    return $stmt->execute();
}
function record_cash_payment($order_id, $amount, $method = 'cash') {
    $db = new DBcontrol();
    $status = "Success";
    $stmt = $db->prepare("INSERT INTO payments (order_id, amount, payment_method, status) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("idss", $order_id, $amount, $method, $status);
    return $stmt->execute();
}
function get_payment_details($order_id) {
    $db = new DBcontrol();
    $stmt = $db->prepare("SELECT * FROM payments WHERE order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}