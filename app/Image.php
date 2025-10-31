<?php 

require_once 'DBcontrol.php';


function get_image($product_id) {
    $db_obj = new DBcontrol();
    $query = "SELECT * FROM image WHERE ref_id = '$product_id'";
    $result = $db_obj->query($query);
    if($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['image_binary'];
    }
    return null;
}
function get_image_type($product_id) {
    
    $db_obj = new DBcontrol();
    $query = "SELECT type, image_binary FROM image WHERE image_name = '$product_id'";
    $result = $db_obj->query($query);
    if($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['type'];
    }
    return null;
}

function add_image($image_name, $ref_id,$type, $image) {

    $db_obj = new DBcontrol();
    $query = "INSERT INTO image (image_name, ref_id,type, image_binary) 
              VALUES (?, ?,?, ?)";
    $stmt = $db_obj->prepare($query);
    if (!$stmt) {
        error_log('Prepare failed: ' . $db_obj->mysqli->error);
        return false;
    }

    $image_binary = @file_get_contents($image);
    if ($image_binary === false) {
        error_log('Failed to read image file: ' . $image);
        return false;
    }

    // For blobs, bind a NULL placeholder then send the data using send_long_data
    $null = null;
    if (!$stmt->bind_param("sisb", $image_name, $ref_id, $type, $null)) {
        error_log('bind_param failed: ' . $stmt->error);
        return false;
    }
    if (!$stmt->send_long_data(3, $image_binary)) {
        error_log('send_long_data failed: ' . $stmt->error);
    }

    $res = $stmt->execute();
    if ($res === false) {
        error_log('Execute failed: ' . $stmt->error);
    }
    return $res;
}

function delete_image($product_id) {
    $db_obj = new DBcontrol();
    $query = "DELETE FROM image WHERE product_id = '$product_id'";
    $result = $db_obj->query($query);
    if($result) {
        return true;
    }
    return false;
}