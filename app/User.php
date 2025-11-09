<?php

require_once 'DBcontrol.php';

function get_userdata_id($id) {
    $db = new DBcontrol();
    $email = $db->real_escape_string($id);
    $sql = "SELECT * FROM users WHERE id = '$id'";
    $res = $db->query($sql);
    return $res;
}
function get_userdata_email($email){
    $db = new DBcontrol();
    $email = $db->real_escape_string($email); 
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $res = $db->query($sql);
    $data = $res->fetch_assoc();
    if($data) {
        return $data;
    } else {
        return false;
    }
}

function is_valid_email($email) {
    $db = new DBcontrol();
    $email = $db->real_escape_string($email); 
    $sql = "SELECT id FROM users WHERE email = '$email'";
    $res = $db->query($sql);
    return ($res && $res->num_rows > 0);
}
function is_valid_phone($phone) {
    $db = new DBcontrol();
    $phone = $db->real_escape_string($phone);
    $sql = "SELECT id FROM users WHERE phone = '$phone'";
    $res = $db->query($sql);
    return ($res && $res->num_rows > 0);
}


function insert_user_data($username, $phone, $address, $email, $password_hash) {
    $db = new DBcontrol();
    $sql = "INSERT INTO users (name, phone, address, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("sssss", $username, $phone, $address, $email, $password_hash);
    return $stmt->execute();
}

function update_password($id, $new_password_hash) {
    $db = new DBcontrol();
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("si", $new_password_hash, $id);
    return $stmt->execute();
}

function update_address($id, $new_address) {
    $db = new DBcontrol();
    $sql = "UPDATE users SET address = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("si", $new_address, $id);
    return $stmt->execute();
}

function update_phone($id, $new_phone) {
    $db = new DBcontrol();
    $sql = "UPDATE users SET phone = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("si", $new_phone, $id);
    return $stmt->execute();
}

function update_email($id, $new_email) {
    $db = new DBcontrol();
    $sql = "UPDATE users SET email = ? WHERE id = ?";
    $stmt = $db->prepare($sql);

    if ($stmt === false) {
        return false;
    }
    $stmt->bind_param("si", $new_email, $id);
    return $stmt->execute();
}
// for admin panel

function fetch_all_user(){
    $db = new DBcontrol();
    $sql = "SELECT id, name, email, phone, address FROM users";
    $res = $db->query($sql);
    return $res;
}
function search_user($query){
    $db = new DBcontrol();
    $sql = "SELECT id, name, email, phone, address FROM users WHERE name LIKE '%$query%'";
    $res = $db->query($sql);
    return $res;
}