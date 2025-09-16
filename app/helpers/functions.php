<?php
// functions.php
session_start();

require_once __DIR__ . '/../config/config.php';

/**
 * Sanitize Input
 */
function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * Redirect Helper
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

/**
 * Session Check - User Logged In কি না
 */
function checkLogin() {
    if (!isset($_SESSION['user_id'])) {
        redirect("../auth/login.php");
    }
}

/**
 * Role Check
 */
function checkRole($role) {
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
        redirect("../auth/login.php");
    }
}

/**
 * Get Logged-in User Info
 */
function getUser($conn, $id, $role = 'users') {
    $stmt = $conn->prepare("SELECT * FROM $role WHERE id=? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

/**
 * File Upload (Profile Picture, Reports, etc.)
 */
function uploadFile($file, $target_dir = "../uploads/") {
    $fileName = basename($file["name"]);
    $targetFile = $target_dir . time() . "_" . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Allowed types
    $allowed = ["jpg", "jpeg", "png", "gif", "pdf"];

    if (!in_array($fileType, $allowed)) {
        return ["status" => false, "message" => "Invalid file type!"];
    }

    if ($file["size"] > 5 * 1024 * 1024) { // 5MB
        return ["status" => false, "message" => "File is too large!"];
    }

    if (move_uploaded_file($file["tmp_name"], $targetFile)) {
        return ["status" => true, "file" => $targetFile];
    } else {
        return ["status" => false, "message" => "Failed to upload file!"];
    }
}

/**
 * Success/Error Message Show
 */
function setMessage($msg, $type = "success") {
    $_SESSION['msg'] = ["text" => $msg, "type" => $type];
}

function showMessage() {
    if (isset($_SESSION['msg'])) {
        $msg = $_SESSION['msg'];
        $color = $msg['type'] === "success" ? "green" : "red";
        echo "<p style='color:{$color};'>{$msg['text']}</p>";
        unset($_SESSION['msg']);
    }
}
