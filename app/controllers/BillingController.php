<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // DB Connection

class BillingController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ✅ সব বিল দেখানো
    public function index()
    {
        $sql = "SELECT * FROM billing ORDER BY created_at DESC";
        $result = $this->conn->query($sql);
        $bills = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bills[] = $row;
            }
        }
        return $bills;
    }

    // ✅ একক বিল পাওয়া
    public function getBill($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM billing WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ নতুন বিল তৈরি করা
    public function create($patient_id, $amount, $description)
    {
        $stmt = $this->conn->prepare("INSERT INTO billing (patient_id, amount, description, status, created_at) VALUES (?, ?, ?, 'unpaid', NOW())");
        $stmt->bind_param("ids", $patient_id, $amount, $description);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // ✅ বিল আপডেট করা (amount, description, status)
    public function update($id, $amount, $description, $status)
    {
        $stmt = $this->conn->prepare("UPDATE billing SET amount = ?, description = ?, status = ? WHERE id = ?");
        $stmt->bind_param("dssi", $amount, $description, $status, $id);
        return $stmt->execute();
    }

    // ✅ বিল ডিলিট করা
    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM billing WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

// ✅ উদাহরণ ব্যবহার
$billingController = new BillingController($conn);

// সব বিল লিস্ট করার জন্য
// $bills = $billingController->index();

?>
