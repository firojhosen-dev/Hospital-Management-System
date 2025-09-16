<?php
require_once(__DIR__ . '/../../config/config.php'); // ডাটাবেজ কানেকশন

class AuthController {

    // ✅ User Registration
    public function register($name, $email, $password, $phone, $gender, $role) {
        global $conn;

        // Check if email already exists
        $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $result = $check->get_result();

        if ($result->num_rows > 0) {
            return "Email already exists!";
        }

        // Hash Password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, gender, role) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $hashed_password, $phone, $gender, $role);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "Registration failed!";
        }
    }

    // ✅ User Login
    public function login($email, $password) {
        global $conn;

        $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];
                return "success";
            } else {
                return "Incorrect password!";
            }
        } else {
            return "Email not found!";
        }
    }

    // ✅ Logout
    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../../public/index.php");
        exit();
    }
}