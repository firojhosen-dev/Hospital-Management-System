<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // DB Connection

class PatientController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ✅ রোগীর প্রোফাইল পাওয়া
    public function getProfile($id)
    {
        $stmt = $this->conn->prepare("SELECT id, name, email, phone, profile_picture FROM users WHERE id=? AND role='patient'");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ প্রোফাইল আপডেট
    public function updateProfile($id, $name, $email, $phone, $profile_picture = null)
    {
        if ($profile_picture) {
            $stmt = $this->conn->prepare("UPDATE users SET name=?, email=?, phone=?, profile_picture=? WHERE id=? AND role='patient'");
            $stmt->bind_param("ssssi", $name, $email, $phone, $profile_picture, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE users SET name=?, email=?, phone=? WHERE id=? AND role='patient'");
            $stmt->bind_param("sssi", $name, $email, $phone, $id);
        }
        return $stmt->execute();
    }

    // ✅ ডাক্তার লিস্ট
    public function getDoctors()
    {
        $result = $this->conn->query("SELECT id, name, email, phone, profile_picture FROM users WHERE role='doctor' AND status='approved'");
        $doctors = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
        }
        return $doctors;
    }

    // ✅ অ্যাপয়েন্টমেন্ট বুক
    public function bookAppointment($patient_id, $doctor_id, $date)
    {
        $stmt = $this->conn->prepare("INSERT INTO appointments (patient_id, doctor_id, date, status) VALUES (?, ?, ?, 'pending')");
        $stmt->bind_param("iis", $patient_id, $doctor_id, $date);
        return $stmt->execute();
    }

    // ✅ রোগীর অ্যাপয়েন্টমেন্ট লিস্ট
    public function getAppointments($patient_id)
    {
        $stmt = $this->conn->prepare("SELECT a.id, d.name as doctor_name, a.date, a.status 
                                      FROM appointments a
                                      JOIN users d ON a.doctor_id = d.id
                                      WHERE a.patient_id=? ORDER BY a.date DESC");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $appointments = [];
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
        return $appointments;
    }

    // ✅ অ্যাপয়েন্টমেন্ট ক্যানসেল
    public function cancelAppointment($appointment_id, $patient_id)
    {
        $stmt = $this->conn->prepare("UPDATE appointments SET status='cancelled' WHERE id=? AND patient_id=?");
        $stmt->bind_param("ii", $appointment_id, $patient_id);
        return $stmt->execute();
    }

    // ✅ রোগীর বিল লিস্ট
    public function getBilling($patient_id)
    {
        $stmt = $this->conn->prepare("SELECT id, amount, status, created_at FROM billing WHERE patient_id=? ORDER BY created_at DESC");
        $stmt->bind_param("i", $patient_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $bills = [];
        while ($row = $result->fetch_assoc()) {
            $bills[] = $row;
        }
        return $bills;
    }
}

// ✅ Controller use example
$patientController = new PatientController($conn);

// Example: রোগীর প্রোফাইল দেখা
// $profile = $patientController->getProfile($_SESSION['user_id']);

?>
