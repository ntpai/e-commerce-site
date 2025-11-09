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

function get_product_by_id(int $product_id): array | null {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE id = '$product_id'";
    $result = $db->query($sql);
    if(!$result) {
        return null;
    }
    return $result->fetch_assoc();
}

function get_product_name(int $product_id) {
    $db = new DBcontrol();
    $sql = "SELECT name FROM products WHERE id = '$product_id'";
    $result = $db->query($sql);
    if($result) {
        $row = $result->fetch_assoc();
        return $row['name'];
    }
    return null;
}

function get_products_by_sales() {
    $db = new DBcontrol();
    $sql = "SELECT id, name, stock, sold_count FROM products ORDER BY sold_count DESC LIMIT 5";
    return $db->query($sql);
}

function get_all_products() {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products";
    return $db->query($sql);
}
function get_all_products_category() {
    $db = new DBcontrol();
    $sql = "SELECT id, name, stock FROM products ORDER BY category";
    $result = $db->query($sql);
    if(!$result) {
        return null;
    }
    // return the full result set so callers can iterate over all rows
    return $result;
}

function get_categories(): array {
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

function search($name, $category = null) {
    $db = new DBcontrol();
    if ($category == null) {
        $sql = "SELECT * FROM products WHERE name LIKE ? AND category = ?";
        $stmt = $db->prepare($sql);
        $like_name = '%' . $name . '%';
        $stmt->bind_param("ss", $like_name, $category);
    } else {
        $sql = "SELECT * FROM products WHERE name LIKE ?";
        $stmt = $db->prepare($sql);
        $like_name = '%' . $name . '%';
        $stmt->bind_param("s", $like_name);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result;
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
    $sql = "DELETE FROM products WHERE id = '$product_id'";
    return $db->query($sql);
}

// Updating products

function update_product_name(string $product_name, int $product_id) {
    $db = new DBcontrol();
    $sql = "UPDATE products SET name = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $product_name, $product_id);
    $result = $stmt->execute();
    return $result;
}

function update_stock(int $stock, int $product_id) {
    $db = new DBcontrol();
    $sql = "UPDATE products SET stock = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $stock, $product_id);
    return $stmt->execute();
}

function update_price(int $price, int $product_id) {
    $db = new DBcontrol();
    $sql = "UPDATE products SET price = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("ii", $price, $product_id);
    return $stmt->execute();
}

function update_category(string $category, int $product_id) {
    $db = new DBcontrol();
    $sql = "UPDATE products SET category = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $category, $product_id);
    return $stmt->execute();
}

function update_status(string $status, int $product_id) {
    $db = new DBcontrol();
    $sql = "UPDATE products SET status = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $status, $product_id);
    return $stmt->execute();
}

function update_description(string $description, int $product_id) {
    $db = new DBcontrol();
    $sql = "UPDATE products SET description = ? WHERE id = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("si", $description, $product_id);
    return $stmt->execute();
}
// for dashboard overview
function get_inactive_products() {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE active = 0";
    $result = $db->query($sql);
    return $result->num_rows;
}
function get_active_products() {
    $db = new DBcontrol();
    $sql = "SELECT * FROM products WHERE status = 'Active'";
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