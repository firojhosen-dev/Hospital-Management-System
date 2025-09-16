<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../../auth/login.php");
    exit();
}
require_once "../../../../config/config.php";

// Check if ID is passed
if (!isset($_GET['id'])) {
    die("Product ID missing");
}
$id = (int)$_GET['id'];

// Delete query
$delete = $conn->query("DELETE FROM products WHERE id=$id");

if ($delete) {
    header("Location: inventory.php?msg=deleted");
    exit;
} else {
    echo "Error deleting record: " . $conn->error;
}
?>
