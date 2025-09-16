<?php
session_start();
require_once __DIR__ . '/../../config/config.php'; // DB Connection

class DoctorController {

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ✅ সব ডাক্তার পাওয়া
    public function getAllDoctors()
    {
        $result = $this->conn->query("SELECT * FROM doctors ORDER BY created_at DESC");
        $doctors = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $doctors[] = $row;
            }
        }
        return $doctors;
    }

    // ✅ নির্দিষ্ট ডাক্তার পাওয়া
    public function getDoctorById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM doctors WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ✅ ডাক্তার যোগ করা
    public function addDoctor($name, $specialization, $phone, $email, $password, $profile_picture = "default.jpg")
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO doctors (name, specialization, phone, email, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $specialization, $phone, $email, $hashedPassword, $profile_picture);
        return $stmt->execute();
    }

    // ✅ ডাক্তার আপডেট
    public function updateDoctor($id, $name, $specialization, $phone, $email, $profile_picture = null)
    {
        if ($profile_picture) {
            $stmt = $this->conn->prepare("UPDATE doctors SET name=?, specialization=?, phone=?, email=?, profile_picture=? WHERE id=?");
            $stmt->bind_param("sssssi", $name, $specialization, $phone, $email, $profile_picture, $id);
        } else {
            $stmt = $this->conn->prepare("UPDATE doctors SET name=?, specialization=?, phone=?, email=? WHERE id=?");
            $stmt->bind_param("ssssi", $name, $specialization, $phone, $email, $id);
        }
        return $stmt->execute();
    }

    // ✅ ডাক্তার মুছে ফেলা
    public function deleteDoctor($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM doctors WHERE id=?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    // ✅ ডাক্তার লগইন
    public function loginDoctor($email, $password)
    {
        $stmt = $this->conn->prepare("SELECT * FROM doctors WHERE email=? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctor = $result->fetch_assoc();

        if ($doctor && password_verify($password, $doctor['password'])) {
            $_SESSION['user_id'] = $doctor['id'];
            $_SESSION['user_role'] = "doctor";
            $_SESSION['user_name'] = $doctor['name'];
            return true;
        }
        return false;
    }

    // ✅ ডাক্তার প্রোফাইল আপডেট (নিজের জন্য)
    public function updateProfile($id, $name, $phone, $email, $profile_picture = null)
    {
        return $this->updateDoctor($id, $name, null, $phone, $email, $profile_picture);
    }

    // ✅ ডাক্তার স্পেশালাইজেশন দিয়ে খোঁজা
    public function getDoctorsBySpecialization($specialization)
    {
        $stmt = $this->conn->prepare("SELECT * FROM doctors WHERE specialization LIKE ?");
        $like = "%".$specialization."%";
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $result = $stmt->get_result();
        $doctors = [];
        while ($row = $result->fetch_assoc()) {
            $doctors[] = $row;
        }
        return $doctors;
    }
}

// ✅ Example Usage
$doctorController = new DoctorController($conn);

// Example: সব ডাক্তার পাওয়া
// $doctors = $doctorController->getAllDoctors();

?>
