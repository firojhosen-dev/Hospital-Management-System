<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // DB Connection

class AdminController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ✅ Dashboard summary data
    public function dashboardSummary()
    {
        $summary = [];

        $summary['patients'] = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role='patient'")->fetch_assoc()['total'];
        $summary['doctors']  = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role='doctor'")->fetch_assoc()['total'];
        $summary['admins']   = $this->conn->query("SELECT COUNT(*) as total FROM users WHERE role='admin'")->fetch_assoc()['total'];
        $summary['billing']  = $this->conn->query("SELECT COUNT(*) as total FROM billing")->fetch_assoc()['total'];

        return $summary;
    }

    // ✅ All User List
    public function getUsers()
    {
        $result = $this->conn->query("SELECT id, name, email, phone, role, status FROM users ORDER BY id DESC");
        $users = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }
        return $users;
    }

    // ✅ Get a sleepy user
    public function getUser($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, phone, role, status FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ Get New User 
    public function addUser($name, $email, $phone, $password, $role = 'patient')
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->conn->prepare("INSERT INTO users (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, 'active')");
        $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role);
        return $stmt->execute();
    }

    // ✅ User update
    public function updateUser($id, $name, $email, $phone, $role, $status)
    {
        $stmt = $this->conn->prepare("UPDATE users SET name=?, email=?, phone=?, role=?, status=? WHERE id=?");
        $stmt->bind_param("sssssi", $name, $email, $phone, $role, $status, $id);
        return $stmt->execute();
    }

    // ✅ User Delete
    public function deleteUser($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ✅ Doctor approve
    public function approveDoctor($id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status='approved' WHERE id=? AND role='doctor'");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ✅ Doctor reject
    public function rejectDoctor($id)
    {
        $stmt = $this->conn->prepare("UPDATE users SET status='rejected' WHERE id=? AND role='doctor'");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ✅ List all the bills
    public function getAllBilling()
    {
        $result = $this->conn->query("SELECT b.id, u.name as patient_name, b.amount, b.status, b.created_at 
                                    FROM billing b 
                                    JOIN users u ON b.patient_id = u.id
                                    ORDER BY b.created_at DESC");
        $bills = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bills[] = $row;
            }
        }
        return $bills;
    }

    // ✅ all appointment List
    public function getAppointments()
    {
        $result = $this->conn->query("SELECT a.id, u.name as patient_name, d.name as doctor_name, a.date, a.status
                                    FROM appointments a
                                    JOIN users u ON a.patient_id = u.id
                                    JOIN users d ON a.doctor_id = d.id
                                    ORDER BY a.date DESC");
        $appointments = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $appointments[] = $row;
            }
        }
        return $appointments;
    }
}

// ✅ Controller use example
$adminController = new AdminController($conn);

// Example: Dashboard data
// $summary = $adminController->dashboardSummary();

?>
