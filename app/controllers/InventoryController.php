<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // DB Connection

class InventoryController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ✅ সব ইনভেন্টরি আইটেম পাওয়া
    public function getAllItems()
    {
        $result = $this->conn->query("SELECT * FROM inventory ORDER BY created_at DESC");
        $items = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $items[] = $row;
            }
        }
        return $items;
    }

    // ✅ নির্দিষ্ট আইটেম পাওয়া
    public function getItemById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM inventory WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ নতুন আইটেম যোগ করা
    public function addItem($name, $category, $quantity, $price, $description)
    {
        $stmt = $this->conn->prepare("INSERT INTO inventory (name, category, quantity, price, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $name, $category, $quantity, $price, $description);
        return $stmt->execute();
    }

    // ✅ আইটেম আপডেট
    public function updateItem($id, $name, $category, $quantity, $price, $description)
    {
        $stmt = $this->conn->prepare("UPDATE inventory SET name=?, category=?, quantity=?, price=?, description=? WHERE id=?");
        $stmt->bind_param("ssidsi", $name, $category, $quantity, $price, $description, $id);
        return $stmt->execute();
    }

    // ✅ আইটেম মুছে ফেলা
    public function deleteItem($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM inventory WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ✅ স্টক আপডেট (যেমন মেডিসিন বিক্রি হলে বা নতুন স্টক এলে)
    public function updateStock($id, $quantity_change)
    {
        $stmt = $this->conn->prepare("UPDATE inventory SET quantity = quantity + ? WHERE id=?");
        $stmt->bind_param("ii", $quantity_change, $id);
        return $stmt->execute();
    }

    // ✅ কম স্টক চেক
    public function getLowStockItems($threshold = 10)
    {
        $stmt = $this->conn->prepare("SELECT * FROM inventory WHERE quantity <= ?");
        $stmt->bind_param("i", $threshold);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }
}

// ✅ Example Usage
$inventoryController = new InventoryController($conn);

// Example: সব ইনভেন্টরি দেখা
// $items = $inventoryController->getAllItems();

?>
