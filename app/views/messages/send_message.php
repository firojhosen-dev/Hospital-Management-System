<?php
session_start();
require_once '../../../config/config.php';

// ✅ লগইন চেক
if (!isset($_SESSION['user_id'])) {
    die("Unauthorized");
}

$sender_id = $_SESSION['user_id'];  // admin / doctor / patient
$receiver_id = $_POST['receiver_id']; 
$message = trim($_POST['message']);

if ($message != "") {
    $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
    $stmt->execute();
    echo "success";
} else {
    echo "empty";
}
?>
