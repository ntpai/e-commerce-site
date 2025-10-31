<?php 

require_once "DBcontrol.php";

function add_product($name, $category, $description, $price, $stock): bool {
    $db = new DBcontrol();
    $sql = "INSERT INTO products (name, category, description, price, stock) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssdi", $name, $category, $description, $price, $stock);
    if(!$stmt){
        return false;   
    }
    $stmt->execute();
    $stmt->close();
    return true;
}

function get_product_id($product_name) {
    $db_obj = new DBcontrol();
    $query = "SELECT id FROM products WHERE name = ?";
    $stmt = $db_obj->prepare($query);
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['id'];
    }
    return false;
} 

function get_product_by_id(string $product_name) {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE product_name = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $product_name);
    if(!$stmt) {
        return false;
    }
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $stmt->close();
    return $row ?: false;
}

function get_products_by_sales() {
    $db = new DBcontrol();
    $sql = "SELECT id, name, stock, sold_count FROM products ORDER BY sold_count DESC LIMIT 3";
    return $db->query($sql);
}

function get_all_products() {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products";
    return $db->query($sql);
}

function get_categories() {
    $db = new DBcontrol();
    $sql = "SELECT DISTINCT category FROM products";
    $result = $db->query($sql);
    $categories = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row['category'];
        }
    }
    return $categories;
}

function get_products_by_category($category) {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE category = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
}
function delete_product($product_id): bool {
    $db = new DBcontrol();
    $sql = "DELETE FROM products WHERE product_id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}

function get_inactive_products() {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE active = 0";
    $result = $db->query($sql);
    return $result->num_rows;
}
function get_active_products() {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE active = 1";
    $result = $db->query($sql);
    return $result->num_rows;
}

function get_product_count() {
    $db = new DBcontrol();
    $sql = "SELECT COUNT(*) as count FROM products";
    $result = $db->query($sql);
    $row = $result->fetch_assoc();
    return $row['count'];
}